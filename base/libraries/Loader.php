<?php
if( !defined('BASE') ) exit('Access Denied!');

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
 * Loader Class (Obullo Loader Pattern)
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
 */


 
Class LoaderException extends CommonException {}

require(APP.'extends'.DIRECTORY_SEPARATOR.'Ob_user'.EXT);

Class loader extends ob_user {

    /**
    * Obullo Models
    * Store all loaded Obullo Models
    * 
    * @var array
    */
    public $om = array();
    
    /**
    * Store all models
    * Which model use database
    * 
    * @var mixed
    */
    public $db_models = array(); 
    
    
    // Allow user access to SSC Pattern.
    function __construct()
    {   
        //parent::__construct();
    }
    
    // disable clone
    //private function __clone(){}
    
    /**
    * loader::library();
    * 
    * Load user or system classes
    * from application/libraries or base/libraries
    * directories.
    * 
    * @author Ersin Güvenç
    * @author you..
    * @param string $class class name
    * @param mixed $static instantiate switch load class not declare it 
    *                      Booelan or Array (provide __CONSTRUCT params)
    * @version 0.1
    * @version 0.2  added register_static functions
    *               added OB_Library
    * @return void
    */
    public static function library($class, $static_or_params = NULL)
    {
        if ($class == '')
        return FALSE;
        
        if($static_or_params === TRUE)
        {   
            // if someone want to load static class
            // like Myclass::mymethod, include it.
            ob::register_static($class);
            return;
        }
        /*------------- user static end ----------------*/
        
        // Check if class static or not.
        $class = strtolower($class);
        if(ob::register_base_static($class))
        return;
        
        /*------------- base static end ----------------*/
        
        // Instantiate the Super Object.        
        $OB = ob::instance();
        
        // Lazy Loading
        if (class_exists($class) AND isset($OB->$class) AND is_object($OB->$class))
        return FALSE;  
        
        $OB->$class = ob::register($class,$static_or_params);
        
        if($OB->$class === NULL)
        throw new LoaderException('Unable to locate the library file: '.$class);
    
        // from now on we can use our class
        // like $this->Myclass->myMethod 
        
        // assign all libraries to all models
        // support for loader::libray() func. inside from
        // public model functions
        OB_Library::asn_to_models(); 
        
    }
    
    /**
    * loader::view();
    * 
    * Load view files
    * Don't declare this func as static
    * because of ability to use $this 
    * 
    * @param string $view filename
    * @param array $data view data
    * @param boolean $string fetch as string
    * @version 0.1
    * @return void
    */
    public function view($view, $data = array(), $string = FALSE)
    {            
        $file = VIEW . DIRECTORY_SEPARATOR . $view . EXT;
        
        if(sizeof($data) > 0)
        extract($data, EXTR_SKIP);

        if (file_exists($file)) {

                if($string) {

                //get file as a srtring.
                ob_start();

                include($file);

                $content = ob_get_contents();
                ob_end_clean();

                return $content;

                } else {

                // just include file.
                include($file);
                }


        } else {
            
            throw new LoaderException('Unable to locate the view: '.$file);
        }

    }
    
    /**
    * loader::model();
    * Obullo Model Pattern (c)
    * 
    * @author Ersin Güvenç
    * @author you..
    * @copyright obullo.com
    * @param string $model
    * @version 0.1
    * @return void
    */
    public static function model($model)
    {        
        if ($model == '')
        return;
        
        $model_name = strtolower($model);
        
        // Controller directory support for model
        // if user provide path separator like this  loader::model(blog/model_blog)
        
        if (strpos($model_name, '/') == TRUE)
        {
            $paths = explode('/',$model_name); // path[0] = controller name
            $model_name = array_pop($paths);
            $path = implode('/',$paths).'/';
            
            // if requested path in same controller
            if($path[0] == $GLOBALS['controller'])
            {
                // Load user called current path like current_controller/model_test
                $MODEL_PATH = MODEL.$path.$model_name.EXT;  
            
            // if requested path from another controller
            } else {
                
                // Load user called another_controller/model_test
                $MODEL_PATH = CONTROLLER_PATH.$path.$model_name.EXT;
            }

             
        } else {
                // Load current controller model
                $MODEL_PATH = MODEL.$model_name.EXT;
        }
        
        
        if ( ! file_exists($MODEL_PATH))
        throw new LoaderException('Unable to locate the model: '.$model_name);
        
        $OB = ob::instance();
        
        if (isset($OB->$model_name))
        throw new LoaderException('This model already loaded before: '.$model_name);

        require($MODEL_PATH);
        $model = ucfirst($model_name);
                
        $OB->$model_name = new $model();    //Register($class); we don't need it
        
        if(isset($OB->db_models[$model])) //if($OB->mod_DB)
        {
            // Lazy Loading. (Prevent to Loading Db Class and Prevent Connect to Db more than one time)
            if (class_exists('DB') AND isset($OB->db) AND is_object($OB->db)) {
            
                $OB->$model_name->db = $OB->db;
              
            } else
            {
                require(BASE.'database/DB'.EXT);
                require(BASE.'database/DBFactory'.EXT);    
                
                // Connect to PDO.
                // Good work.
                // we must assign db object for each model
                // $OB->db = OB_DBFactory::Connect();
                $OB->$model_name->db = ob::dbconnect();  

                echo 'DB class initalized one time!'; 
            }     
        
        } else {
            
            // if model hasn't got loader::database() element remove db object.
            $OB->$model_name->db = NULL;    
        }
        
        // Reset db switch arrays loading db for other models.
        /** @deprecated for declaring two one model inside another model with db support */
        //$OB->db_models = array();

        // assign all loaded libraries inside to current model
        // loader::library() support for Model_x { function __construct() { loader::library() }}
        $OB->$model_name->_asn_lib();
        
        // store loaded obullo models
        $OB->om[] = $model_name;
        
    }
    
    /**
    * loader::database();
    * 
    * Obullo database load pattern (c)
    * This function just load database class
    * When you use it inside from model like this loader::database();
    * func store all models which use db and it tells to loader class 
    * this model use the database func.
    * 
    * @author Ersin Güvenç
    * @author you..
    * @copyright obullo.com
    * @version 0.1
    * @version 0.2 multiple models load::database func. support
    *              loading model inside again model bug fixed.
    *              added db_models[] array.
    * @return void
    */
    public static function database()
    {
        $OB = ob::instance();
        
        // For php 5.3.0 and upper versions maybe 
        // we can use get_called_class() func.
        $trace = debug_backtrace();

        // is model contains loader::database() func
        // and is it instance of Model ?

        foreach($trace as $t)
        {
           if(isset($t['object']))
           { 
             if($t['object'] instanceof Model)        
             $OB->db_models[$t['class']] = $t['class'];
           } 
        }
        
        unset($trace); // reset var, prevent memory consumption
        
        if (class_exists('DB') AND isset($OB->db) AND is_object($OB->db))
        return FALSE;  
        
        require(BASE.'database/DB'.EXT);
        require(BASE.'database/DBFactory'.EXT);    
        
        // Extends to PDO.
        $OB->db = ob::dbconnect();
        
        // echo 'DB class initalized one time!';
    }        

    /**
    * loader::helper();
    * 
    * We have three helper directory unlike CI
    *   o Base/helpers  : system helpers
    *   o App/helpers   : common application helpers
    *   o Controllers/helper_$controller : controller helpers
    * 
    * @author Ersin Güvenç
    * @author you..
    * @param string $helper
    * @version 0.1
    * @return void
    */
    public static function helper($helper)
    { 
        // if user provide path separator like this loader::helper(blog/helper_blog)
        if (strpos($helper, '/') == TRUE)
        {
            $paths = explode('/',$helper); // path[0] = controller name
            $helper_name = array_pop($paths);
            $path = implode('/',$paths).'/';
            
            $helper_name = strtolower('helper_'.str_replace('helper_', '', $helper_name)).EXT;
            
            if(file_exists(CONTROLLER_PATH.$path.$helper_name))
            {
                include(CONTROLLER_PATH.$path.$helper_name);
    
            } else
            {
                throw new LoaderException('Unable to locate the helper: '.$helper_name);
            }
            return;
        }
        
        $helper = strtolower('helper_'.str_replace('helper_', '', $helper)).EXT;
        
        // Check system helper
        if(file_exists(BASE.'helpers/'.$helper)) 
        {
            include(BASE.'helpers/'.$helper);
        // Check application helper
        } elseif(file_exists(APP.'helpers/'.$helper))
        {
            include(APP.'helpers/'.$helper);
        // Check controller helper    
        } elseif(file_exists(CONTROLLER.$helper))
        {
            include(CONTROLLER.$helper);
        // If file not exists 
        } else
        {
            throw new LoaderException('Unable to locate the helper: '.$helper);
        }

    }   
    
    /**
    * loader::dir();
    * Show directory list of current controller
    * 
    * @author Ersin Güvenç
    * @version 0.1
    * @return booelan
    */
    public static function dir()
    {
        if(is_readable(CONTROLLER))
        {
            // opendir function
            $handle = opendir(CONTROLLER);
            echo '<br />Directory Listing of <b>'.CONTROLLER.'</b><br/>';

            // running the while loop
            while ($file = readdir($handle)) 
            echo $file.'<br/>';

            // close dir
            closedir($handle);
            
            return TRUE;              
        } 
        
        throw new LoaderException($GLOBALS['controller']. ' directory is not readable! ');
    }
    
    /**
    * Load directly PEAR libraries.
    *    
    * @param string e.g. 'Class', 'Mail/mime', 'Spreadsheet/Excel/Writer'
    * @author Ersin Güvenç
    * @author you..
    * @version 0.1
    * @return boolean
    */
    public static function pear($class)
    {
        if(file_exists($class))
        {
            require($class.EXT);
            
            return TRUE;
        }
        
        throw new LoaderException('Unable to locate the pear library:'. $class.EXT);
        
    } //end func.
   
    
} //end class.

          
?>
