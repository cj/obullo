<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo
 * @subpackage      Libraries
 * @category        Libraries      
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license 
 */
 
Abstract Class Obullo_Registry
{
    abstract protected function get($key);
    //get stored object.
    abstract protected function set($key,$val);
    //set (store) object.
} 
 
Class OB_Registry extends Obullo_Registry {
    
    /** 
    * Registry array of objects 
    * @access private 
    */  
    private static $objs = array();
    private static $instance;
    
    //prevent directly access.
    private function __construct(){}
    
    //prevent clone. 
    public function __clone(){}

    /** 
    * singleton method used to access the object 
    * @access public 
    */  
    public static function instance()
    {
        if( ! isset(self::$instance))
        {
            self::$instance = new self();
        }
         
        return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get stored object.
    * 
    * @access   protected
    * @param    string $key
    * @return   object | NULL.
    */
    protected function get($key)
    {
        if(isset(self::$objs[$key]))
        {
            return self::$objs[$key];
        }
        
        return NULL;
    }

    // --------------------------------------------------------------------
    
    /**
    * Set object.
    * 
    * @access   protected 
    * @param    string $key
    * @param    object $val
    */
    protected function set($key,$val)
    {
        self::$objs[$key] = $val;
    }

    // --------------------------------------------------------------------
    
    /**
    * Get stored object.
    * 
    * @param    string $key
    * @return   object
    */
    public static function get_object($key)
    {
        return self::instance()->get($key);
    }
    
    // --------------------------------------------------------------------

    /**
    * Set class instance.
    * 
    * @param    string $key
    * @param    object $instance
    */
    public static function set_object($key, $instance)
    {
        return self::instance()->set($key, $instance);
    }

}

// END Registry Class

/* End of file Registry.php */
/* Location: ./base/obullo/Registry.php */ 
?>