<?php
defined('BASE') or exit('Access Denied!'); 

/*
|--------------------------------------------------------------------------
| Global Active Record Switch
|--------------------------------------------------------------------------
*/
$database['system']['active_record'] = FALSE;

/*
|--------------------------------------------------------------------------
| Database Settings
|--------------------------------------------------------------------------
| Put your static database configurations here and decide your db variable
| name. You will use it in the application like this $this->db .
| 
| db variable name ( default $this->db )
|     ------------
|             |
| $database['db']['...'] 
| 
*/
$database['db']['hostname'] = "localhost";
$database['db']['username'] = "root";
$database['db']['password'] = "";
$database['db']['database'] = "example_db";
$database['db']['dbdriver'] = "mysql";
$database['db']['dbh_port'] = "";
$database['db']['char_set'] = "utf8";
$database['db']['dsn']      = "";
$database['db']['options']  = array();  // Example:  array( PDO::ATTR_PERSISTENT => false , 
                                        //                  PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true 
                                        //           ); 

// second database
$database['db2']['hostname'] = "localhost";
$database['db2']['username'] = "root";
$database['db2']['password'] = "";
$database['db2']['database'] = "obullo";
$database['db2']['dbdriver'] = "mysql";
$database['db2']['dbh_port'] = "";
$database['db2']['char_set'] = "utf8";
$database['db2']['dsn']      = "";
$database['db2']['options']  = array();

/* End of file database.php */
/* Location: ./application/config/database.php */