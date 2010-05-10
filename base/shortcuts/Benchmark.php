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
 * Shortcut For Benchmark Class       
 */
 
Class benchmark {
   
    public static function mark($name) 
    {   
        return ob::instance()->benchmark->mark($name); 
    }

    // ------------------------------------------------------------------------
    
    public static function elapsed_time($point1 = '', $point2 = '', $decimals = 4)
    {
        return ob::instance()->benchmark->elapsed_time($point1, $point2, $decimals);
    }
    
    // ------------------------------------------------------------------------
    
    public static function memory_usage()
    {
        return ob::instance()->benchmark->memory_usage();
    }

} // end of the class.

?>