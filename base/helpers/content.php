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
 * @subpackage  Libraries
 * @category    Language
 * @author      Ersin Guvenc
 * @version     0.1
 * @version     0.2 added empty $data string support
 * @version     0.3 added set_view_folder function
 * @version     0.3 added return , fail gracefully function for views.
 * @link        
 */
 
$_cont = ssc::instance();
$_cont->_ent = new stdClass();

$_cont->_ent->view_folder     = DS. '';
$_cont->_ent->app_view_folder = DS. '';
$_cont->_ent->css_folder      = '/';

log_message('debug', "Content Helper Initialized");

// ------------------------------------------------------------------------ 

/**
* Create your custom folders and
* change all your view paths for the
* supporting multiple interfaces (iphone interface, xml services
* etc ..)
* 
* @author  Ersin Guvenc
* @param   string $func view function
* @param   string $folder view folder (no trailing slash)
*/
function content_set_folder($func = 'view', $folder = '')
{
    $cont = ssc::instance();
    switch ($func)
    {
       case 'view':
         $cont->_ent->view_folder     = DS. $folder;
         break;
         
       case 'app_view':
         $cont->_ent->app_view_folder = DS. $folder;  
         break;
         
       case 'css':
         $cont->_ent->css_folder      = '/'. $folder;  
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
* @param mixed $filename
* @param mixed $data
* @param mixed $string
* @return void
*/
function content_view($filename, $data = '', $string = TRUE)
{               
    $cont = ssc::instance();
    $return = FALSE;

    if(isset($cont->_ent->view_folder{1})) { $return = TRUE; } // if view folder changed don't show errors ..

    return _load_view(DIR .$GLOBALS['d']. DS .'views'. $cont->_ent->view_folder .DS, $filename, $data, $string, $return);
}

// ------------------------------------------------------------------------

/**
* Load global view file
* 
* @param mixed $filename
* @param mixed $data
* @param mixed $string
* @return void
*/
function content_app_view($filename, $data = '', $string = FALSE)
{
    $cont = ssc::instance(); 
    $return = FALSE;
    
    if(isset($cont->_ent->app_view_folder{1})) { $return = TRUE; }  // if view folder changed don't show errors ..
    
    return _load_view(APP .'views'. $cont->_ent->app_view_folder . DS, $filename, $data, $string, $return); 
}

// ------------------------------------------------------------------------

/**
* Load Java script files externally
* like fetch view files as string
* 
* @author   Ersin Guvenc
* @param    string $path
* @param    string $filename
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
    
    if(sizeof($data) > 0) { extract($data, EXTR_SKIP); }
    
    ob_start();
    
    include($path . $filename . EXT);
    $content = ob_get_contents();
    
    ob_end_clean();

    return "\n".$content; 
}

// ------------------------------------------------------------------------ 
    
/**
* Main view function        
* 
* @author   Ersin Guvenc          
* @param    string   $path file path 
* @param    string   $filename 
* @param    array    $data template vars
* @param    boolean  $string 
* @param    boolean  $return 
* @version  0.1
* @version  0.2 added empty $data
* @version  0.3 added $return param
* @return   void
*/
function _load_view($path, $filename, $data = '', $string = FALSE, $return = FALSE)
{   
    if( empty($data) ) $data = array();
    
    if ( ! file_exists($path . $filename . EXT) )
    {
        if($return) return;  // fail gracefully for different interfaces ..
                             // iphone etc..
        
        throw new ContentException('Unable locate the view file: '. $filename . EXT);
    } 
    
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


/* End of file content.php */
/* Location: ./base/helpers/content.php */
?>