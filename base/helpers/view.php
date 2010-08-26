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
 
Class ViewException extends CommonException {}  
 
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
 * @version     0.4 added img_folder to view_set_folder() function.
 * @version     0.5 renamed all function prefix as "view_".
 * @link        
 */
 
if( ! isset($_vi->_ew)) 
{
    $_vi = ssc::instance();
    $_vi->_ew = new stdClass();

    $_vi->_ew->view_folder     = DS. '';
    $_vi->_ew->app_view_folder = DS. '';
    $_vi->_ew->css_folder      = '/';
    $_vi->_ew->img_folder      = '/';
                                               
    log_message('debug', "View Helper Initialized");
}

// ------------------------------------------------------------------------ 

/**
* Create your custom folders and
* change all your view paths to supporting
* multiple interfaces (iphone interface etc ..)
* 
* @author   Ersin Guvenc
* @param    string $func view function
* @param    string $folder view folder (no trailing slash)
* @version  0.1                 
* @version  0.2 added img folder
*/
if ( ! function_exists('view_set_folder'))
{
    function view_set_folder($func = 'view', $folder = '')
    {
        $vi = ssc::instance();
        $folder_path = empty($folder) ? DS : $folder. DS;
        
        switch ($func)
        {
           case 'view':
             $vi->_ew->view_folder     = DS. $folder_path;
             break;
             
           case 'view_app':
             $vi->_ew->app_view_folder = DS. $folder_path;  
             break;
             
           case 'css':
             $vi->_ew->css_folder      = $folder;  
             break;
             
           case 'img':
             $vi->_ew->img_folder      = $folder;  
             break;
        }
        
        return TRUE;
    }
}
// ------------------------------------------------------------------------ 

/**
* Load inline script file from
* local folder.
* 
* @param string $filename
* @param array  $data
*/
if ( ! function_exists('view_script'))
{
    function view_script($filename, $data = '')
    {   
        return _load_script(DIR .$GLOBALS['d']. DS .'scripts'. DS, $filename, $data);
    }
}
// ------------------------------------------------------------------------

/**
* Load inline script file from
* application folder.
* 
* @param string $filename
* @param array  $data
*/
if ( ! function_exists('view_app_script'))
{
    function view_app_script($filename, $data = '')
    {   
        return _load_script(APP .'scripts'. DS, $filename, $data);
    }
}
// ------------------------------------------------------------------------

/**
* Load inline script file from
* base folder.
* 
* @param string $filename
* @param array  $data
*/
if ( ! function_exists('view_base_script'))
{
    function view_base_script($filename, $data = '')
    {   
        return _load_script(BASE .'scripts'. DS, $filename, $data);
    }
}
// ------------------------------------------------------------------------

/**
* Load local view file
* 
* @param string  $filename
* @param array   $data
* @param boolean $string
* @return void
*/
if ( ! function_exists('view'))
{
    function view($filename, $data = '', $string = TRUE)
    {               
        $vi = ssc::instance();
        $return = FALSE;

        if(isset($vi->_ew->view_folder{1})) { $return = TRUE; }    // if view folder changed don't show errors ..

        $path =  DIR .$GLOBALS['d']. DS .'views'. $vi->_ew->view_folder;
        
        $vi->_profiler_local_views[$filename] = $path . $filename .EXT; 
        
        return _load_view($path, $filename, $data, $string, $return);
    }
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
if ( ! function_exists('view_app'))
{
    function view_app($filename, $data = '', $string = FALSE)
    {
        $vi = ssc::instance(); 
        $return = FALSE;
        
        if(isset($vi->_ew->app_view_folder{1})) { $return = TRUE; }  // if view folder changed don't show errors ..
        
        $path = APP .'views'. $vi->_ew->app_view_folder;
        
        $vi->_profiler_app_views[$filename] = $path . $filename .EXT; 
        
        return _load_view($path, $filename, $data, $string, $return); 
    }
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
if ( ! function_exists('_load_script'))
{
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
if ( ! function_exists('_load_view'))
{
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
        
        log_message('debug', 'View file loaded: '.$path . $filename . EXT);

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
}
// ------------------------------------------------------------------------ 

/**
* Object to Array
*
* Takes an object as input and converts the class variables to array key/vals
*
* @access   private
* @param    object
* @return   array
*/
if ( ! function_exists('_ob_object_to_array'))
{
    function _ob_object_to_array($object)
    {
        return (is_object($object)) ? get_object_vars($object) : $object;
    }
}

/* End of file view.php */
/* Location: ./base/helpers/view.php */
?>