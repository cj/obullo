<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 * 
 * @package         obullo    
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
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
function Obullo_Error_Template($errno, $errstr, $errfile, $errline, $type)
{ 
    echo $errstr;
    
    ob_start();
    include(APP .'system'. DS .'errors'. DS .'ob_error'. EXT);
    $buffer = ob_get_contents(); 
    ob_end_clean();

    log_php_errors($type, $errstr, $errfile, $errline);

    echo $buffer;
}

// -------------------------------------------------------------------- 

/**
 * Php Error Logger
 *
 * This function logs PHP generated error messages
 *
 * @access   private
 * @param    string    the error type
 * @param    string    the error string
 * @param    string    the error file
 * @param    int       the error line number
 * @return   string
 */
function log_php_errors($type, $errstr, $errfile, $errline)
{    
    log_me('error', 'Php Error Type: '.$type.'  --> '.$errstr. ' '.$errfile.' '.$errline, TRUE);
}

/**
* Catch Exceptions
* 
* @param object $e
*/
function Obullo_Exception_Handler($e)
{   
    $type = 'Exception';
    $sql  = '';
        
    if(substr($e->getMessage(),0,3) == 'SQL') 
    {
        $ob   = this();
        $type = 'Database';
        
        foreach(profiler_get('databases') as $db_name => $db_var)
        {
           if(is_object($ob->$db_var))
           $sql .= $ob->{$db_var}->last_query($ob->{$db_var}->prepare); 
        }        
    }
    
    ob_start();
    include(APP .'system'. DS .'errors'. DS .'ob_exception'. EXT);
    $buffer = ob_get_contents(); 
    ob_end_clean();
    
    log_php_errors('Exception', $e->getMessage(), $e->getFile(), $e->getLine());
    
    echo $buffer;
}       

// -------------------------------------------------------------------- 
 
/**
* 404 Page Not Found Handler
*
* @access   private
* @param    string
* @return   string
*/
function show_404($page = '')
{   
    log_me('error', '404 Page Not Found --> '.$page);
    echo show_http_error('404 Page Not Found', $page, 'ob_404', 404);

    exit;
}

// -------------------------------------------------------------------- 

/**
* Manually Set General Http Errors
* 
* @param string $message
* @param int    $status_code
* @param int    $heading
* 
* @version 0.1
* @version 0.2  added custom $heading params for users
*/
function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
{
    log_me('error', 'HTTP Error --> '.$message); 
    echo show_http_error($heading, $message, 'ob_general', $status_code);
    
    exit;
}
                   
// --------------------------------------------------------------------

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
    
    ob_start();
    include(APP. 'system'. DS .'errors'. DS .$template. EXT);
    $buffer = ob_get_contents(); 
    ob_end_clean();
    
    return $buffer;
}

// --------------------------------------------------------------------

/**
* Main Error Handler
* 
* @param int $errno
* @param string $errstr
* @param string $errfile
* @param int $errline
*/
function Obullo_Error_Handler($errno, $errstr, $errfile, $errline)
{
    if (($errno & error_reporting()) == 0) return;  
    
    switch ($errno)
    {
        case E_ERROR:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "ERROR");
        break;
        
        case E_WARNING:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "WARNING");
        break;
        
        case E_PARSE:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "PARSE ERROR");
        break;
        
        case E_NOTICE:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "NOTICE");
        break;
                           
        case E_CORE_ERROR:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "CORE ERROR");
        break;
        
        case E_CORE_WARNING:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "CORE WARNING");
        break;
        
        case E_COMPILE_ERROR:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "COMPILE ERROR");
        break;
        
        case E_USER_ERROR:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "USER FATAL ERROR");
            exit();
        break;   
            
        case E_USER_WARNING:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "USER WARNING");
        break;
        
        case E_USER_NOTICE:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "USER NOTICE");
        break;
        
        case E_STRICT:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "STRICT ERROR");
        break;
        
        case E_RECOVERABLE_ERROR:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "RECOVERABLE ERROR");
        break;
        
        case E_DEPRECATED:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "DEPRECATED ERROR");
        break;
        
        case E_USER_DEPRECATED:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "USER DEPRECATED ERROR");
        break;
        
        case E_ALL:
            Obullo_Error_Template($errno, $errstr, $errfile, $errline, "ERROR");
        break;
    
    }
    
    return TRUE;    // return true and don't execute internal error handler 
}          

// -------------------------------------------------------------------- 

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

// -------------------------------------------------------------------- 

set_error_handler('Obullo_Error_Handler');
set_exception_handler('Obullo_Exception_Handler');


// END Errors.php File

/* End of file Errors.php */
/* Location: ./base/obullo/Errors.php */
