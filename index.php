<?php
// This function just for memory testing..
function OB_memory_usage() {
$usage = '';
 
        $mem_usage = memory_get_usage(true); 
        
        if ($mem_usage < 1024) 
            $usage =  $mem_usage." bytes"; 
        elseif ($mem_usage < 1048576) 
            $usage = round($mem_usage/1024,2)." kilobytes"; 
        else 
            $usage = round($mem_usage/1048576,2)." megabytes"; 
            
        return $usage;
} 

// start memory test
//$start = OB_memory_usage();
$start = memory_get_usage();

//ini_set('register_globals', false);       

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC-min Software for PHP 5.2.4 or newer
 * Derived from Code Igniter.
 * 
 * @package         obullo
 * @filesource      index.php        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0 @alpha
 * @license         http://www.opensource.org/licenses/gpl-3.0.html GPL 
 */
                              
/* ---------------------------------------------------------------------------*/                               



header('Content-type: text/html;charset=UTF-8'); 

Class CommonException extends Exception {}  

// Base paths
define('DS',DIRECTORY_SEPARATOR);
define('BASE', 'base'.DS);            
define('APP',  'application'.DS);   
define('EXT',  '.php');
define('MODEL', 'application'.DS.'controllers'.DS);
define('VIEW', 'application'.DS.'controllers'.DS);
define('CONTROLLER', 'application'.DS.'controllers'.DS);
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));  

require (BASE.'libraries'.DS.'Registry'.EXT); 
require (BASE.'libraries'.DS.'Library_factory'.EXT); 
require (BASE.'Common'.EXT);    
require (BASE.'libraries'.DS.'Errors'.EXT); 

$Config = base_register('Config');
$Uri    = base_register('URI');
$Router = base_register('Router');

//echo 'class: '.$Router->fetch_class().'<br />';
//echo 'method: '.$Router->fetch_method();  exit;

$GLOBALS['c']   = $Router->fetch_class();  // Get requested controller
$GLOBALS['m']   = $Router->fetch_method(); // Get requested method

// Check the controller exists or not
if ( ! file_exists(APP.'controllers'.DS.$GLOBALS['c'].DS.$GLOBALS['c'].EXT))
{
    new CommonException('Unable to load your default controller.
    Please make sure the controller specified in your Routes.php file is valid.');
}

require (BASE.'libraries'.DS.'Loader'.EXT);
require (BASE.'libraries'.DS.'Ob'.EXT);
require (BASE.'libraries'.DS.'Controller'.EXT);
require (BASE.'libraries'.DS.'Library'.EXT); 
require (BASE.'libraries'.DS.'Model'.EXT);

// call the controller.
require (CONTROLLER.$GLOBALS['c'].DS.$GLOBALS['c'].EXT);


if ( ! class_exists($GLOBALS['c'])
    OR $GLOBALS['m'] == 'controller'
    OR strncmp($GLOBALS['m'], '_', 1) == 0
    OR in_array(strtolower($GLOBALS['m']), array_map('strtolower', get_class_methods('Controller')))
    )
{
    show_404("{$GLOBALS['c']}/{$GLOBALS['m']}");
}


// If Everyting ok !
$OB = new $GLOBALS['c']();


// Check method exist or not
if ( ! in_array(strtolower($GLOBALS['m']), array_map('strtolower', get_class_methods($OB))))
{
    show_404("{$GLOBALS['c']}/{$GLOBALS['m']}"); 
}

// Call the requested method.
// Any URI segments present (besides the class/function) will be passed to the method for convenience
call_user_func_array(array($OB, $GLOBALS['m']), array_slice($Uri->rsegments, 2));


//$end = OB_memory_usage();
$end = memory_get_usage();

echo '<b>Started memory:</b> '.$start.'<br />';
echo '<b>Total consumed memory: </b>'.$end.'<br />';


?>
