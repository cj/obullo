<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo     
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */

/**
* Common.php
* 
* @version 1.0
* @version 1.1 added removed OB_Library:factory added
*              lib_factory function
* @version 1.2 removed lib_factory function
* @version 1.3 renamed base "libraries" folder as "base"
* @version 1.4 added $var  and confi_name vars for get_config()
* @version 1.5 added PHP5 library interface class
*/

interface PHP5_Library 
{
    public static function instance();
    public function init();
}

/**
* register();
* Registry Controller Function
* 
* @access   private
* @author   Ersin Güvenç
* @param    string $class name of the class.
* @param    array $params construct params of the class.
* @version  0.1
* @version  0.2 added $file_exists var
* @version  0.3 moved into ob class
* @version  0.4 added __construct(params = array()) support
* @version  0.5 removed OB_Library::factory(), lib_factory()
* @version  0.6 added $dir param
* @return   object | NULL
*/
function register($class, $params = NULL, $dir = '')
{
    switch ($dir)
    {
       case 'directory':
       $dir = DIR . $GLOBALS['d']. DS;
         break;
         
       default: // application.
       $dir = APP; 
    }

    $registry = OB_Registry::singleton();
    
    $Class = strtolower($class); //lowercase classname.

    $getObject = $registry->getObject($Class);
    
    if ($getObject !== NULL)     // if class already stored we are done.
    return $getObject;
    
    if(file_exists($dir.'libraries'.DS.$class.EXT))
    {
        require($dir.'libraries'.DS.$class.EXT);
        
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
* 
* @access   private
* @param    string the class name being requested
* @param    bool optional flag that lets classes get loaded but not instantiated
* @version  0.1
* @version  0.2 removed OB_Library::factory()
*               added lib_factory() function
* @version  0.3 renamed base "libraries" folder as "base"
* @version  0.4 added extend to core libraries support
* 
* @return   object  | NULL
*/
function base_register($class, $params = NULL, $instantiate = TRUE)
{
    $registry = OB_Registry::singleton();
    
    $Class = ucfirst($class);
    
    $getObject = $registry->getObject($Class);

    if ($getObject !== NULL)
    return $getObject;
    
    if(file_exists(BASE .'libraries'. DS .$Class. EXT))
    {
        require(BASE .'libraries'. DS .$Class. EXT);
        
        $classname = 'OB_'.$Class;
        
        $prefix = config_item('subclass_prefix');  // MY_
                                             
        if(file_exists(APP .'libraries'. DS .$prefix.$Class. EXT)) // extend support.
        {
            require(APP .'libraries'. DS .$prefix.$Class. EXT);
            
            $classname = $prefix.$Class;
        }
        
        if(is_array($params)) // construct support.
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
* @access   public
* @author   Ersin Güvenç
* @param    string $class name of the class.
*           You must provide real class name. (lowercase)
* @param    boolean $base base class or not
* @version  0.1
* @version  0.2 added base param
* @version  0.3 renamed base "libraries" folder as "base"
* 
* @return TRUE | NULL
*/
function register_static($real_name, $base = FALSE)
{   
    if(class_exists($real_name))
    return TRUE;

    $Class = strtolower($real_name); //lowercase classname.
    $Path  = APP .'libraries'. DS .'php5';

    if($base)
    {
        $Class = ucfirst($Class);
        $Path  = BASE .'libraries'. DS .'php5'; 
    }
    
    if(file_exists($Path. DS .$Class. EXT))
    {
        require($Path. DS .$Class. EXT);
        
        return TRUE;
    }
    
    return NULL;  // if register func return to null 
                  // we will show a loader exception inside from
                  // which file used ob::register_static func.
} 

/**
* Loads the (static) configuration or language files.
*
* @access    private
* @author    Ersin Güvenç
* @param     string $filename file name
* @param     string $var variable of the file
* @param     string $folder folder of the file
* @version   0.1
* @version   0.2 added $config_name param
* @version   0.3 added $var variable
* @version   0.4 renamed function as get_static ,renamed $config_name as $filename, added $folder param
* @return    array
*/
function get_static($filename = 'config', $var = '', $folder = '')
{
    static $static = array();

    if ( ! isset($static[$filename]))
    {
        if ( ! file_exists($folder.DS.$filename.EXT))
        throw new CommonException('The static file '.DS.$folder.DS.$filename.EXT.' does not exist.');
        
        require($folder.DS.$filename.EXT);
        
        if($var == '') $var = &$filename;
        
        if ( ! isset($$var) OR ! is_array($$var))
        throw new CommonException('The static file '.DS.$folder.DS.$filename.EXT.' file does not appear to be formatted correctly.');
    
        $static[$filename] =& $$var;
     }

    return $static[$filename];    
}

/**
* Get config file.
* 
* @param    string $filename
* @param    string $var
* @return   array
*/
function get_config($filename = 'config', $var = '')
{
    return get_static($filename, $var, 'application'.DS.'config');
}

/**
* Gets a config item
*
* @access    public
* @param     string $config_name file name
* @version   0.1
* @version   0.2 added $config_name var
*            multiple config support
* @return    mixed
*/
function config_item($item, $config_name = 'config')
{
    static $config_item = array();

    if ( ! isset($config_item[$item]))
    {
        $config_name = get_config($config_name);

        if ( ! isset($config_name[$item]))
        return FALSE;
        
        $config_item[$item] = $config_name[$item];
    }
    
    return $config_item[$item];
}

/**
* Gets a db configuration items
*
* @access    public
* @author    Ersin Güvenç
* @param     string $item
* @param     string $index 'default'
* @version   0.1
* @version   0.2 added multiple config fetch
* @return    mixed
*/
function db_item($item, $index = 'db')
{
    static $db_item = array();

    if ( ! isset($db_item[$index][$item]))
    {
        $database = get_config('database');

        if ( ! isset($database[$index][$item]))
        return FALSE;
        
        $db_item[$index][$item] = $database[$index][$item];
    }

    return $db_item[$index][$item];
}
 
/**
 * Tests for file writability
 *
 * is_writable() returns TRUE on Windows servers when you really can't write to 
 * the file, based on the read-only attribute.  is_writable() is also unreliable
 * on Unix servers if safe_mode is on. 
 *
 * @access    private
 * @return    void
 */
function is_really_writable($file)
{    
    // If we're on a Unix server with safe_mode off we call is_writable
    if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == FALSE)
    {
        return is_writable($file);
    }

    // For windows servers and safe_mode "on" installations we'll actually
    // write a file then read it.  Bah...
    if (is_dir($file))
    {
        $file = rtrim($file, '/').'/'.md5(rand(1,100));

        if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
        {
            return FALSE;
        }

        fclose($fp);
        @chmod($file, DIR_WRITE_MODE);
        @unlink($file);
        return TRUE;
    }
    elseif (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
    {
        return FALSE;
    }

    fclose($fp);
    return TRUE;
}

/**
* Error Logging Interface
*
* We use this as a simple mechanism to access the logging
* class and send messages to be logged.
*
* @access    public
* @return    void
*/
function log_message($level = 'error', $message, $php_error = FALSE)
{
    static $LOG;
    
    $config = get_config();
    if ($config['log_threshold'] == 0)
    {
        return;
    }

    $LOG = base_register('Log');
    $LOG->write_log($level, $message, $php_error);
}

/**
* Determines if the current version of PHP is greater then the supplied value
*
* Since there are a few places where we conditionally test for PHP > 5
* we'll set a static variable.
*
* @access    public
* @param    string
* @return    bool
*/
function is_php($version = '5.0.0')
{
    static $_is_php;
    $version = (string)$version;
    
    if ( ! isset($_is_php[$version]))
    {
        $_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
    }

    return $_is_php[$version];
}


/**
 * Set HTTP Status Header
 *
 * @access   public
 * @param    int     the status code
 * @param    string    
 * @return   void
 */
function set_status_header($code = 200, $text = '')
{
    $stati = array(
                        200    => 'OK',
                        201    => 'Created',
                        202    => 'Accepted',
                        203    => 'Non-Authoritative Information',
                        204    => 'No Content',
                        205    => 'Reset Content',
                        206    => 'Partial Content',

                        300    => 'Multiple Choices',
                        301    => 'Moved Permanently',
                        302    => 'Found',
                        304    => 'Not Modified',
                        305    => 'Use Proxy',
                        307    => 'Temporary Redirect',

                        400    => 'Bad Request',
                        401    => 'Unauthorized',
                        403    => 'Forbidden',
                        404    => 'Not Found',
                        405    => 'Method Not Allowed',
                        406    => 'Not Acceptable',
                        407    => 'Proxy Authentication Required',
                        408    => 'Request Timeout',
                        409    => 'Conflict',
                        410    => 'Gone',
                        411    => 'Length Required',
                        412    => 'Precondition Failed',
                        413    => 'Request Entity Too Large',
                        414    => 'Request-URI Too Long',
                        415    => 'Unsupported Media Type',
                        416    => 'Requested Range Not Satisfiable',
                        417    => 'Expectation Failed',

                        500    => 'Internal Server Error',
                        501    => 'Not Implemented',
                        502    => 'Bad Gateway',
                        503    => 'Service Unavailable',
                        504    => 'Gateway Timeout',
                        505    => 'HTTP Version Not Supported'
                    );

    if ($code == '' OR ! is_numeric($code))
    {
        show_error('Status codes must be numeric', 500);
    }

    if (isset($stati[$code]) AND $text == '')
    {                
        $text = $stati[$code];
    }
    
    if ($text == '')
    {
        show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
    }
    
    $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

    if (substr(php_sapi_name(), 0, 3) == 'cgi')
    {
        header("Status: {$code} {$text}", TRUE);
    }
    elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
    {
        header($server_protocol." {$code} {$text}", TRUE, $code);
    }
    else
    {
        header("HTTP/1.1 {$code} {$text}", TRUE, $code);
    }
}


?>
