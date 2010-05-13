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
 
require(APP  .'system'. DS .'init'. DS .'Bootstrap'. EXT);  
require(BASE .'obullo'. DS .'Bootstrap'. EXT);

/**
|--------------------------------------------------------------------------
| User Front Controller for Bootstrap.php file. 
|--------------------------------------------------------------------------
|
| User can create own Front Controller who want replace
| system methods by overriding to Bootstrap.php library.
| @see
| User Guide: Chapters / General Topics / Control Your Application Boot
|
*/
ob_include_files();
ob_include_headers();

ob_system_run();
ob_system_close();

// --------------------------------------------------------------------

/* Beta 1.0 Rc1 Offical Version Release Date: ? - ?  */
/* SVN $Id: Index.php 174 18-04-2010 15:24:38 develturk $ */
?>