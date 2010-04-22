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

/*
*  Predefined error constants
*  http://usphp.com/manual/en/errorfunc.constants.php
* * 
1     E_ERROR
2     E_WARNING
4     E_PARSE
8     E_NOTICE
16    E_CORE_ERROR
32    E_CORE_WARNING
64    E_COMPILE_ERROR
128   E_COMPILE_WARNING
256   E_USER_ERROR
512   E_USER_WARNING
1024  E_USER_NOTICE
2048  E_STRICT
4096  E_RECOVERABLE_ERROR
8192  E_DEPRECATED
16384 E_USER_DEPRECATED
30719 E_ALL
*/         
    
/**
* Error Template
* 
* @param mixed $errno
* @param mixed $errstr
* @param mixed $errfile
* @param mixed $errline
* @param mixed $type
*/
function Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, $type)
{   
    include(APP.'system'.DS.'errors'.DS.'ob_error'.EXT);
}

/**
* Catch Exceptions
* 
* @param object $e
*/
function Obullo_ExceptionHandler($e)
{   
    $type = 'Exception';
    $sql  = '';
        
    if(substr($e->getMessage(),0,3) == 'SQL') 
    {
        $ob   = ob::instance();
        $type = 'Database';
        
        foreach($ob->_dbs as $key => $val)
        {
           if(is_object($ob->$key))
           {
                $sql .= $ob->{$key}->last_query($ob->{$key}->prepare);
           }
        }        
    }
    
    include(APP.'system'.DS.'errors'.DS.'ob_exception'.EXT);
}       
 
/**
* 404 Page Not Found Handler
*
* @access   private
* @param    string
* @return   string
*/
function show_404($page = '')
{    
    include(APP.'system'.DS.'errors'.DS.'ob_404'.EXT);
    
    log_message('error', '404 Page Not Found --> '.$page);
    
    exit;
}

/**
* Manually Set General Http Errors
* 
* @param string $message
* @param int    $status_code
*/
function show_error($message, $status_code = 500)
{
    show_http_error('An Error Was Encountered', $message, 'ob_general', $status_code);
    
    log_message('error', 'HTTP Error --> '.$message);
    
    exit;
}

/**
 * General Http Errors
 *
 * @access   private
 * @param    string    the heading
 * @param    string    the message
 * @param    string    the template name
 * @param    int       header status code
 * @return   string
 */
function show_http_error($heading, $message, $template = 'ob_general', $status_code = 500)
{
    set_status_header($status_code);
    
    $message = implode('<br />', ( ! is_array($message)) ? array($message) : $message);
    
    include(APP.'system'.DS.'errors'.DS.$template.EXT);
}

/**
* Main Error Handler
* 
* @param int $errno
* @param string $errstr
* @param string $errfile
* @param int $errline
*/
function Obullo_ErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (($errno & error_reporting()) == 0) return;  
    
    switch ($errno)
    {
        case E_ERROR:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "ERROR");
        break;
        
        case E_WARNING:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "WARNING");
        break;
        
        case E_PARSE:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "PARSE ERROR");
        break;
        
        case E_NOTICE:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "NOTICE");
        break;
                           
        case E_CORE_ERROR:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "CORE ERROR");
        break;
        
        case E_CORE_WARNING:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "CORE WARNING");
        break;
        
        case E_COMPILE_ERROR:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "COMPILE ERROR");
        break;
        
        case E_USER_ERROR:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "USER FATAL ERROR");
            exit();
        break;   
            
        case E_USER_WARNING:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "USER WARNING");
        break;
        
        case E_USER_NOTICE:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "USER NOTICE");
        break;
        
        case E_STRICT:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "STRICT ERROR");
        break;
        
        case E_RECOVERABLE_ERROR:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "RECOVERABLE ERROR");
        break;
        
        case E_DEPRECATED:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "DEPRECATED ERROR");
        break;
        
        case E_USER_DEPRECATED:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "USER DEPRECATED ERROR");
        break;
        
        case E_ALL:
            Obullo_ErrorTemplate($errno, $errstr, $errfile, $errline, "ERROR");
        break;
    
    }
    
    return TRUE;    // return true and Don't execute PHP internal error handler 
}          


set_error_handler("Obullo_ErrorHandler");
set_exception_handler('Obullo_ExceptionHandler');
    
?>