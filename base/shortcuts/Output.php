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
 * Shortcut For Output Class       
 */
 
Class output {
   
    public static function set_output($output) 
    {   
        return ob::instance()->output->set_output($output); 
    }

    // ------------------------------------------------------------------------
    
    public static function get_output() 
    {   
        return ob::instance()->output->get_output(); 
    }

    // ------------------------------------------------------------------------
    
    public static function set_header($header, $replace = TRUE)
    {   
        return ob::instance()->output->set_header($header, $replace); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function set_status_header($code = '200', $text = '')
    {   
        return ob::instance()->output->set_status_header($code, $text); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function enable_profiler($val = TRUE)
    {   
        return ob::instance()->output->enable_profiler($val); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function cache($time)
    {   
        return ob::instance()->output->cache($time); 
    }

} // end of the class.

?>