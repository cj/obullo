<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Minimalist Software for PHP 5.2.4 or newer
 * 
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.database        
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
 * @license         http://www.opensource.org/licenses/gpl-3.0.html GPL  
 * @since           Version 1.0
 * @filesource
 */ 
 
/**
 * DB Class.
 *
 * Extending PDO Class.
 *
 * @package         Obullo 
 * @subpackage      Base.database     
 * @category        Database
 * @version         0.1
 */
 
Class DBException extends CommonException {}  

// predefined db constants
define('assoc','assoc'); 
define('obj','obj');
define('bound','bound');
define('col','col');
define('num','num');

// paramater types
define('p_int','int');
define('p_str','str');
define('p_bool','bool');

Class DB_Results
{
    // Factory fetch type
    static function fetch($type='')
    {
        switch ($type)
        {                      
            case 'assoc':
            return PDO::FETCH_ASSOC;
            break;

            case 'obj':
            return PDO::FETCH_OBJ;
            break;
            
            //get column names and column nubers togetger
            case 'bound':
            return PDO::FETCH_BOUND;
            break;
            
            //get just column info
            case 'col':
            return PDO::FETCH_COLUMN;
            break;
            
            case 'num':
            return PDO::FETCH_NUM;
            break;

            default:
            return PDO::FETCH_ASSOC; 
        }   
    } //end fecth.
    
    
    static function type($type='')
    {
        switch ($type)
        {                      
            // integer
            case 'int':
            return PDO::PARAM_INT;
            break;

            // string
            case 'str':
            return PDO::PARAM_STR;
            break;
            
            // boolean
            case 'bool':
            return PDO::PARAM_BOOL;
            break;
            
            default:
            return PDO::PARAM_STR; 
        }   
    }
    
    
}  //end class.


Class OB_DB_active_record extends PDO {

    /**
    * 
    * @todo code igniter query builder functions 
    * we will implement it later.
    */
    function select(){}
    function from(){}
    
}

Class DB extends OB_DB_active_record
{
    
    /**
    * Parent Query pdo query result
    * 
    * @var object
    */
    private $PQ = '';

    /**
    * sql string
    * 
    * @var string
    */
    private $sql = '';
    
    /**
    * prepare switch
    * 
    * @var boolean
    */
    private $prepare = FALSE;
    
    /**
    * Prepare options
    * 
    * @var mixed
    */
    private $p_opt = array();
    
    
    function __construct($dsn, $user = NULL, $pass = NULL, $options = NULL)
    {
        parent::__construct($dsn, $user, $pass, $options);
        
        //throw new DBException('SQLSTATE: test error!');
    } 
    
    // PDO prepare function.
    function prep($options = array())
    {
        $this->p_opt = $options;
        $this->prepare = TRUE;
    }
    
    // Direct or prepared query
    function query($sql)
    {   
        if($this->prepare)
        {
            $this->PQ = parent::prepare($sql,$this->p_opt); 
            return NULL;
        }
        
        $this->PQ = parent::query($sql);
        $this->sql = $sql;

        return $this;
    }                    
        
    // Execute prepared query
    function exec($array = NULL)
    {
        $this->PQ->execute($array);
        
        // reset prepare variable 
        $this->prepare = FALSE;
        
        return NULL;
    }
        
    // Alias of PDO_Statement::bindVal()
    function bval($param,$val,$type='')
    {
        $this->PQ->bindValue($param,$val,DB_Results::type($type));  
    }    
        
    // Get available drivers on your host
    function drivers()
    {
        foreach(PDO::getAvailableDrivers() as $driver)
        {
            echo $driver.'<br />';
        }  
    }
    
    // Get results as associative array
    function assoc()
    {
        $assoc = $this->PQ->fetch(PDO::FETCH_ASSOC);
        return $assoc;
    }
    
    // Get result as object
    function obj()
    {                                  
        $object = $this->PQ->fetch(PDO::FETCH_OBJ);
        return $object;
    }
    
    // Same as object
    function row()
    {                                  
        $object = $this->PQ->fetch(PDO::FETCH_OBJ);
        return $object;
    }
    
    // Get all results by assoc, object or what u want
    function all($type = NULL)
    {    
        $constant = DB_Results::fetch($type);
        $all = $this->PQ->fetchAll($constant);
        
        return $all;
    } 
    
    // Get column numbers, results in assoc
    function num()
    {    
        $num = $this->PQ->fetch(PDO::FETCH_NUM);
        return $num;
    } 
    
    // Number of rows
    function num_rows()
    {    
        $num = $this->PQ->rowCount();
        return $num;
    }     
    
    // Get column names and numbers (both)
    function both()
    {
        $both = $this->PQ->fetch(PDO::FETCH_BOTH);
        return $both; 
    } 

    // CRUD OPERATIONS not implemented YET.
    function update(){}
    
    // http://tr.php.net/manual/en/pdostatement.bindvalue.php
    function insert(){}
    
    // PDO exec() functionuna bak affected rows a otomatik dönüyor.
    function delete(){}
    
    function last_id(){}

 
} //end class.
 
?>
