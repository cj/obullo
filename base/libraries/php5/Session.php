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

Class SessionException extends CommonException {}  
 
// ------------------------------------------------------------------------

/**
 * Session Class
 *
 * @package      Obullo
 * @subpackage   Libraries
 * @category     Sessions
 * @author       Ersin Guvenc
 * @link         
 */
Class session_CORE implements PHP5_Driver_Library {
    
    public $session;
                   
    private static $instance;
    private static $driver;

    public static function instance()
    {
       if(! (self::$instance instanceof self))
       {
            self::$instance = new self();
       } 
       
       return self::$instance;
    }

    // --------------------------------------------------------------------
    
    public function init($params = array())
    {         
        if(isset(self::$driver))
        return;
         
        $driver = (isset($params['sess_driver'])) ? $params['sess_driver'] : config_item('sess_driver'); 
        $prefix = config_item('subclass_prefix');
        
        switch ($driver)    // driver extend support.
        {
           case 'cookie':
             $classname = 'OB_Session_cookie_driver';   
             
             if(file_exists(APP .'libraries'. DS .'php5'. DS .'drivers'. DS .'session'. DS .$prefix.'cookie'. EXT))
             {
                $classname = $prefix.'Session_cookie_driver';
             }
             $this->session = new $classname($params);
             self::$driver = 'cookie'; 
             break;
             
           case 'database':
             $classname = 'OB_Session_database_driver';
             
             if(file_exists(APP .'libraries'. DS .'php5'. DS .'drivers'. DS .'session'. DS .$prefix.'database'. EXT))
             {
                $classname = $prefix.'Session_database_driver';
             }
             $this->session = new $classname($params);
             self::$driver = 'database';  
             break;
             
           case 'native':
             $this->session = NULL; // not implemented yet.
             break;
        }
    }              
    
    // --------------------------------------------------------------------   
    
    public function __call($method, $args)
    {
        if(method_exists($this->session, $method))
        {
            return call_user_func_array(array($this->session, $method), $args);
        }
        
        throw new LoaderException('Session class method not found.');
    }
}

// END Session Class

/* End of file Session.php */
/* Location: ./base/libraries/php5/Session.php */
?>