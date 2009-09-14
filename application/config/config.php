<?php

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Framework software for PHP 5.2.4 or newer
 *
 * @package         obullo
 * @filename        application/config/config.php        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */

/*
---------------------------------------------------------------------------
| BASE URL
---------------------------------------------------------------------------
| Your web site url can't be empty.
--------------------------------------------------------------------------- 
*/
 
$config['base_url'] = 'http://localhost/';

/*
---------------------------------------------------------------------------
| START CONTROLLER
---------------------------------------------------------------------------
| Default start controller.When user request from 
| web site like mysite.com/ start controller 
| automatically will open
--------------------------------------------------------------------------- 
*/ 

$config['start_controller'] = 'hello';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Sessions class with encryption
| enabled you MUST set an encryption key.  See the user manual for info.
|
*/
$config['encryption_key'] = "";

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'session_cookie_name' = the name you want for the cookie
| 'encrypt_sess_cookie' = TRUE/FALSE (boolean).  Whether to encrypt the cookie
| 'session_expiration'  = the number of SECONDS you want the session to last.
|  by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'time_to_update'        = how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']     = 'ob_session';
$config['sess_expiration']      = 7200;
$config['sess_encrypt_cookie']  = FALSE;
$config['sess_use_database']    = FALSE;
$config['sess_table_name']      = 'ob_sessions';
$config['sess_match_ip']        = FALSE;
$config['sess_match_useragent'] = TRUE;
$config['sess_time_to_update']  = 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
|
*/
$config['cookie_prefix']    = "";
$config['cookie_domain']    = "";
$config['cookie_path']        = "/";


?>
