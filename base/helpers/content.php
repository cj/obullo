<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @filesource
 * @license
 */
 
Class ContentException extends CommonException {}  
 
/**
 * Obullo Content Builder Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Language
 * @author      Ersin Guvenc
 * @version     0.1
 * @version     0.2 added empty $data string support
 * @version     0.3 added set_view_folder function, added return , fail gracefully function for views.
 * @version     0.4 added img_folder to content_set_folder() function.
 * @link        
 */
 
$_cont = ssc::instance();
$_cont->_ent = new stdClass();

$_cont->_ent->view_folder     = DS. '';
$_cont->_ent->app_view_folder = DS. '';
$_cont->_ent->css_folder      = '/';
$_cont->_ent->img_folder      = '/';
                                           
log_message('debug', "Content Helper Initialized");

// ------------------------------------------------------------------------ 

/**
* Create your custom folders and
* change all your view paths for the
* supporting multiple interfaces (iphone interface, xml services
* etc ..)
* 
* @author   Ersin Guvenc
* @param    string $func view function
* @param    string $folder view folder (no trailing slash)
* @version  0.1                 
* @version  0.2 added img folder
*/
function content_set_folder($func = 'view', $folder = '')
{
    $cont = ssc::instance();
    $folder_path = empty($folder) ? DS : $folder. DS;
    
    switch ($func)
    {
       case 'view':
         $cont->_ent->view_folder     = DS. $folder_path;
         break;
         
       case 'app_view':
         $cont->_ent->app_view_folder = DS. $folder_path;  
         break;
         
       case 'css':
         $cont->_ent->css_folder      = $folder;  
         break;
         
       case 'img':
         $cont->_ent->img_folder      = $folder;  
         break;
    }
    
    return TRUE;
}

// ------------------------------------------------------------------------ 

/**
* Load inline script file from
* local folder.
* 
* @param string $filename
* @param array  $data
*/
function content_script($filename, $data = '')
{   
    return _load_script(DIR .$GLOBALS['d']. DS .'scripts'. DS, $filename, $data);
}

// ------------------------------------------------------------------------

/**
* Load inline script file from
* application folder.
* 
* @param string $filename
* @param array  $data
*/
function content_app_script($filename, $data = '')
{   
    return _load_script(APP .'scripts'. DS, $filename, $data);
}

/**
* Load inline script file from
* base folder.
* 
* @param string $filename
* @param array  $data
*/
function content_base_script($filename, $data = '')
{   
    return _load_script(BASE .'scripts'. DS, $filename, $data);
}

/**
* Load local view file
* 
* @param string  $filename
* @param array   $data
* @param boolean $string
* @return void
*/
function content_view($filename, $data = '', $string = TRUE)
{               
    $cont = ssc::instance();
    $return = FALSE;

    if(isset($cont->_ent->view_folder{1})) { $return = TRUE; }    // if view folder changed don't show errors ..

    $path =  DIR .$GLOBALS['d']. DS .'views'. $cont->_ent->view_folder;
    
    $cont->_profiler_local_views[$filename] = $path . $filename .EXT; 
    
    return _load_view($path, $filename, $data, $string, $return);
}

// ------------------------------------------------------------------------

/**
* Load global view file
* 
* @param string  $filename
* @param array   $data
* @param boolean $string
* @return void
*/
function content_app_view($filename, $data = '', $string = FALSE)
{
    $cont = ssc::instance(); 
    $return = FALSE;
    
    if(isset($cont->_ent->app_view_folder{1})) { $return = TRUE; }  // if view folder changed don't show errors ..
    
    $path = APP .'views'. $cont->_ent->app_view_folder;
    
    $cont->_profiler_app_views[$filename] = $path . $filename .EXT; 
    
    return _load_view($path, $filename, $data, $string, $return); 
}

// ------------------------------------------------------------------------

/**
* Load Java script files externally
* like fetch view files as string
* 
* @author   Ersin Guvenc
* @access   private
* @param    string  $path
* @param    string  $filename
* @param    array   $data
* @version  0.1
* @version  0.2 added empty $data
* @param    array  $data
*/
function _load_script($path, $filename, $data = '')
{
    if( empty($data) ) $data = array();
    
    if ( ! file_exists($path . $filename . EXT) )
    {
        throw new ContentException('Unable locate the script file: '. $path . $filename . EXT);
    } 
    
    $data = _ob_object_to_array($data);
    
    if(sizeof($data) > 0) { extract($data, EXTR_SKIP); }
    
    ob_start();
    
    include($path . $filename . EXT);
    $content = ob_get_contents();
    
    ob_end_clean();
                           
    log_message('debug', 'Script file loaded: '.$path . $filename . EXT); 
    
    ssc::instance()->_profiler_scripts[] = $filename;
    
    return "\n".$content; 
}

// ------------------------------------------------------------------------ 
    
/**
* Main view function        
* 
* @author   Ersin Guvenc
* @access   private          
* @param    string   $path file path 
* @param    string   $filename 
* @param    array    $data template vars
* @param    boolean  $string 
* @param    boolean  $return 
* @version  0.1
* @version  0.2 added empty $data
* @version  0.3 added $return param
* @version  0.4 added log_message()
* @return   void
*/
function _load_view($path, $filename, $data = '', $string = FALSE, $return = FALSE)
{   
    if( empty($data) ) $data = array();

    if ( ! file_exists($path . $filename . EXT) )
    {
        if($return) 
        { 
            log_message('debug', 'View file not found: '. $path . $filename . EXT);
            
            return;     // fail gracefully for different interfaces ..
                        // iphone etc..
        }  
                             
        throw new ContentException('Unable locate the view file: '. $filename . EXT);
    } 
    
    $data = _ob_object_to_array($data);
    
    if(sizeof($data) > 0) { extract($data, EXTR_SKIP); }
    
    ob_start();
    
    include($path . $filename . EXT);
    
    log_message('debug', 'Content file loaded: '.$path . $filename . EXT);

    if($string === TRUE)
    {
        $content = ob_get_contents();
        @ob_end_clean();
        
        return $content;
    }
    
    // Set Global views inside to Output Class for caching functionality..
    base_register('Output')->append_output(ob_get_contents());
    
    @ob_end_clean();
    
    return;
    
    throw new LoaderException('Unable to locate the view: ' . $filename . EXT);
}


/**
* Object to Array
*
* Takes an object as input and converts the class variables to array key/vals
*
* @access   private
* @param    object
* @return   array
*/
function _ob_object_to_array($object)
{
    return (is_object($object)) ? get_object_vars($object) : $object;
}


/* End of file content.php */
/* Location: ./base/helpers/content.php */
?>