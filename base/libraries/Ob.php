<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC-min Framework software for PHP 5.2.4 or newer
 * Derived from Code Igniter
 *
 * @package         obullo        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */

/**
*  o SSC Pattern (c) 2009 Ersin Güvenç
*  o We use Super Static Controllers  
*  o for prevent long writing ($this->input->post())
*  o we just write like this ob::post(); ob::lang();
*/
 
/**
 * Obullo Super Static Controller (SSC)
 * 
 * We put all static main codes here
 * Like cookie , session..
 * Goal of the SSC to get all static functions
 * like this ob::instance(), ob::session_set(), 
 * ob::cookie_get()  you can 
 * also use $this var inside from static
 * functions.
 * 
 * @version 0.1
 * @version 0.2 added core functions like ob::register
 * @version 0.3 moved some func to common.php like 
 *              ob::register
 */
 
Class SSC extends loader 
{
    //function __construct() { parent::__construct(); }
    
    //------------- Input Class Shortcut Functions -------------------//
    
    public function xss($str,$is_image = FALSE) { return $this->input->xss_clean($str, $is_image); }  
    public function post($key = '',$xss_clean = FALSE) { return $this->input->post($key, $xss_clean); }
    public function get($key = '',$xss_clean = FALSE) { return $this->input->get($key, $xss_clean); }
    public function both($index = '',$xss_clean = FALSE) { return $this->input->get_post($index, $xss_clean); }
    public function get_post($index = '',$xss_clean = FALSE) { return $this->input->get_post($index, $xss_clean); }
    public function server($index = '',$xss_clean = FALSE) { return $this->input->server($index, $xss_clean); }  
    public function cookie($index = '',$xss_clean = FALSE) { return $this->input->cookie($index, $xss_clean); } 
    public function ip() { return $this->input->ip_address(); } 
    public function valid_ip($ip) { return $this->input->valid_ip($ip); }
    public function user_agent(){ return $this->input->user_agent(); }   

    //------------- Session Class Shortcut Functions -------------------//
    
    public function set_session($newdata = array(),$newval = '') { return $this->session->set_userdata($newdata, $newval); } 
    public function session($item) { return $this->session->userdata($item); } 
    public function unset_session($newdata) { return $this->session->unset_userdata($newdata); } 
    public function set_flash($newdata = array(), $newval = '') { return $this->session->set_flashdata($newdata,$newval); } 
    public function flash($key) { return $this->session->flashdata($key); }
    public function keep_flash($key) { return $this->session->keep_flashdata($key); }
    public function kill_session() { return $this->session->sess_destroy(); }

    //------------- Language Class Shortcut Functions -------------------//
    
    public function lang_load($langfile = '', $idiom = '', $return = FALSE){ return $this->lang->load($langfile, $idiom, $return); }
    public function lang($item){ return $this->lang->line($item); }
    
    //------------- Url Helper Shortcut Functions -------------------//  
    
    public function base_url() { return $this->config->slash_item('base_url'); }
    public function current_url() { return $this->config->site_url($this->uri->uri_string()); }
    public function uri_string() { return $this->uri->uri_string(); }
    public function index_page() { return $this->config->item('index_page');  }
    
    public function config_item($item) { return config_item($item); } 
    
}  // end ssc class.


 /**
 * Obullo Super Object (c) 2009
 * 
 * @version 0.1
 * @version 0.2 added core functions like ob::register
 * @version 0.3 added extending to SSC, moved register
 *              functions to common.php
 * @version 0.4 added $load variable for loader support
 *              helpers, functions..
 */
 
Class ob extends SSC
{
    /**
    * Load files just from
    * helpers..
    * 
    * @var object
    */
    public $load;
    
    /**
    * Obullo instance
    * 
    * @var object
    */
    private static $instance;

    /**
    * Construct func.
    * @return void
    */
    public function __construct()
    {   
        self::$instance = $this;
        
        $this->load = $this;
        
        parent::__construct();
    }

    /**
    * ob::instance();
    * 
    * Get the Obullo Super Object Every Where
    *  
    * @author Ersin Güvenç
    * @version 1.0 
    * @version 1.1 get_instance renamed and moved into here
    * @return object
    */
    public static function instance()
    {       
       return self::$instance;
    } 
          
    /**
    * ob::dbconnect();
    * Factory and Connect to database driver
    * Get pdo database handle
    * 
    * @author Ersin Güvenç
    * @version 1.0
    * @version 1.1 moved into ob class
    * @return object - pdo database handle
    */
    public static function dbconnect()
    {
        return OB_DBFactory::Connect();
    }
    
    /**
    * Get current directory
    * 
    * @return string
    */
    public static function directory()
    {
        return $GLOBALS['d'];
    }
    
    /**
    * Get current controller
    * 
    * @return string
    */
    public static function controller()
    {
        return $GLOBALS['c'];
    }
    
    /**
    * Get current method
    * 
    * @return string
    */
    public static function method()
    {
        return $GLOBALS['m'];
    }
    
    /**
    * Path of the current controller
    * 
    * @return string
    */
    public static function path()
    {
        return CONTROLLER.$GLOBALS['d'].DS.'controllers'.DS;
    }
    
} // end class.


?>
