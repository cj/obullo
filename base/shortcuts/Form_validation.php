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
 * Shortcut For Config Class       
 */
 
Class form_validation {

    public static function set_rules($field, $label = '', $rules = '') 
    { 
        return ob::instance()->form_validation->set_rules($field, $label, $rules); 
    }
    
    // ------------------------------------------------------------------------  
    
    public static function set_message($lang, $val = '')
    { 
        return ob::instance()->form_validation->set_message($lang, $val); 
    }
    
    // ------------------------------------------------------------------------ 
    
    public static function set_error_delimiters($prefix = '<p>', $suffix = '</p>')
    {
        return ob::instance()->form_validation->set_error_delimiters($prefix, $suffix); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function run($group = '')
    { 
        return ob::instance()->form_validation->run($group); 
    }

} // end of the class. 

?>