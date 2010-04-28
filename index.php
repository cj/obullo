<?php
/**
|--------------------------------------------------------------------------
| Obullo Framework (c) 2010. 
|--------------------------------------------------------------------------
|
| @version See .base/obullo/obullo.php for the version.
| 
| PHP5 MVC Based Minimalist Software for PHP 5.1.2 or newer
| @see     license.txt
*/ 

define('DS',   DIRECTORY_SEPARATOR);  

/**
|--------------------------------------------------------------------------
| Php error reporting
|--------------------------------------------------------------------------
|
| Predefined error constants
| @see http://usphp.com/manual/en/errorfunc.constants.php

| For security
| reasons you are encouraged to change this when your site goes live.
|
*/
error_reporting(E_ALL | E_STRICT); 

/**
|---------------------------------------------------------------
| APPLICATION FOLDER CONSTANT
|---------------------------------------------------------------
|
| If you want this front controller to use a different "application"
| folder then the default one you can set its name here. The folder 
| can also be renamed or relocated anywhere on your server.
| @see
| User Guide: Chapters / General Topics / Managing Your Applications
|
*/
define('BASE', 'base'. DS);
define('APP',  'application'. DS);  
                                                       
// --------------------------------------------------------------------                               

define('EXT',  '.php'); 
define('FCPATH', __FILE__);
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('DIR',  APP .'directories'. DS);
 
require(BASE .'obullo'. DS .'Front_controller.php');
                        
/**
|--------------------------------------------------------------------------
| Custom User Front Controller
|--------------------------------------------------------------------------
|
| User can create own Front Controller who want extend
| and do method overridding for base OB_Front_Controller library
| @see
| User Guide: Chapters / General Topics / Front Controller
*/
require(APP .'system'. DS .'init'. DS .'Front_controller'.EXT);
                                          
$application = new Front_controller();
$application->run();        
  
// --------------------------------------------------------------------


/* Beta 1.0 None Offical version Release Date: 01-04-2010 17:12:38 */
/* Beta 1.0 Rc1 Offical version Release Date: ? - ?  */

/* SVN $Id: Index.php 165 18-04-2010 15:24:38 develturk $ */

?>
