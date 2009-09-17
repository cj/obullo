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

###############################
# @ OBULLO CORE               #
###############################
 
Class OBException extends CommonException {}

/**
 * Obullo Super Static Controller (SSC)
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
    
    // test function
    public function input_set($key,$val)
    {   
        return $this->input->set($key,$val);
    }
    
    public function input_get($key)
    {   
        return $this->input->get($key); 
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
    * ob::get_config();
    * 
    * Get main configuration data
    * 
    * @author Code Igniter
    * @author Ersin Güvenç
    * @return array
    */
    static function get_config()
    {
        static $main_conf;

        if ( ! isset($main_conf))
        {
            if ( ! file_exists(APP.'config/config'.EXT))
            throw new OBException('The configuration file config'.EXT.' does not exist.');
            
            require(APP.'config/config'.EXT);

            if ( ! isset($config) OR ! is_array($config))
            throw new OBException('Your config file does not appear to be formatted correctly.');

            $main_conf[0] =& $config;
        }
        
        return $main_conf[0];
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
    static function register($class,$static_or_params = NULL)
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
            if(is_array($static_or_params))
            {
                $registry->storeObject($Class, new $classname($static_or_params));
                
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
    * @version 0.1
    * @return TRUE | NULL
    */
    static function register_static($real_name)
    {   
        if(class_exists($real_name))
        return TRUE;
        
        $Class = strtolower($real_name); //lowercase classname.
        
        if(file_exists(APP.'libraries'.DIRECTORY_SEPARATOR.$Class.EXT))
        {
            require(APP.'libraries'.DIRECTORY_SEPARATOR.$Class.EXT);
            
            return TRUE;
        }
        
        return NULL;  // if register func return to null 
                      // we will show a loader exception inside from
                      // which file will use ob::register_static func.
    } 
       
    /**
    * ob::register_base_static();
    * Register base library static class
    * if class base library static load class
    * and return true
    * 
    * @access public
    * @author Ersin Güvenç
    * @param string $class library class name.
    * @version 0.1
    * @return TRUE | NULL
    */
    static function register_base_static($class)
    {
        $Class = strtolower($class); //lowercase classname.
        
        // Get real class name
        $statics = ob::get_static_classes();
        
        if(isset($statics[$Class]))
        {        
            // if class base static
            if(class_exists($statics[$Class]))
            return TRUE;
            
            if(file_exists(BASE.'libraries'.DIRECTORY_SEPARATOR.ucfirst($Class).EXT))
            {
                require(BASE.'libraries'.DIRECTORY_SEPARATOR.ucfirst($Class).EXT);
                
                return TRUE;
            }
        
            throw new LoaderException('Unable to locate the static library file: '.$Class);
        }
        
        return NULL;
        
    } 
       
    /**
    * Get base library static classes
    * 
    * @author Ersin Güvenç
    * @author you..
    * @version 0.1
    * @return array
    */
    static function get_static_classes()
    {   
        return array('none'=>'none');
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
    static function input_post($key,$const = NULL){}
   
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
    static function input_ip($key,$const = NULL)
    {
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
    public function user_ip(){
        
        echo $this->input->ip();
        //$input = self::register('input');
    }
    static function user_agent(){} 
    
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

 /**
 * Obullo Controller
 * 
 * @package         Obullo 
 * @subpackage      Base.libraries     
 * @category        Libraries
 * @version         1.0
 * @version         1.1 renamed Register as ob::register
 */   
 
Class Controller extends ob
{
    
    function __construct()       
    {   
        $this->ob_init();
        
        parent::__construct();

    }

    function ob_init()
    {
      /**
      *  @deprecated
      *  we assign the load variable to main loader class.
      *  we will use loader like this 
      *  $this->load->library('file') //load class from library dir.
      *  $this->load->helper('file')  //load helper file from helper dir. 
      */                  
      
      /**
      *  we can use loader as static like this loader::library();
      *  since version 1.0 @alpha             
      */
      
      /** @deprecated */
      // $this->load =& Register('Loader');
      
      // load internal system classes...
      // WE CAN USE THIS CLASSES in controller like  $this->input->post();
      // without use load word.
      
      
      // Load Automatically None Static Classes.
      
      
        $Classes = array(
                            'input'     => 'Input',
                            );
        
        foreach ($Classes as $public_var => $Class)
        {
            $this->$public_var = ob::register($Class);
            
            //ob::register_static($Class);
        }
      
      
      // from now on we can none static classes like this
      // like this $this->class->function(); 
    
    } //end function.

         
} //end class.
  
  
?>
