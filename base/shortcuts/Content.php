<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo      
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Shortcut For Content Class       
 */
 
Class content {

    public static function set_view_folder($func = 'view', $folder = '')
    {   
        return ob::instance()->content->set_view_folder($func, $folder);
    }
    
    // ------------------------------------------------------------------------  

    public static function script($filename, $data = array())
    {   
        return ob::instance()->content->script($filename, $data);
    }
    
    // ------------------------------------------------------------------------  
    
    public static function app_script($filename, $data = array())
    {   
        return ob::instance()->content->app_script($filename, $data);
    }
    
    // ------------------------------------------------------------------------
    
    public static function base_script($filename, $data = array())
    {   
        return ob::instance()->content->base_script($filename, $data);
    }
    
    // ------------------------------------------------------------------------
    
    public static function view($filename, $data = array(), $string = TRUE)
    {               
        return ob::instance()->content->view($filename, $data, $string);
    }
    
    // ------------------------------------------------------------------------
    
    public static function app_view($filename, $data = array(), $string = FALSE)
    {
        return ob::instance()->content->app_view($filename, $data, $string); 
    }
   

} // end of the class.

?>