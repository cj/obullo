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
 * @version         0.4 added __sleep and __wakeup magic functions.added required , optional variables.
 */                 

Class DBFactory
{
    /**
    * Include Active Record Class
    * or not
    * 
    * @var boolean
    */
    private $active_record = TRUE;
    
    /**
    * Active db name
    * 
    * @var string
    */
    private $db_var  = 'db';

    /**
    * Database Settings.
    */
    private $hostname = '';
    private $username = '';
    private $password = '';
    private $database = '';
    private $dbdriver = '';
    private $char_set = '';
    private $dbh_port = '';
    private $dsn      = '';
    private $options  = array(); 
    
    /**
    * Include database file
    * and build database params
    * 
    * @author   Ersin Güvenç
    * @version  0.1
    * @version  0.2 Added dsn connection, Added optional, required variables.
    * @return   void
    */
    public function __construct($param = '', $db_var, $ac = TRUE)
    {
        // check for PDO extension
        if ( ! extension_loaded('pdo') )
        {
            throw new DBFactoryException('The PDO extension is required but extension is not loaded !');
        }
        
        $this->active_record = db_item('active_record','system');
        $this->db_var = &$db_var;
    
        // Set Database Variables
        // Dynamic Connection
        if(is_array($param))
        {
            // Dsn Connection..
            if( ! empty($param['dsn']) ) 
            {
                $this->dbdriver = strtoupper($param['dbdriver']);  // required
                $this->char_set = isset($param['char_set']) ? $param['char_set'] : '';    // optional
                $this->dsn      = $param['dsn'];  // required
                $this->options  = isset($param['options']) ? $param['options'] : array(); // optional
                
            } else 
            {
            // Standart Connection..
                $this->hostname = $param['hostname'];  // required
                $this->username = $param['username'];  // required
                $this->password = $param['password'];  // required
                $this->database = $param['database'];  // required
                $this->dbdriver = strtoupper($param['dbdriver']); // required
                $this->char_set = isset($param['char_set']) ? $param['char_set'] : '';    // optional
                $this->dbh_port = isset($param['dbh_port']) ? $param['dbh_port'] : '';    // optional
                $this->options  = isset($param['options']) ? $param['options'] : array(); // optional
            }
               
        } else 
        {
            // Config.database connection
            $this->hostname = db_item('hostname',$this->db_var); 
            $this->username = db_item('username',$this->db_var); 
            $this->password = db_item('password',$this->db_var); 
            $this->database = db_item('database',$this->db_var);
            $this->dbdriver = strtoupper(db_item('dbdriver',$this->db_var)); 
            $this->char_set = db_item('char_set',$this->db_var);
            $this->dbh_port = db_item('dbh_port',$this->db_var);
            $this->dsn      = db_item('dsn',$this->db_var);
            $this->options  = db_item('options',$this->db_var);
        }  
        
        if( ! is_array($this->options) ) $this->options = array();
        
        ob::instance()->{$this->db_var} = NULL;
        
        // Include active record files.
        $this->_active_record($ac);
        $this->_connect();
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
    private function _active_record($switch = TRUE)
    {
        if($switch) { $switch = $this->active_record; }
        
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
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Connect Requested PDO Drivers
    * 
    * @author   Ersin Güvenç
    * @param    
    * @version  0.1
    * @version  0.2 added $db_var and $settings vars
    * @version  0.3 added $param var, set db paramaters manually for
    *           dynamic connections
    * @return   void
    */
    private function _connect()
    {                                            
        switch ($this->dbdriver)
        {   
           // FreeTDS / Microsoft SQL Server / Sybase
           case 'DBLIB':
             $port    = empty($this->dbh_port) ? '' : ':'.$this->dbh_port.';';
             $charset = empty($this->char_set) ? '' : ';charset='.$this->char_set;
             $dsn     = empty($this->dsn) ? 'dblib:host='.$this->hostname.';'.$port.'dbname='.$this->database.$charset : $this->dsn;
             
             ob::instance()->{$this->db_var} = new DB($dsn, $this->username, $this->password, $this->options);
             break;
           
           // MSSQL (Alias of DBLIB)
           case 'MSSQL':
             $port    = empty($this->dbh_port) ? '' : ':'.$this->dbh_port.';';
             $charset = empty($this->char_set) ? '' : ';charset='.$this->char_set;
             $dsn     = empty($this->dsn) ? 'dblib:host='.$this->hostname.';'.$port.'dbname='.$this->database.$charset : $this->dsn;
             
             ob::instance()->{$this->db_var} = new DB($dsn, $this->username, $this->password, $this->options);
             break;
             
           // Firebird/Interbase 6   
           case 'FIREBIRD':
             $dsn = empty($this->dsn) ? 'firebird:dbname='.$this->database : $this->dsn;

             ob::instance()->{$this->db_var} = new DB($dsn, $this->username, $this->password, $this->options);
             break;
           
           // IBM DB2  
           case 'IBM':
             $port = empty($this->dbh_port) ? '' : 'PORT='.$this->dbh_port.';';
             $dsn  = empty($this->dsn) ? 'ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE='.$this->database.';HOSTNAME='.$this->hostname.';'.$port.'PROTOCOL=TCPIP;' : $this->dsn;
             
             ob::instance()->{$this->db_var} = new DB($dsn, $this->username, $this->password, $this->options);
             break;
           
           // IBM Informix Dynamic Server  
           case 'INFORMIX':    
             if( empty($this->dsn) ) throw new DBFactoryException('Please provide dsn for INFORMIX connection.');
           
             ob::instance()->{$this->db_var} = new DB($this->dsn);
             break; 
           
           // MySQL 3.x/4.x/5.x  
           case 'MYSQL':
             $port = empty($this->dbh_port) ? '' : 'port:'.$this->dbh_port.';';
             $dsn  = empty($this->dsn) ? 'mysql:host='.$this->hostname.';'.$port.'dbname='.$this->database : $this->dsn;

             // array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $this->char_set") it occurs an error !
             ob::instance()->{$this->db_var}  = new DB($dsn, $this->username, $this->password, $this->options);
             
             if( ! empty($this->char_set) )
             ob::instance()->{$this->db_var}->query("SET NAMES '" . $this->char_set . "'");
             
             break;
           
           // Oracle Call Interface  
           case 'OCI':
             $port    = empty($this->dbh_port) ? '' : ':'.$this->dbh_port;
             $charset = empty($this->char_set) ? '' : ';charset='.$this->char_set; 
             $dsn     = empty($this->dsn) ? 'oci:dbname='.$this->hostname.$port.'/'.$this->database.$charset : $this->dsn;
             
             ob::instance()->{$this->db_var} = new DB($dsn, $this->username, $this->password, $this->options);
             break;
           
           // ODBC v3 (IBM DB2, unixODBC and win32 ODBC) 
           case 'ODBC': 
             if( empty($this->dsn) ) throw new DBFactoryException('Please provide dsn for ODBC connection.');
             
             // If you specify username or password in the DSN, PDO ignores the value of the password
             // or username arguments in the PDO constructor. (ersin)
             // @see http://www.php.net/manual/en/ref.pdo-odbc.connection.php
             ob::instance()->{$this->db_var} = new DB($this->dsn, NULL, NULL, $this->options);
             break;
             
           // PostgreSQL  
           case 'PGSQL':
             $port = empty($this->dbh_port) ? '' : 'port='.$this->dbh_port;
             $dsn  = empty($this->dsn) ? 'pgsql:dbname='.$this->database.' user='.$this->username.' password='.$this->password.' host='.$this->hostname.' '.$port : $this->dsn;
             
             ob::instance()->{$this->db_var}  = new DB($dsn, NULL,NULL, $this->options);
             
             if( ! empty($this->char_set) )
             ob::instance()->{$this->db_var}->query("SET NAMES '" . $this->char_set . "'");
             break;
             
           // SQLite 3 and SQLite 2
           case 'SQLITE':
             $dsn  = empty($this->dsn) ? 'sqlite:'.$this->database : $this->dsn;
           
             ob::instance()->{$this->db_var}  = new DB($dsn, NULL, NULL, $this->options); 
             break;
             
           // 4D
           case '4D':
             $port    = empty($this->dbh_port) ? '' : ':'.$this->dbh_port.';';
             $charset = empty($this->char_set) ? '' : ';charset='.$this->char_set;
             $dsn     = empty($this->dsn) ? '4D:host='.$this->hostname.$port.$charset : $this->dsn;
             
             ob::instance()->{$this->db_var} = new DB($dsn, $this->username, $this->password, $this->options);
             break;
           
          default:
          throw new DBFactoryException('This DB Driver does not installed on your server: '. $this->dbdriver); 
           
        } //end switch.
    
        // We set exception attribute for always showing the pdo exceptions errors. (ersin)
        ob::instance()->{$this->db_var}->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        ob::instance()->{$this->db_var}->set_driver($this->dbdriver);
    
    } //end function.
    
    
    // --------------------------------------------------------------------

    /**
    * Called when object is getting serialized
    * This disconnects the DB object that can't be serialized
    *
    * @return array
    */
    public function __sleep()
    {
        return array('hostname', 'username', 'password', 'database',
        'dbdriver', 'char_set', 'dbh_port', 'dsn', 'options', 'active_record', 'db_var');
    }

    
    // --------------------------------------------------------------------
    
    /**
    * Called when object is getting unserialized
    *
    * @return void
    */
    public function __wakeup()
    {
        $this->_connect();
    }
    

} //end class.


/* End of file DBFactory.php */
/* Location: ./base/database/DBFactory.php */

?>
