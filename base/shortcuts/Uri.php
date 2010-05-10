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
 * Shortcut For URI Class       
 */
 
Class uri {
   
    public static function segment($n, $no_result = FALSE)  
    {   
        return ob::instance()->uri->segment($n, $no_result); 
    }

    // ------------------------------------------------------------------------  
    
    public static function rsegment($n, $no_result = FALSE)  
    {   
        return ob::instance()->uri->rsegment($n, $no_result); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function uri_to_assoc($n = 3, $default = array()) 
    {   
        return ob::instance()->uri->uri_to_assoc($n, $default); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function ruri_to_assoc($n = 3, $default = array()) 
    {   
        return ob::instance()->uri->ruri_to_assoc($n, $default); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function assoc_to_uri($array) 
    {   
        return ob::instance()->uri->assoc_to_uri($array); 
    }
    
    // ------------------------------------------------------------------------
    
    public static function slash_segment($n, $where = 'trailing')
    {   
        return ob::instance()->uri->slash_segment($n, $where) ; 
    }
    
    // --------------------------------------------------------------------
    
    public static function segment_array()
    {
        return ob::instance()->uri->segment_array();
    }

    // --------------------------------------------------------------------
    
    public static function rsegment_array()
    {
        return ob::instance()->uri->rsegments;
    }
    
    // --------------------------------------------------------------------
   
    public static function total_segments()
    {
        return ob::instance()->uri->total_segments();
    }

    // --------------------------------------------------------------------
    
    public static function total_rsegments()
    {
        return ob::instance()->uri->total_rsegments();
    }
    
    // --------------------------------------------------------------------
    
    public static function uri_string()
    {
        return ob::instance()->uri->uri_string;
    }
    
    // --------------------------------------------------------------------
    
    public static function ruri_string()
    {
        return ob::instance()->uri->ruri_string();
    }
    
    
} // end of the class.

?>