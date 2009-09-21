<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC-min Framework software for PHP 5.2.4 or newer
 * Derived from Code Igniter
 *
 * @package         obullo
 * @filename        base/libraries/Controller.php        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */
  
/**
 * Obullo Super Static Controller (SSC) (c) 2009
 * We put all static main codes here
 * Like cookie , session, register
 * Goal of the SSC to get all static functions
 * like this ob::instance(), ob::session_set(), 
 * ob::cookie_get(),ob::dbconnect() you can 
 * also use $this var inside form static
 * functions.
 * 
 * @version 0.1
 * @version 0.2 added core functions like ob::register
 */
 
Class OBException extends CommonException {}  
 
Class ob extends loader
{
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
        
        parent::__construct();
    }

    /**
    * ob::instance();
    * 
    * Get the Obullo Super Object Every Where
    *  
    * @author Ersin Güvenç
    * @version 1.0 
    * @version 1.1 getInstance renamed and moved into ob class
    * @return object
    */
    static function instance()
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
    static function dbconnect()
    {
        return OB_DBFactory::Connect();
    }

    /**
    * ob::register();
    * Registry Controller Function
    * 
    * @access public
    * @author Ersin Güvenç
    * @param string $class name of the class.
    * @version 0.1
    * @version 0.2 added $file_exists var
    * @version 0.3 moved into ob class
    * @version 0.4 added __construct(params=array()) support
    * @return object | NULL
    */
    static function register($class, $params = NULL)
    {
        $registry = OB_Registry::singleton();
        
        $Class = strtolower($class); //lowercase classname.
    
        $getObject = $registry->getObject($Class);
        
        // if class already stored we are done.
        
        if ($getObject !== NULL)
        return $getObject;
        
        $file_exists = OB_Library::factory($class);
        
        if($file_exists)
        {
            $classname = ucfirst($class);
            
            if (class_exists('OB_'.$class))
            $classname = 'OB_'.$classname;
            
            // construct support.
            if(is_array($params))
            {
                $registry->storeObject($Class, new $classname($params));
                
            } else 
            {
                $registry->storeObject($Class, new $classname());
            }
             
            //return singleton object.
            $Object = $registry->getObject($Class);

            if(is_object($Object))
            return $Object;
        }
        
        return NULL;  // if register func return to null 
                      // we will show a loader exception inside from
                      // which file will use ob::register func.
    
    } // end func.
    
    /**
    * ob::register_static();
    * User register static class func.
    * use class like Myclass::method
    * 
    * @access public
    * @author Ersin Güvenç
    * @param string $class name of the class.
    *               You must provide real class name.
    * @param boolean $base base class or not
    * @version 0.1
    * @version 0.2 added base param
    * @return TRUE | NULL
    */
    static function register_static($real_name, $base = FALSE)
    {   
        if(class_exists($real_name))
        return TRUE;
    
        $Class = strtolower($real_name); //lowercase classname.
        $Path  = APP;
        
        if($base)
        {
            $Class = ucfirst($Class); //lowercase classname.
            $Path  = BASE; 
        }
        
        if(file_exists($Path.'libraries'.DIRECTORY_SEPARATOR.$Class.EXT))
        {
            require($Path.'libraries'.DIRECTORY_SEPARATOR.$Class.EXT);
            
            return TRUE;
        }
        
        return NULL;  // if register func return to null 
                      // we will show a loader exception inside from
                      // which file will use ob::register_static func.
    } 
   
    ###  input class  static otomtik olarak yüklenecek
    ### load::library()
    /**
    * ob::input_post();
    * 
    * @author Ersin Güvenç
    * @param mixed $key form field
    * @param mixed $const p_int,p_bool,p_string
    * @version 1.0
    * @return void
    */
    public function input_post($key,$const = NULL){}
   
    // test function
    
    // ob::input_get();
    //static function input_get($key,$const = NULL){}
    
    // identical CI $this->input->get_post
    static function input_both($key,$bool){}
    
    // identical CI $this->input->get_post
    static function input_xss(){}
    
    /**
    * ob::input_ip();
    * Validate ip address
    * @param mixed $key ip addres
    * @version 1.0
    * @return void
    */
    public function input_ip()
    {
        return $this->input->ip();
        //$input = ob::register('input');
        // bu yeterli değil functionu class içinden static yazmalısın
    }
    
    static function input_server($key)
    {
        //$input = ob::register('input');
    }
    
    // browser
    // ob::input_ip();
    // Returns the IP address for the current user
    // alias of CI $this->input->ip_address
    /*
    public function user_ip(){
        
        echo $this->input->ip();
        //$input = self::register('input');
    }
    */
    public function user_agent(){echo 'ok';} 
    
    ### Session class ı static olarak kullanıcı yüklüyor
    ### test controller için loader::library('class','static')
    ### loader library içine static classları tanımla.
    // sessions
    static function session_set($key,$array = NULL)
    {
        // kullanıcı ilan edicek 
        // OB_Session::session_set sadece bunu yaz.
        
    }
    
    static function session_set_userdata($array = NULL){}
    
    static function session_userdata($array = NULL){}
    
    static function session_flashdata($array = NULL){}
    
    // cookies..
    static function cookie_get(){}
    
    static function cookie_set(){}
    
    // config class     //otomatik yükle
    static function config_load()
    {
        // load function here...
    }
    
    static function config_item()
    {    
    }
    
    // get current database settings
    static function config_db_item(){}
    
    static function config_set(){}
    // $this->config->site_url();
    static function config_url(){}
    static function config_baseurl(){}
    // $this->config->system_url();
    static function config_system(){}
    
    // language class
    // otomatik yada kullanıcı tarafından yukleniyor.
    // load library ile
    static function lang_item(){}

    
    // redirect function
    static function redirect(){}
    
    
    
    // static function uri_segment(){}
    
    // url functions
    // ob::href(''); identical CI anchor();
    //static function href(){} url helper bu 
    
    
} // end class.


?>
