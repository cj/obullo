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
 * Shortcut For Agent Class       
 */
 
Class agent {
   
    public static function is_browser() 
    {   
        return ob::instance()->agent->is_browser(); 
    }

    // ------------------------------------------------------------------------
    
       
    public static function is_mobile() 
    {   
        return ob::instance()->agent->is_mobile(); 
    }

    // ------------------------------------------------------------------------
    
    public static function is_robot() 
    {   
        return ob::instance()->agent->is_robot(); 
    }

    // ------------------------------------------------------------------------
    
    public static function is_referral() 
    {   
        return ob::instance()->agent->is_referral();
    }

    // ------------------------------------------------------------------------
    
    public static function browser() 
    {   
        return ob::instance()->agent->browser();
    }

    // ------------------------------------------------------------------------
    
    public static function version() 
    {   
        return ob::instance()->agent->version();
    }

    // ------------------------------------------------------------------------
    
    public static function mobile() 
    {   
        return ob::instance()->agent->mobile();
    }

    // ------------------------------------------------------------------------
    
    public static function robot() 
    {   
        return ob::instance()->agent->robot();
    }

    // ------------------------------------------------------------------------
    
    public static function platform() 
    {   
        return ob::instance()->agent->platform();
    }

    // ------------------------------------------------------------------------
    
    public static function referrer() 
    {   
        return ob::instance()->agent->referrer();
    }

    // ------------------------------------------------------------------------
    
    public static function agent_string() 
    {   
        return ob::instance()->agent->agent_string();
    }

    // ------------------------------------------------------------------------
    
    public static function accept_lang() 
    {   
        return ob::instance()->agent->accept_lang();
    }

    // ------------------------------------------------------------------------
    
    public static function accept_charset() 
    {   
        return ob::instance()->agent->accept_charset();
    }

    // ------------------------------------------------------------------------

} // end of the class.

?>