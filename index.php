<?php

/**
|--------------------------------------------------------------------------
| Obullo Framework (c) 2009 - 2010. 
| This Product Derived From CodeIgniter Software.
|--------------------------------------------------------------------------
|
| @version beta 1.0
| 
| PHP5 MVC Based Minimalist Software for PHP 5.1.2 or newer
| @see     license.txt
*/ 

define('DS',   DIRECTORY_SEPARATOR); 
/*
|---------------------------------------------------------------
| APPLICATION FOLDER NAME
|---------------------------------------------------------------
|
| If you want this front controller to use a different "application"
| folder then the default one you can set its name here. The folder 
| can also be renamed or relocated anywhere on your server.
| For more info please see the 
| User Guide: Chapters / General Topics / Managing Your Applications
|
*/
$application_folder = "application" .DS;
$base_folder        = "base" .DS;
                                                       
// --------------------------------------------------------------------                               

define('EXT',  '.php'); 
define('BASE', $base_folder);
define('FCPATH', __FILE__);
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('APP',  $application_folder);   
define('DIR',  APP .'directories'. DS);
 
require(BASE .'obullo'. DS .'Front_controller.php');
                        
/*
|--------------------------------------------------------------------------
| Custom User Front Controller
|--------------------------------------------------------------------------
|
| User can create own Front Controller who want extend
| and do method overridding for base OB_Front_Controller library
|
*/
require(APP .'system'. DS .'init'. DS .'Front_controller'.EXT);
                                          
$application = new Front_controller();
$application->run();        
  
// --------------------------------------------------------------------


/* Beta 1.0 Version Release Date: 01-01-2010 17:12:38 */
/* SVN $Id: Index.php 159 01-01-2010 17:12:38 develturk $ */

?>
