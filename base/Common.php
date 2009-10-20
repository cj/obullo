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
* Common.php
* 
* @version 1.0
* @version 1.1 added removed OB_Library:factory added
*              lib_factory function
* @version 1.2 removed lib_factory function
* 
*/

/**
* register();
* Registry Controller Function
* 
* @access   public
* @author   Ersin Güvenç
* @param    string $class name of the class.
* @version  0.1
* @version  0.2 added $file_exists var
* @version  0.3 moved into ob class
* @version  0.4 added __construct(params=array()) support
* @version  0.5 removed OB_Library::factory()
*               added lib_factory() function
* @return   object | NULL
*/
function register($class, $params = NULL)
{
    $registry = OB_Registry::singleton();
    
    $Class = strtolower($class); //lowercase classname.

    $getObject = $registry->getObject($Class);
    
    // if class already stored we are done.
    
    if ($getObject !== NULL)
    return $getObject;
    
    //$file_exists = lib_factory($Class);
    
    if(file_exists(APP.'libraries'.DS.$class.EXT))
    {
        require(APP.'libraries'.DS.$class.EXT);
        
        $classname = ucfirst($class);
        
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
* @param    string the class name being requested
* @param    bool optional flag that lets classes get loaded but not instantiated
* @version  0.1
* @version  0.5 removed OB_Library::factory()
*               added lib_factory() function
* @return   object  | NULL
*/
function base_register($class, $params = NULL, $instantiate = TRUE)
{
    $registry = OB_Registry::singleton();
    
    $Class = ucfirst($class);
    
    $getObject = $registry->getObject($Class);

    if ($getObject !== NULL)
    return $getObject;
    
    //$file_exists = lib_factory($class);
    
    if(file_exists(BASE.'libraries'.DS.$Class.EXT))
    {
        require(BASE.'libraries'.DS.$Class.EXT);
        
        $classname = 'OB_'.$Class;
        
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
    
    if(file_exists($Path.'libraries'.DS.$Class.EXT))
    {
        require($Path.'libraries'.DS.$Class.EXT);
        
        return TRUE;
    }
    
    return NULL;  // if register func return to null 
                  // we will show a loader exception inside from
                  // which file used ob::register_static func.
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
        throw new CommonException('The configuration file config'.EXT.' does not exist.');
        
        
        require(APP.'config'.DS.'config'.EXT);

        
        if ( ! isset($config) OR ! is_array($config))
        throw new CommonException('Your config file does not appear to be formatted correctly.');
    
        $main_conf[0] =& $config;
    }
    
    return $main_conf[0];

}
 
?>
