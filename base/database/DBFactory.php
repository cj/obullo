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
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
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

Class OB_DBFactory
{
    /**
    * Include database file
    * and build database params
    * 
    * @author   Ersin Güvenç
    * @version  0.1
    * @version  0.2 Added dsn connection, Added optional, required variables.
    * @return   object PDO
    */
    public static function init($param = '', $db_var = 'db', $ac = TRUE)
    {
        // check for PDO extension
        if ( ! extension_loaded('pdo') )
        {
            throw new DBFactoryException('The PDO extension is required but extension is not loaded !');
        }
        
        // Include active record files.
        self::_active_record($ac);
        
        return self::_factory($param, $db_var);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Active Record Factory
    * 
    * @author   Ersin Güvenç
    * @param    boolean $switch On/Off Active Record
    * @since    0.1
    * @since    0.2 Changed DBResults class as 'result'
    * @return   void
    */
    private static function _active_record($switch = TRUE)
    {
        if($switch) { $switch = db_item('active_record','system'); }
        /*
        if( ! class_exists('result'))
        {
            switch ($switch)
            {   
                 case TRUE:
                       require(BASE.'database'.DS.'DBResults'.EXT);
                       require(BASE.'database'.DS.'DBac_record'.EXT);
                       require(BASE.'database'.DS.'DBac_sw_on'.EXT);
                   break;
                   
                 case FALSE:
                       require(BASE.'database'.DS.'DBResults'.EXT);
                       require(BASE.'database'.DS.'DBac_sw_off'.EXT);
                   break;       
            }
        }
        
        if( ! class_exists('DB'))
        {
            require(BASE.'database'.DS.'DB'.EXT);
        }
     */
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Connect Requested PDO Driver
    * 
    * @author   Ersin Güvenç
    * @param    
    * @version  0.1
    * @version  0.2 added $db_var and $settings vars
    * @version  0.3 added $param var, set db paramaters manually for
    *           dynamic connections
    * @version  0.4 removed switch, added new driver classes
    * @return   void
    */
    private static function _factory($param, $db_var)
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
    
        $driver_class = 'OB_'.ucfirst($driver_name).'_DB_Driver';
         
        /*
        if( ! class_exists($driver_class)) 
        { 
            require(BASE. 'database' .DS. 'drivers' .DS. $driver_name .'_driver' .EXT); 
        }
        */
        $DB = new $driver_class($param, $db_var);
        $DB->_connect();
        
        return $DB->get_connection();
    
    } //end function.
    
    

} //end class.


/* End of file DBFactory.php */
/* Location: ./base/database/DBFactory.php */

?>
