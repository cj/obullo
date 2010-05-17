<?php
if( !defined('BASE') ) exit('Access Denied!');                 
/*
|--------------------------------------------------------------------------
| Datanase Constants
|--------------------------------------------------------------------------
|
| These prefs are used when working with query binding functions.
|
*/
define('use_bind_value', 'bind_value');  // Bind Value

/**
|--------------------------------------------------------------------------
| Datanase Result Constants
|--------------------------------------------------------------------------
| PDO paramater type constants
| @link http://php.net/manual/en/pdo.constants.php

| These prefs are used when working with query result class.
|
*/
define('param_bool', PDO::PARAM_BOOL); // boolean
define('param_null', PDO::PARAM_NULL); // null
define('param_int' , PDO::PARAM_INT);  // integer
define('param_str' , PDO::PARAM_STR);  // string
define('param_lob' , PDO::PARAM_LOB);  // integer  Large Object Data (lob)
define('param_stmt', PDO::PARAM_STMT); // integer 
                                       // Represents a recordset type. Not currently supported by any drivers. 
                                        
define('param_inout' , PDO::PARAM_INPUT_OUTPUT); // integer

/* End of file db.php */
/* Location: ./base/constants/db.php */
?>