<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Framework software for PHP 5.2.4 or newer
 *
 * @package         obullo
 * @filename        base/Common.php        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */
 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);


/**
* register();
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
function register($class, $params = NULL)
{
    $registry = OB_Registry::singleton();
    
    $Class = strtolower($class); //lowercase classname.

    $getObject = $registry->getObject($Class);
    
    // if class already stored we are done.
    
    if ($getObject !== NULL)
    return $getObject;
    
    $file_exists = OB_Library::factory($Class);
    
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
* base_register()
* 
* Register base classes which start by OB_ prefix
* Like CI load class
* 
* @access   public
* @param    string  the class name being requested
* @param    bool    optional flag that lets classes get loaded but not instantiated
* @version  1.0
* @return   object  | NULL
*/
function base_register($class, $instantiate = TRUE)
{
    $registry = OB_Registry::singleton();

    $getObject = $registry->getObject($class);

    if ($getObject !== NULL)
    return $getObject;
    
    $file_exists = OB_Library::factory($class);
    
    if($file_exists)
    {                           
        $classname = 'OB_'.$class;
        
        $registry->storeObject($class, new $classname());
    
        //return singleton object.
        $Object = $registry->getObject($class);

        if(is_object($Object))
        return $Object;
    }
    
    return NULL;  // if register func return to null 
                  // we will show a loader exception
}

/**
* register_static();
* 
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
function register_static($real_name, $base = FALSE)
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

/**
* Loads the main config.php file
*
* @access    private
* @return    array
*/
function get_config()
{
    static $main_conf;

    if ( ! isset($main_conf))
    {
        if ( ! file_exists(APP.'config'.DS.'config'.EXT))
        {
            exit('The configuration file config'.EXT.' does not exist.');
        }

        require(APP.'config'.DS.'config'.EXT);

        if ( ! isset($config) OR ! is_array($config))
        {
            exit('Your config file does not appear to be formatted correctly.');
        }

        $main_conf[0] =& $config;
    }
    return $main_conf[0];
}
 
?>
