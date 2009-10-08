<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Framework software for PHP 5.2.4 or newer
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.database        
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
 * @license         http://www.opensource.org/licenses/gpl-3.0.html GPL  
 * @since           Version 1.0 @alpha
 * @filesource
 */ 
 
/**
 * OB_DBFactory Class
 *
 * Factory PDO drivers.
 *
 * @package         Obullo 
 * @subpackage      Base.database     
 * @category        Database
 * @version         0.1
 */                 

Class DBFactoryException extends CommonException {}

Class OB_DBFactory
{
    public static function Connect()
    {                                       
        include(APP.'config/database'.EXT); 
    
        extract($db['default']); 
    
        if(empty($dbdriver))
        throw new DBFactoryException('Please set a valid DB driver from config database file!');
    
        $dbdriver = strtoupper($dbdriver);
    
        switch ($dbdriver) {
           
           // FreeTDS / Microsoft SQL Server / Sybase
           case 'DBLIB':  
             $dbh = new DB("dblib:host=$hostname:$dbh_port;dbname=$database;charset=$char_set","$username","$password");
             break;
             
           // Firebird/Interbase 6   
           case 'FIREBIRD':
             $dbh = new DB("firebird:dbname=$database","$username", "$password");
             break;
           
           // IBM DB2  
           case 'IBM': 
             $dbh = new DB("ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=$database;"."HOSTNAME=$hostname;PORT=$dbh_port;PROTOCOL=TCPIP;", "$username", "$password");
             break;
           
           // IBM Informix Dynamic Server  
           case 'INFORMIX':    
             $dbh = new DB("informix:host=$hostname; service=9800;
             database=$database; server=ids_server; protocol=onsoctcp;EnableScrollableCursors=1", "$username", "$password");
             break; 
           
           // MySQL 3.x/4.x/5.x  
           case 'MYSQL':
             // array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $char_set")
             $dbh = new DB("mysql:host=$hostname;dbname=$database","$username","$password");
             $dbh->query("SET NAMES utf8");
             break;
           
           // Oracle Call Interface  
           case 'OCI':
             $dbh = new DB("oci:dbname=$hostname/$database;charset=$char_set", "$username", "$password");
             break;
           
           // ODBC v3 (IBM DB2, unixODBC and win32 ODBC) 
           case 'ODBC': 
             $dbh = new DB("odbc:Driver={SQL Native Client};Server=$hostname;Database=$database;Uid=$username;Pwd=$password;");
             break;
             
           // PostgreSQL  
           case 'PGSQL':
             $dbh = new DB("pgsql:dbname=$database;user=$username;password=$password;host=$hostname");
             break;
             
           // SQLite 3 and SQLite 2
           case 'SQLITE':
             $dbh = new DB("sqlite:$database"); 
             break;
             
           // 4D
           case '4D':
             $dbh = new DB("4D:host=$hostname;charset=$char_set","$username","$password");
             break;
           
          default:
          throw new DBFactoryException('This DB Driver does not support!'); 
           
        } //end switch.
    
        // We set exception attribute for showing the pdo exceptions errors 
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $dbh->set_driver($dbdriver);
        
        return $dbh;
    
    } //end function.
    
} //end class.


?>
