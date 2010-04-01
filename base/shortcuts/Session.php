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
 * Shortcut For Session Class       
 */
 
Class session {
   
    public static function set($newdata = array(),$newval = '')
    { 
        return ob::instance()->session->set_userdata($newdata, $newval); 
    }
    
    // ------------------------------------------------------------------------
     
    public static function get($item) 
    { 
        return ob::instance()->session->userdata($item); 
    } 
    
    // ------------------------------------------------------------------------

    public static function un_set($newdata) 
    { 
        return ob::instance()->session->unset_userdata($newdata); 
    } 
    
    // ------------------------------------------------------------------------
    
    public static function set_flash($newdata = array(), $newval = '')
    { 
        return ob::instance()->session->set_flashdata($newdata,$newval); 
    } 
    
    // ------------------------------------------------------------------------
    
    public static function get_flash($key) 
    { 
        return ob::instance()->session->flashdata($key); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function keep_flash($key)
    { 
        return ob::instance()->session->keep_flashdata($key); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function destroy()
    { 
        return ob::instance()->session->sess_destroy(); 
    }
    
} // end of the class. 

?>
