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
 * Shortcut For Input Class       
 */
 
Class input {
   
    public static function xss_clean($str,$is_image = FALSE) 
    {   
        return ob::instance()->input->xss_clean($str, $is_image); 
    }

    // ------------------------------------------------------------------------
    
    
    public static function post($key = '',$xss_clean = FALSE) 
    {   
        return ob::instance()->input->post($key, $xss_clean); 
    }

    // ------------------------------------------------------------------------
    
    
    public static function get($key = '',$xss_clean = FALSE)
    { 
        return ob::instance()->input->get($key, $xss_clean); 
    }

    // ------------------------------------------------------------------------
    
    
    public static function get_post($index = '',$xss_clean = FALSE)
    { 
        return ob::instance()->input->get_post($index, $xss_clean); 
    }

    // ------------------------------------------------------------------------
    
    
    public static function server($index = '',$xss_clean = FALSE)
    { 
        return ob::instance()->input->server($index, $xss_clean); 
    }
    
    // ------------------------------------------------------------------------
    
    
    public static function cookie($index = '',$xss_clean = FALSE)
    { 
        return ob::instance()->input->cookie($index, $xss_clean); 
    } 
    
    // ------------------------------------------------------------------------

    public static function ip_address()
    {
         return ob::instance()->input->ip_address(); 
    }

    // ------------------------------------------------------------------------
    
    
    public static function valid_ip($ip)
    { 
        return ob::instance()->input->valid_ip($ip); 
    }
    
    // ------------------------------------------------------------------------

    public static function user_agent()
    { 
        return ob::instance()->input->user_agent(); 
    }  
    
    
} // end of the class.

?>