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
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Shortcut For Config Class       
 */
 
Class config {
   
    public static function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE) 
    {   
        return ob::instance()->config->load($file, $use_sections, $fail_gracefully); 
    }

    // ------------------------------------------------------------------------
    
    
    public static function item($item, $index = '')   
    {   
        return ob::instance()->config->item($item, $index);
    }
    
    // ------------------------------------------------------------------------
    
    
    public static function slash_item($item)   
    {   
        return ob::instance()->config->slash_item($item);
    }
    
    // ------------------------------------------------------------------------
    
    public static function site_url($uri = '')   
    {   
        return ob::instance()->config->site_url($uri);
    }
   
    // ------------------------------------------------------------------------
    
    public static function set_item($item, $value)   
    {   
        return ob::instance()->config->set_item($item, $value);
    }   
    

} // end of the class.

?>