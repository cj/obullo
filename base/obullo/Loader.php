<?php                      
defined('BASE') or exit('Access Denied!');  

/**
 * Obullo Framework (c) 2009 - 2010.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         Obullo  
 * @author          Obullo.com  
 * @subpackage      Base.obullo        
 * @copyright       Copyright (c) 2009 Ersin Guvenc.
 * @license          
 * @filesource
 */ 
 
/**
 * Loader Class (Obullo Loader) (c) 2009 - 2010
 * Load Obullo library, model, config, lang and any other files ...
 *
 * @package         Obullo 
 * @subpackage      Base.obullo     
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
 * @version         0.9 added __autoloader() functionality, moved __autoloader to User.php
 * @version         1.0 added loader::css(), loader::base_css()
 * @version         1.1 renamed base "libraries" folder as base
 * @version         1.2 added loader::script(, $compress = true) functionality, added views/base_views folder
 * @version         1.3 added js compression, removed Library class functions
 * @version         1.4 removed compress, moved all view functions to Content class.
 * @version         1.5 database function changes, added _assign_db_objects func, removed old funcs.
 * @version         1.6 database function changes, changed DBFactory, added loader::_model object_name var
 *                  and $params support for model files.
 * @version         1.7 added $x_helpers .. private static vars and added self::$_x_helpers static functions.
 * @version         1.8 updated db functions, @deprecated register_static(),
 *                      we use spl_autoload_register() func. because of performance :), added loader::file() func.
 * @version         1.9 added profiler class Ssc::instance()->_profiler_ functions.
 * @version         2.0 loader::model('blog/model_filename'); bug fixed.
 * @version         2.1 added profiler_set(); functions and removed old $ssc->_profiler_ variables, renamed OB_DBFactory::init()
 *                      func as OB_DBFactory::Connect in loader::database();
 * @version         2.2 added  app_model and model('.outside_folder/model_name') and model('subfolder/model_name') support
 *                      added  app_helper and helper('.outside_folder/helper_name') - helper('subfolder/helper_name') support
 */

Class LoaderException extends CommonException {}
                
Class loader {  
   
    /**
    * Prevent Duplication 
    * memory of the "local" helper files.
    * 
    * @var array
    */
    public static $_helpers      = array();
    
    /**
    * Prevent Duplication 
    * memory of the "base" helper files.
    * 
    * @var array
    */
    public static $_base_helpers = array();
    
    /**
    * Prevent Duplication 
    * memory of the "application" helper files.
    * 
    * @var array
    */
    public static $_app_helpers  = array();
    
    /**
    * Prevent Duplication 
    * memory of the "external" files.
    * 
    * @var array
    */
    public static $_files        = array();
    
    /**
    * loader::lib();
    * 
    * load app libraries from /directories folder.
    * 
    * @param    mixed $class
    * @param    mixed $no_ins_params array | false
    * @param    string $object_name
    * @return   self::_library()
    */
    public static function lib($class, $no_ins_params = '', $object_name = '')
    {          
        self::_library($class, $no_ins_params, FALSE, $object_name = '', 'directory'); 
    }
    
    // --------------------------------------------------------------------
    
    /**
    * loader::app_lib();
    * 
    * load app libraries from /application folder.
    * 
    * @param    mixed $class
    * @param    mixed $no_ins_params array | null | false
    * @param    string $object_name
    * @return   self::_library()
    */
    public static function app_lib($class, $no_ins_params = NULL, $object_name = '')
    {          
        self::_library($class, $no_ins_params, FALSE, $object_name = '', 'app');
    }
    
    // --------------------------------------------------------------------
    
    /**
    * loader::base_lib();
    * 
    * load base libraries from /base folder.
    * 
    * @param    mixed $class
    * @param    mixed $no_ins_or_params array | null | false
    * @param    string $object_name
    * @return   self::_library()
    */
    public static function base_lib($class, $no_ins_params = NULL, $object_name = '')
    {             
        self::_library($class, $no_ins_params, TRUE, $object_name); 
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Obullo Library Loader.
    * 
    * Load user or system classes
    * from application/libraries or base/libraries
    * directories.
    * 
    * @author   Ersin Guvenc
    * @param    string $class class name
    * @param    array | boolean $params_or_no_ins __construct() params  | or | No Instantiate 
    *                      
    * @version  0.1
    * @version  0.2  added register_static functions
    * @version  0.3  removed class_exists, removed asn_to_models()
    * @version  0.4  added profiler_set() func.
    * @return   void
    */
    private static function _library($class, $params_or_no_ins = '', $base = FALSE, $object_name = '', $lib_dir = '')
    {
        if($class == '')
        return FALSE;
         
        $OB = Obullo::instance();  // Grab the Super Object. 
        
        $class_var = strtolower($class);
        
        $sub_path  = '';
        if(strpos($class, '/') > 0)         //  inside folder request
        {
            $paths     = explode('/', $class);   // paths[0] = path , [1] file name     
            $class_var = array_pop($paths);      // get file name
            $sub_path  = implode('/', $paths). DS;
        }
        
        if($object_name != '') $class_var = $object_name; 
        
        if (isset($OB->$class_var) AND is_object($OB->$class_var)) { return; }
        
        switch ($base)
        {
           case FALSE:
             $type = ($lib_dir == 'directory') ? 'local' : 'application';
             $OB->$class_var = base_register($class_var, $params_or_no_ins, $lib_dir, $sub_path); 
             break;
             
           case TRUE:
             $type = 'base';
             $OB->$class_var = base_register($class_var, $params_or_no_ins); 
             break;
        }
        
        if($OB->$class_var === NULL)
        throw new LoaderException('Unable to locate the '.$type.' library file: '. $class_var);
    
        profiler_set('libraries', $class_var, $class_var);
    }
        
    // --------------------------------------------------------------------
                                     
    /**
    * loader::app_model();
    * 
    * @author   Ersin Guvenc
    * @param    string $model
    * @param    string $object_name
    * @param    array | boolean $params_or_no_ins (construct params) | or | No Instantiate 
    * @return   void
    */
    public static function app_model($model, $object_name = '', $params_or_no_ins = '')
    {
        self::model($model, $object_name, $params_or_no_ins, 'app_model');
    }
    
    // --------------------------------------------------------------------
    
    /**
    * loader::model();
    * Obullo Model Loader
    * 
    * loader::model('subfolder/model_name')  local sub folder load
    * loader::model('.outside_folder/model_name')  outside directory load
    * 
    * @author    Ersin Guvenc
    * @param     string $model
    * @param     string $object_name 
    * @param     array | boolean $params (construct params) | or | Not Instantiate
    * @param     reserved private parameter for application model func.
    * @version   0.1
    * @version   0.2 added directory support
    * @version   0.3 changed $GLOBALS['c'] as $GLOBALS['d']
    * @version   0.4 removed old current path support added
    *                new model directory structure support 
    * @version   0.5 added multiple load support
    * @version   0.5 added $object_name and $params variables 
    * @version   0.6 changed $params as $params_or_no_ins
    * @version   0.7 loading from another path bug fixed. added 'models' string and DS.
    * @version   0.8 added  model('.outside_folder/model_name') and model('subfolder/model_name') support
    * @return    void
    */
    public static function model($model, $object_name = '', $params_or_no_ins = '', $func = 'model')
    {   
        $data = self::_load_file($model, $folder = 'models', $func);
        
        self::_model($data['file'], $data['file_name'], $object_name, $params_or_no_ins);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Load _model
    * 
    * @access    private
    * @param     string $file
    * @param     string $model_name
    * @version   0.1  
    * @version   0.2 added params_or_no_ins instantiate switch ,added Ssc::instance()->_profiler_mods 
    * @version   0.3 added profiler_set function
    */
    private static function _model($file, $model_name, $object_name = '', $params_or_no_ins = '')
    {
        if ( ! file_exists($file))
        {
            throw new LoaderException('Unable to locate the model: '.$model_name);
        }
        
        $model_var = $model_name;
        if($object_name != '') $model_var = $object_name; 
        
        $OB = Obullo::instance();  
        
        if (isset($OB->$model_var) AND is_object($OB->$model_var)) { return; }
        
        require_once($file);
        $model = ucfirst($model_name);   

        if($params_or_no_ins === FALSE)
        {
            profiler_set('models', $model_var.'_no_instantiate', $model_name);
            return; 
        }
        
        if( ! class_exists($model, false)) // autoload false.
        {
            throw new LoaderException('You have a small problem, model name isn\'t right in here: '.$model);
        }
        
        // store loaded obullo models
        profiler_set('models', $model_var, $model_var);     // should be above the new model();
        
        $OB->$model_var = new $model($params_or_no_ins);    // register($class); we don't need it   

        // assign all loaded db objects inside to current model
        // loader::database() support for Model_x { function __construct() { loader::database() }}
        $OB->$model_var->_assign_db_objects();
        
    }

    // --------------------------------------------------------------------
    
    /**
    * loader::database();
    * 
    * Database load.
    * This function loads the database for controllers
    * and model files.
    * 
    * @author   Ersin Guvenc
    * @param    mixed $db_name for manual connection
    * @param    boolean $instantiate switch
    * @param    boolean $ac active record switch
    * @version  0.1
    * @version  0.2 multiple models load::database function support.
    *               Loading model inside again model bug fixed.
    * @version  0.3 Deprecated debug_backtrace(); function
    *               added asn_to_libraries();, asn_to_models();
    * @version  0.4 added DBFactory() Actice Record Switch
    *               added $ac param.
    *                     
    * @version  0.5 added $db_name param for multiple connection
    * @version  0.6 @deprecated asn_to_models();, removed unecessarry functions.
    *               added self::_assign_db_objects() func. 
    * @version  0.7 changed DBFactory, moved db_var into DBFactory
    * @version  0.8 changed DBFactory class as static, added $return_object param
    * @version  0.9 renamed OB_DBFactory::init() func as OB_DBFactory::Connect()
    * @version  1.0 added profiler_set('databases') function.
    * @return   void
    */
    public static function database($db_name = 'db', $return_object = FALSE)
    {
        $OB = Obullo::instance();
        
        $db_var = $db_name;
         
        if(is_array($db_name) AND isset($db_name['variable']))
        {
            $db_var = $db_name['variable'];
        }
    
        if (isset($OB->{$db_var}) AND is_object($OB->{$db_var})) 
        {
            if($return_object)
            return $OB->{$db_var};
                
            return;
        }   
        
        if($return_object)
        {    
            profiler_set('databases', $db_name, $db_var);  // Store db variables ..
            
            return OB_DBFactory::Connect($db_name, $db_var); // Return to database object ..
        }
        
        $OB->{$db_var} = OB_DBFactory::Connect($db_name, $db_var);   // Connect to Database
    
        profiler_set('databases', $db_name, $db_var);  // Store db variables
        
        self::_assign_db_objects($db_var);

    }

    // --------------------------------------------------------------------
    
    /**
    * loader::app_helper();
    * 
    * loader::app_helper('subfolder/helper_name')  local sub folder load
    * loader::app_helper('.outside_folder/helper_name')  outside directory load
    * 
    * @version  0.1
    * @version  0.2 added self::$_app_helpers static var
    * @version  0.3 added loader::app_helper('subfolder/helper_name') and 
    *               loader::app_helper('.outside_folder/helper_name') support
    * @param    string $helper
    */
    public static function app_helper($helper)
    {
        if( isset(self::$_app_helpers[$helper]) )
        {
            return; 
        }
    
        $data = self::_load_file($helper, $folder = 'helpers', 'app_helper');

        if(file_exists($data['file']))
        {
            include($data['file']);
            
            self::$_app_helpers[$helper] = $helper;
            
            return;
        }
        
        throw new LoaderException('Unable to locate the application helper: ' .$helper. EXT); 
    }
    
    // --------------------------------------------------------------------
    
    /**
    * loader::helper();
    * 
    * loader::helper('subfolder/helper_name')  local sub folder load
    * loader::helper('.outside_folder/helper_name')  outside directory load
    * 
    * We have three helper directories
    *   o Base/helpers  : /base helpers
    *   o App/helpers   : /application helpers
    *   o Local/helpers : /directiories/$directory/ helpers
    * 
    * @author   Ersin Guvenc
    * @param    string $helper
    * @version  0.1
    * @version  0.2 changed $GLOBALS['c'] as $GLOBALS['d']
    * @version  0.3 changed base helper functionality as base_helper()
    * @version  0.4 added multiple helper load functionality
    * @version  0.5 added self::$_helpers static var
    * @version  0.6 loader::helper('subfolder/helper_name')  local sub folder load support
    *               loader::helper('.outside_folder/helper_name')  outside directory  load support
    * @return   void
    */
    public static function helper($helper, $func = 'helper')
    { 
        if( isset(self::$_helpers[$helper]) )
        {
            return; 
        }
        
        $data = self::_load_file($helper, $folder = 'helpers', $loader_func = 'helper');

        if(file_exists($data['file']))
        {
            include($data['file']);
            
            self::$_helpers[$helper] = $helper;
            
            return;
        } 
        
        throw new LoaderException('Unable to locate the helper: '.$helper. EXT);   
    }   
    
    // --------------------------------------------------------------------
    
    /**
    * loader::base_helper();
    *
    * @author   Ersin Guvenc
    * @param    string $helper
    * @version  0.1
    * @version  0.2 added self::$_base_helpers static var
    * @version  0.3 added extend support for base helpers
    * @return   void
    */
    public static function base_helper($helper)
    {   
        if( isset(self::$_base_helpers[$helper]) )
        {
            return; 
        }
        
        if(file_exists(BASE .'helpers'. DS .$helper. EXT)) 
        { 
            $prefix = config_item('subhelper_prefix');
        
            if(file_exists(APP .'helpers'. DS .$prefix. $helper. EXT))  // If app helper my_file exist.
            {
                include(APP .'helpers'. DS .$prefix. $helper. EXT);
                
                self::$_base_helpers[$prefix . $helper] = $prefix . $helper; 
            }
            
            include(BASE .'helpers'. DS .$helper. EXT);
            
            self::$_base_helpers[$helper] = $helper;
            
            return; 
        }
        
        throw new LoaderException('Unable to locate the base helper: ' .$helper. EXT);
    }
    
    // --------------------------------------------------------------------
    
    public static function lang($file, $return = FALSE)
    {               
        lang_load($file, '', 'local', $return);
    }
        
    // --------------------------------------------------------------------
        

    public static function app_lang($file = '', $folder = '', $return = FALSE)
    {
        lang_load($file, $folder, 'global', $return);
    }
    
    // ------------------------------------------------------------------
            

    public static function base_lang($file = '', $folder = '', $return = FALSE)
    {
        lang_load($file, $folder, 'base' ,$return);
    }
    
    // --------------------------------------------------------------------
                          
    public static function config($file)    
    {
        this()->config->load($file);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Load a file from ROOT directory.
    *
    * @access   public
    * @param    string $file filename
    * @version  0.1
    * @version  0.2  added storing files to profiler class functionality.
    *                removed EXT constant
    * @version  0.3  added profiler set func.
    * @return   void
    */                                 
    public static function file($path, $string = FALSE, $ROOT = APP)    
    {
        if( isset(self::$_files[$path]) )
        return;
        
        if(file_exists($ROOT .$path)) 
        { 
            self::$_files[$path] = $path;
            
            log_message('debug', 'External file loaded: '.$path);
       
            profiler_set('files', $path, $ROOT . $path);  // store into profiler
        
            if($string === TRUE)
            {
                ob_start();
                include_once($ROOT .$path);
            
                $content = ob_get_contents();
                @ob_end_clean();
                
                return $content;
            }
            
            require_once($ROOT .$path);
            return;
        }
        
        throw new LoaderException('Unable to locate the external file: ' .$path);
    }
   
    
    // --------------------------------------------------------------------
    
    /**
    * Comman file loader for models and
    * helpers functions.
    * 
    * @param string $filename
    * @param string $folder
    * @param string $loader_func
    * 
    * return array  file_name | file
    */
    private static function _load_file($filename, $folder = 'helpers', $loader_func = 'app_helper')
    {   
        $real_name  = strtolower($filename);
        $root       = APP .'directories';
        
        if($loader_func == 'app_model')
        $root       = APP .'models';
        
        if($loader_func == 'app_helper')
        $root       = APP .'helpers';
        
        if(strpos($real_name, '/') > 0)         //  inside folder request
        {
            $paths      = explode('/',$real_name);   // paths[0] = path , [1] file name     
            $file_name  = array_pop($paths);          // get file name
            $path       = implode('/', $paths);
            
            $sub_root   = $GLOBALS['d']. DS .$folder. DS;
            if(strpos($loader_func, 'app_') === 0)
            $sub_root   = '';
           
            $file = $root. DS .$sub_root. $path. DS .$file_name. EXT;
            
            if(strpos($real_name, '.') === 0)   // ./outside folder request
            {
                $file = APP .'directories'. DS .substr($path, 1). DS .$folder. DS .$file_name. EXT;
            }
                
            return array('file_name' => $file_name, 'file' => $file);
        } 
        
        $sub_root   = $GLOBALS['d']. DS .$folder. DS;
        if(strpos($loader_func, 'app_') === 0)
        $sub_root   = '';
        
        return array('file_name' => $real_name, 'file' => $root. DS .$sub_root. $real_name. EXT);
    } 
    
    // --------------------------------------------------------------------
    
    /**
    * Assign db objects to all Models
    * 
    * @author  Ersin Guvenc
    * @param   string $db_var
    *
    * @version 0.1
    * @version 0.2  @deprecated old functions, we assign
    *               just db objects ..
    * @version 0.3  changed ob::instance()->_mods as 
    *               Ssc::instance()->_profiler_mods; 
    * @version 0.4  added profiler_get func.
    * @return  void
    */
    private static function _assign_db_objects($db_var = '')
    {
        $models = profiler_get('models');

        $OB = Obullo::instance();

        if (count($models) == 0) return;

        foreach ($models as $model_name)
        {
            $OB->$model_name->$db_var = &$OB->$db_var;
        }
    
    }
  
}
// END Loader Class

/* End of file Loader.php */
/* Location: ./base/obullo/Loader.php */