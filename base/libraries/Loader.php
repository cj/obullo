<?php                      
if( !defined('BASE') ) exit('Access Denied!');

/* SVN FILE: $Id: Loader.php $Rev: 37 24-10-2009 01:03 develturk $ */

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 Minimalist software for PHP 5.2.4 or newer
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.libraries        
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
 * @license         http://www.opensource.org/licenses/gpl-3.0.html GPL  
 * @filesource
 */ 
 
/**
 * Loader Class (Obullo Loader Pattern) (c) 2009
 *
 * Load obullo library,model,view.. files
 *
 * @package         Obullo 
 * @subpackage      Base.libraries     
 * @category        Loader
 * @version         0.1
 * @version         0.2 added model and load db function
 * @version         0.3 added static properties for database(),model(),library()
 *                      added load_DB variable (Model database load on/off).
 *                      added asn_to_models(),helper(),dir()
 * @version         0.4 renamed static functions ob::instance(),ob::register()..
 *                  added static param to library func.Added __construct support
 *                  to library.
 * @version         0.5 changed directory structure added $GLOBALS['d'] (directory)
 * @version         0.6 loader::database() and libraries _asn_lib() instanceof problem fixed.
 * @version         0.7 added base(), _library(), base_helper(), base_view() functions. 
 * @version         0.8 added js(), base_js(), script(), base_script() functions.Removed dir() function.
 * @version         0.9 added __autoloader() functionality
 */


 
Class LoaderException extends CommonException {}

require(APP.'extends'.DS.'User'.EXT);
                
Class loader extends user {  

    /**
    * Obullo Models
    * Store all loaded Models
    * 
    * @var array
    */
    public $mods = array();
    
    /**
    * Obullo Libraries
    * Store all loaded Libraries
    * 
    * @var array
    */
    public $libs = array();
    
    
    // Allow user access to SSC Pattern.
    function __construct(){}
    
    /**
    * loader::library();
    * 
    * load app libraries from /app folder.
    * 
    * @param    mixed $class
    * @param    mixed $static_or_params
    * @return   self::_library()
    */
    public function library($class, $static_or_params = NULL)
    {          
        if(is_array($class))
        {
            foreach($class as $c)
            $this->_library($c, NULL);
        }
         else
        {
            $this->_library($class, $static_or_params); 
        }    
    }
    
    /**
    * loader::base();
    * 
    * load base libraries from /base folder.
    * 
    * @param    mixed $class
    * @param    mixed $static_or_params
    * @return   self::_library()
    */
    public function base($class, $static_or_params = NULL)
    {             
        if(is_array($class))
        {
            foreach($class as $c)
            $this->_library($c, NULL,TRUE);
        } 
         else 
        {
            $this->_library($class, $static_or_params, TRUE); 
        }
    }
    
    /**
    * Obullo Library Pattern (c) 2009
    * 
    * Load user or system classes
    * from application/libraries or base/libraries
    * directories.
    * 
    * @author   Ersin Güvenç
    * @author   you..
    * @param    string $class class name
    * @param    mixed $static instantiate switch load class not declare it 
    *                      Booelan or Array (provide __CONSTRUCT params)
    * @version  0.1
    * @version  0.2  added register_static functions
    * @return   void
    */
    private function _library($class, $static_or_params = NULL, $base = FALSE)
    {
        if ($class == '')
        return FALSE;
        
        if($static_or_params === TRUE)
        {   
            // if someone want to load static class
            // like Myclass::mymethod, include it.
            register_static($class);
            return;
        }

        // Instantiate the Super Object.        
        $OB = ob::instance();
        
        // Lazy Loading
        if (class_exists($class) AND isset($OB->$class) AND is_object($OB->$class))
        return FALSE;  
        
        switch ($base)
        {
           case FALSE:
             $OB->$class = register($class, $static_or_params); 
             break;
             
           case TRUE:
             $OB->$class = base_register($class, $static_or_params); 
             break;
        }
        
        if($OB->$class === NULL)
        throw new LoaderException('Unable to locate the library file: '.$class);
    
        // assign all libraries to all models
        // support for loader::libray() func. inside from
        // public model functions
        self::asn_to_models();
    
        if($base) { $OB->libs[] = $class; return; }

        //----- below the operations for user libraries -----//
        
        if($OB->$class instanceof Library)  // if user want to use 'extends Library' way 
        $OB->$class->_asn_lib();   // __construct load db support.
        
    
        if($OB->$class instanceof Library)  // if user want to use 'extends Library' way  
        self::asn_to_libraries();  // assign all User libraries to all libraries
                                   // base libraries already assigned because of Library
                                   // extends to ob class..
        
        $OB->libs[] = $class;
        
        //return $OB; // $ob = loader::library('class');
    }
    
    /**
    * loader::view();
    * 
    * Load view files
    * Don't declare this func as static
    * because of ability to use $this
    *  
    * @author   Ersin Güvenç
    * @author   you..
    * @param    string $filename filename
    * @param    array $data view data
    * @param    boolean $string fetch as string
    * @version  0.1
    * @version  0.2 Changed $GLOBALS['c'] as $GLOBALS['d']
    * @return   self::_view()
    */
    public function view($filename, $data = array(), $string = FALSE)
    {            
        $path = VIEW . $GLOBALS['d']. DS . 'views' . DS;
                     
        return $this->_view($path, $filename, $data, $string);
    }
    
    /**
    * loader::base_view();
    * 
    * load top html page for every views 
    * Base view file comes from 
    * base/views directory
    * 
    * @param    string $filename                   
    * @param    array $data
    * @param    boolean $string
    * @return   self::_view()
    */
    public function base_view($filename, $data = array(), $string = FALSE)
    {
        $path = BASE . 'views'. DS;
        
        return $this->_view($path, $filename, $data, $string); 
    }
    
    /**
    * Main view function
    * 
    * @author   Ersin Güvenç
    * @author   you..            
    * @param    string $path file path 
    * @param    string $filename 
    * @param    array $data template vars
    * @param    boolen $string 
    * @version  0.1
    * @version  0.2 added $this->data container
    * @return   void
    */
    private function _view($path, $filename, $data = array(), $string = FALSE)
    {
        $file = $path . $filename . EXT;
        
        if(sizeof($data) > 0) { extract($data, EXTR_SKIP); }
        if(sizeof($this->data) > 0) { extract($this->data, EXTR_SKIP); } // user hidden data
        
        if (file_exists($file))
        {
            if($string)
            {
                //get file as a srtring.
                ob_start();

                include($file);

                $content = ob_get_contents();
                ob_end_clean();

                return $content;
            } 
            
            // just include file.
            include($file);
            return;
        } 
        
        throw new LoaderException('Unable to locate the view: '.$filename);
    }
   
       
    /**
    * loader::model();
    * Obullo Model Pattern
    * 
    * @author       Ersin Güvenç
    * @author       you..
    * @copyright    obullo.com
    * @param        string $model
    * @version      0.1
    * @version      0.2 added directory support
    * @version      0.3 changed $GLOBALS['c'] as $GLOBALS['d']
    * @version      0.4 removed old current path support added
    *                   new model directory structure support 
    * @version      0.5 added multiple load support
    * @return       void
    */
    public function model($models = array())
    {        
        if ( ! is_array($models))
        $models = array($models);
        
        foreach($models as $model)
        { 
            $model_name = strtolower($model);
            
            // Controller directory support for model
            // if user provide path separator like this  loader::model(blog/model_blog)
            if (strpos($model_name, '/') == TRUE)
            {
                $paths = explode('/',$model_name); // path[0] = controller name
                $model_name = array_pop($paths);
                $path = implode('/',$paths).'/';
                
                // Load user called another_controller/model_test
                $MODEL_PATH = CONTROLLER.$path.$model_name.EXT;
            } 
            else
            {
                // Load current controller model
                $MODEL_PATH = MODEL.$GLOBALS['d'].DS.'models'.DS.$model_name.EXT;
            }
            
            if ( ! file_exists($MODEL_PATH))
            throw new LoaderException('Unable to locate the model: '.$model_name);
            
            $OB = ob::instance();  
            
            if (isset($OB->$model_name) AND is_object($OB->$model_name))
            throw new LoaderException('This model already loaded before: '.$model_name);
            
            require($MODEL_PATH);
            $model = ucfirst($model_name);   

            if( ! class_exists($model_name))
            throw new LoaderException('Model name is not correct in file: '.$model_name);
            
            $OB->$model_name = new $model();    //register($class); we don't need it   

            // assign all loaded libraries inside to current model
            // loader::library() support for Model_x { function __construct() { loader::library() }}
            $OB->$model_name->_asn_lib();
            
            // store loaded obullo models
            $OB->mods[] = $model_name;
        
        } // end foreach
          
    }
    
    /**
    * loader::database();
    * 
    * Database load.
    * This function just loads the database for current
    * Controller, not models, libraries etc.. 
    * 
    * @author   Ersin Güvenç
    * @author   you..
    * @version  0.1
    * @version  0.2 multiple models load::database function support.
    *               Loading model inside again model bug fixed.
    * @version  0.3 Deprecated debug_backtrace(); function
    *               added asn_to_libraries();
    * @return   void
    */
    public static function database()
    {
        $OB = ob::instance();
        
        if (class_exists('DB') AND isset($OB->db) AND is_object($OB->db))
        return FALSE;  
        
        require(BASE.'database'.DS.'DB'.EXT);
        require(BASE.'database'.DS.'DBFactory'.EXT);    
        
        // Extends to PDO.
        $OB->db = ob::dbconnect();

        // assign db object to all models
        self::asn_to_models(); 
        
        // assign db object to all libraries
        self::asn_to_libraries();    // function load db support.
    }        

    /**
    * loader::helper();
    * 
    * We have three helper directory unlike CI
    *   o Base/helpers  : system helpers
    *   o App/helpers   : common application helpers
    *   o Controllers/directory/helpers: controller helpers
    * 
    * @author   Ersin Güvenç
    * @author   you..
    * @param    string $helper
    * @version  0.1
    * @version  0.2 changed $GLOBALS['c'] as $GLOBALS['d']
    * @version  0.3 changed base helper functionality as base_helper()
    * @version  0.4 added multiple helper load functionality
    * @return   void
    */
    public static function helper($helpers = array())
    { 
        if ( ! is_array($helpers))
        $helpers = array($helpers);
        
        foreach($helpers as $helper)
        {
            // if user provide path separator like this loader::helper(blog/helper_blog)
            if (strpos($helper, '/') == TRUE)
            {
                $paths = explode('/',$helper);  // path[0] = controller name
                $helper_name = array_pop($paths);
                $path = implode('/',$paths).'/';
                
                $helper_name = strtolower(str_replace('_helper', '', $helper_name).'_helper').EXT;
                
                if(file_exists(CONTROLLER.$path.$helper_name))
                {
                    include(CONTROLLER.$path.$helper_name);
                } 
                else 
                {
                    throw new LoaderException('Unable to locate the user helper: '.$helper_name);   
                } 
            }
            
            $helper = strtolower(str_replace('_helper', '', $helper).'_helper').EXT;
            
            if(file_exists(APP.'helpers'.DS.$helper))
            {
                include(APP.'helpers'.DS.$helper);
       
            } elseif(file_exists(CONTROLLER.$GLOBALS['d'].DS.'helpers'.DS.$helper))
            {
                include(CONTROLLER.$GLOBALS['d'].DS.'helpers'.DS.$helper);
            
            } else 
            { 
                throw new LoaderException('Unable to locate the application helper: '.$helper); 
            }
        
        } // end foreach.
        
    }   
    
    /**
    * loader::base_helper();
    * 
    * load helper from /base directory
    * @author   Ersin Güvenç
    * @author   you..
    * @version  0.1
    * @version  0.2 added multiple load support
    * @param    mixed $helpers
    * @return   void
    */
    public static function base_helper($helpers = array())
    {
        if ( ! is_array($helpers))
        $helpers = array($helpers);
        
        foreach($helpers as $helper)
        {
            $helper = strtolower(str_replace('_helper', '', $helper).'_helper').EXT;
            
            if(file_exists(BASE.'helpers'.DS.$helper)) 
            {                  
                include(BASE.'helpers'.DS.$helper);
            } 
            else 
            {
                throw new LoaderException('Unable to locate the base helper: '.$helper);
            }
        }
        
    }
            
    /**
    * Loads a language file
    *
    * @access   public
    * @param    array
    * @param    string
    * @return   void
    */
    public function language($file = array(), $lang = '')
    {
        if ( ! is_array($file))
        $file = array($file);

        foreach ($file as $langfile)
        $this->lang->load($langfile, $lang);
    }
    
    /**
    * Loads a config file
    *
    * @access   public
    * @param    array
    * @return   void
    */                                 
    public function config($file = array())    
    {
        if( ! is_array($file))
        $file = array($file);
        
        foreach ($file as $configfile)
        $this->config->load($configfile);
    }
                          
    /**
    * Load directly PEAR libraries.
    * 
    * @author   Ersin Güvenç
    * @author   you..    
    * @param    string e.g. 'Class', 'Mail/mime', 'Spreadsheet/Excel/Writer'
    * @version  0.1
    * @return   boolean
    */
    public static function pear($class)
    {
        if(file_exists($class))
        {
            require($class.EXT);
            return;
        }
        
        throw new LoaderException('Unable to locate the pear library:'. $class.EXT);
        
    } //end func.
    
    
    /**
    * loader::js();
    * 
    * load application .js files
    * 
    * @param    string $filename
    * @param    string $arguments
    */
    public function js($filename, $arguments = '')
    {
        $path = 'application/controllers/'. $GLOBALS['d'] . '/views/js/';
         
        return $this->_js($path, $filename, $arguments);
    }
    
    /**
    * loader::base_js();
    * 
    * load base .js files
    * 
    * @param    string $filename
    * @param    string $arguments
    */
    public function base_js($filename, $arguments = '')
    {
        $path = 'base/views/js/';
        
        return $this->_js($path, $filename, $arguments);
    }
    
    /**
    * Build js files in <head> tags
    * 
    * @author   Ersin Güvenç
    * @param    string $path
    * @param    string $filename
    * @param    string $arguments add argument ?arg=1&arg2=2
    * @return   string
    */
    private function _js($path, $filename, $arguments = '')
    {
         $src  = $this->config->item('base_url'). $path . $filename . '.js' . $arguments;
         $file = $path . $filename . '.js';
         
         if(file_exists($file))
         return "\n".'<script type="text/javascript" src="'.$src.'"></script>'."\n";  
         
         throw new LoaderException('Unable locate the js file: '. $filename); 
    }
    
    /**
    * loader::base_script();
    * 
    * load base java script files
    * 
    * @param    string $filename
    * @param    array $data
    */
    public function script($filename, $data = array())
    {
        $path = CONTROLLER . $GLOBALS['d'] . DS . 'views' . DS . 'scripts' . DS;
         
        return $this->_script($path, $filename, $data);
    }
    
    /**
    * loader::base_script();
    * 
    * load base java script files
    * 
    * @param    string $filename
    * @param    array $data
    */
    public function base_script($filename, $data = array())
    {
        $path = BASE . 'views' . DS . 'scripts' . DS;
        
        return $this->_script($path, $filename, $data);
    }
    
    /**                     
    * Load Java script files externally
    * 
    * @author   Ersin Güvenç
    * @param    string $script_file
    * @param    array $data
    */
    private function _script($path, $filename, $data = array())
    {  
        $filename = strtolower(str_replace('_script', '', $filename).'_script');
        $file = $path . $filename . $this->config->item('script_view_extension');
         
        if(sizeof($data) > 0) { extract($data, EXTR_SKIP); }
        
        if (file_exists($file))
        {  
             //get file as a string.
             ob_start();

             include($file);

             $content = ob_get_contents();
             ob_end_clean();

             return "\n".$content."\n";
        }

        throw new LoaderException('Unable locate the script file: '. $filename);
        
    }
    
    // @ Support for loader::libray() inside from public model functions 
    // If you declare a library like this loader::library(); 
    // from model __construct() function, it works this is ok
    // because loader::model() function already loads it via $OB->$model_name->_asn_lib();
    // but when u declare it inside a model function it will not work
    // so you will get an error: Undefined property: Model_test::$myclass
    // This function fix the problem, assigns all library files to model. (Ersin) 

    private static function asn_to_models()
    {
        $OB = ob::instance();
        
        if (count($OB->mods) == 0)
        return;
        
        foreach ($OB->mods as $model_name)
        $OB->$model_name->_asn_lib();
    }
    
    
    // assign all variables to library class 
    private static function asn_to_libraries()
    {
        $OB = ob::instance();
        
        if (count($OB->libs) == 0)
        return;
        
        foreach ($OB->libs as $lib_name)
        {
            if($OB->$lib_name instanceof Library) 
            $OB->$lib_name->_asn_lib(); 
        }
    }
   
    
} //end class.

          
?>
