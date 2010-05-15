<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.database        
 * @copyright       Copyright (c) 2009 Ersin Guvenc.
 * @license         public 
 * @since           Version 1.0 @alpha 2
 * @filesource
 */ 
 
Class DBFactoryException extends CommonException {}
 
/**
 * DBFactory Class
 *
 * Factory PDO drivers.
 *
 * @package         Obullo 
 * @subpackage      Base.database     
 * @category        Database
 * @version         0.1
 * @version         0.2 added construct(), active_record() functions
 * @version         0.3 added multiple database connection, dsn connection.
 * @version         0.4 added driver file support.
 */                 
 
Class OB_DBFactory {

    /**
    * Check pdo extension
    * and build database params
    * 
    * @author   Ersin Guvenc
    * @version  0.1
    * @version  0.2 Added dsn connection, Added optional, required variables.
    * @return   object PDO
    */
    public static function init($param = '', $db_var = 'db', $ac = NULL)
    {
        // check for PDO extension
        if ( ! extension_loaded('pdo') )
        {
            throw new DBFactoryException('The PDO extension is required but extension is not loaded !');
        }
        
        return self::_factory($param, $db_var, $ac);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Connect Requested PDO Driver
    * 
    * @author   Ersin Guvenc
    * @param    
    * @version  0.1
    * @version  0.2 added $db_var and $settings vars
    * @version  0.3 added $param var, set db paramaters manually for
    *           dynamic connections
    * @version  0.4 removed switch, added new driver classes
    * @return   object of PDO Instance.
    */
    private static function _factory($param, $db_var, $ac = NULL)
    {                          
        $dbdriver = is_array($param) ? $param['dbdriver'] : db_item('dbdriver', $db_var); 
        
        $driver_name = '';
                     
        switch (strtolower($dbdriver))
        {   
           // MySQL 3.x/4.x/5.x  
           case ($dbdriver == 'mysql'): 
           $driver_name = 'mysql';
             break;
             
           // IBM - DB2 / Informix not yet ..
           case ($dbdriver == 'ibm' || $dbdriver == 'db2'):
           $driver_name = 'ibm';
             break;
             
           // MSSQL / DBLIB / FREETDS / SYBASE
           case ($dbdriver == 'dblib' || $dbdriver == 'mssql' || $dbdriver == 'freetds' || $dbdriver == 'sybase'):
           $driver_name = 'mssql';
             break;
           
           // OCI (ORACLE)
           case ($dbdriver == 'oci' || $dbdriver == 'oracle'):
           $driver_name = 'oci';
             break;
             
           // ODBC
           case ($dbdriver == 'odbc'):
           $driver_name = 'odbc';
             break;
             
           // PGSQL
           case ($dbdriver == 'pgsql'):
           $driver_name = 'pgsql';
             break;
             
           // SQLITE
           case ($dbdriver == 'sqlite' || $dbdriver == 'sqlite2' || $dbdriver == 'sqlite3'):
           $driver_name = 'sqlite';
             break;
             
           // Firebird
           case 'firebird':
           $driver_name = 'firebird';
             break;
             
           // 4D
           case '4d':
           $driver_name = '4d';
             break;
           
          default:
          throw new DBFactoryException('This Database Driver does not support: '. $dbdriver); 
           
        } //end switch.
        
        $active_record = is_bool($ac) ? $ac : db_item('active_record','system');
        
        // ugly hack but we need it ..
        if($active_record) 
        {
            eval('Class OB_DBAc_sw extends OB_DBAc_record {}');
        } 
        else 
        {
            eval('Class OB_DBAc_sw extends PDO {}');
        }
            
        $driver_class = 'Obullo_DB_Driver_'.ucfirst($driver_name);
        
        $DB = new $driver_class($param, $db_var); 
        $DB->_connect();
        
        return $DB->get_connection();
    
    }
    
}

/* End of file DBFactory.php */
/* Location: ./base/database/DBFactory.php */
?>