<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 * 
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.database        
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
 * @license         public
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
 * @version         0.2 added active record class
 */

define('use_bind_value', 'bind_value'); // Bind Value
define('use_bind_param', 'bind_param'); // Bind Param
 
// --------------------  DB Classes load schema ----------------------
/*  
 include DBAC_Adapter.php
 
 DBAC_Adapter::AC($active_record = true); 
      o include DBResults.php
      o include DBac_record.php
      o include DBac_sw_on.php

 DBAC_Adapter::AC($active_record = false);
      o include DBResults.php
      o include DBac_sw_off.php

 include DB.php
 include DBFactory.php
*/
// --------------------------------------------------------------------

Class DB extends DBAC_Switch
{
    /**
    * prepare switch
    * 
    * @var boolean
    */
    public $prepare = FALSE;
    
    /**
    * Prepare options
    * 
    * @var mixed
    */
    public $p_opt = array();
    
    /**
    * Store last queried sql
    * 
    * @var string
    */
    public $last_sql = '';
    
    /**
    * Store last PDO execute 
    * values
    * 
    * @var array
    */
    public $last_values;
    
    /**
    * Parent Query - PDOStatement Object
    * 
    * @var object
    */
    private $PQ = '';

    /**
    * Count execute func.
    * 
    * @var int
    */
    public $exec_count = 0;
    
    /**
    * Bind value usage switch
    * 
    * @var boolean
    */
    public $use_bind_values = FALSE;
    
    /**
    * Bind params usage switch
    * 
    * @var boolean
    */
    public $use_bind_params = FALSE;
    
    /**
    * Last bindValues and bindParams
    * We store binds values to array()
    * because of we need it in last_query() 
    * function.
    * 
    * @var array
    */
    public $last_bind_values = array();
    public $last_bind_params = array();
    
    // --------------------------------------------------------------------
    
    /**
    * Connect to PDO
    * 
    * @author   Ersin Güvenç 
    * @param    string $dsn  Dsn
    * @param    string $user Db username
    * @param    mixed  $pass Db password
    * @param    array  $options Db Driver options
    * @return   void
    */
    public function __construct($dsn, $user = NULL, $pass = NULL, $options = NULL)
    {
        parent::__construct($dsn, $user, $pass, $options);
    } 
    
    // --------------------------------------------------------------------
    
    /**
    * Set PDO native Prepare() function
    * 
    * @author   Ersin Güvenç 
    * @param    array $options prepare options
    */
    public function prep($options = array())
    {
        $this->p_opt   = &$options;
        $this->prepare = TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Flexible Prepared or Direct Query
    *         
    * @author  Ersin Güvenç
    * @param   string $sql
    * @version 1.0
    * @version 1.1  added $this->exec_count
    * @return  object PDOStatement
    */
    public function query($sql = NULL)
    {   
        $this->last_sql = $sql;
        
        if($this->prepare)
        {
            $this->PQ = parent::prepare($sql, $this->p_opt);
            
            ++$this->exec_count;
            
            return NULL;
        }
        
        $this->PQ = parent::query($sql);

        ++$this->exec_count;
        
        return $this;
    }                    
    
    // --------------------------------------------------------------------
        
    /**
    * Escape 'like' strings for security
    * 
    * @access private
    * @param  mixed $str
    * @return mixed
    */
    public function escape_like($str)
    {
        $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str); 
        
        return $str;
    }
    
    // --------------------------------------------------------------------
        
    /**
    * Execute prepared query
    * 
    * @author   Ersin Güvenç
    * @version  0.1
    * @version  0.2    added secure like conditions support
    * @version  0.3    changed bindValue functionality
    * @param    array  $array bindValue or bindParam arrays
    * @param    string $bval_or_bparam
    * @return   void | NULL 
    */
    public function exec($array = NULL, $bval_or_bparam = '')
    { 
        $this->last_values = &$array; // store last executed bind values.
        
        if($this->use_bind_values)
        {
           $this->last_values = &$this->last_bind_values;
            
        } elseif($this->use_bind_params)
        {
           $this->last_values = &$this->last_bind_params;
        }        
        
        // this is just for prepared direct queries with bindValues or bindParams..
        if($this->last_sql != NULL AND $this->exec_count == 0)
        {
            $this->query($this->last_sql);
        }
    
        //print_r($array); exit;
    
        if(is_array($array) AND $bval_or_bparam != '')
        {
            if( ! self::isAssoc($array))
            throw new DBException('PDO binds data must be associative array !');
            
            switch ($bval_or_bparam)
            {
               case 'bind_param':
                 $this->_bindParams($array);
                 break;
                 
               case 'bind_value':
                 $this->_bindValues($array);
                 break;
            }
            
            $array = NULL;
        }
        
        // if( ! self::isAssoc($array)) $array = NULL;
        
        // if no query builded by active record
        // switch to pdo::statement
        $this->PQ->execute($array);
        
        // reset prepare variable 
        $this->prepare = FALSE;
        
        ++$this->exec_count; 
        
        return NULL;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Exec just for INSERT and 
    * UPDATE operations it returns to
    * number of affected rows after the write
    * operations.
    * 
    * @author   Ersin Güvenç
    * @param    string $sql
    * @version  0.1
    * @return   boolean
    */
    public function exec_query($sql)
    {
        $this->last_sql = $sql;
        
        return parent::exec($sql);
    }
        
    // --------------------------------------------------------------------
    
    /**
    * Automatically secure bind values..
    * 
    * @param    mixed $array
    * @return   void 
    */
    private function _bindValues($array)
    {
        foreach($array as $key => $val)
        {                                          
            switch (gettype($val))
            {
               case 'string':
               //echo 'string'; 
                 $this->bind_value($key, $val, PDO::PARAM_STR);
                 break;
                 
               case 'integer':
                 $this->bind_value($key, $val, PDO::PARAM_INT);
                 break;
                 
               case 'boolean':
               //echo 'BOOL';
                 $this->bind_value($key, $val, PDO::PARAM_BOOL);
                 break;
               
               case 'null':
                 $this->bind_value($key, $val, PDO::PARAM_NULL);
                 break;
                 
               default:
                 $this->bind_value($key, $val, PDO::PARAM_STR);
            }
        }
    }

    // --------------------------------------------------------------------
    
    /**
    * Automatically secure bind params..
    * 
    * @param    mixed $array
    * @return   void
    */
    private function _bindParams($array)
    {
        foreach($array as $key => $val)
        {                                          
            switch (gettype($val))              
            {
               case 'string':
                 $this->bind_param($key, $val, PDO::PARAM_STR);
                 break;
                 
               case 'integer':
                 $this->bind_param($key, $val, PDO::PARAM_INT);
                 break;
                 
               case 'boolean':
                 $this->bind_param($key, $val, PDO::PARAM_BOOL);
                 break;
               
               case 'null':
                 $this->bind_param($key, $val, PDO::PARAM_NULL);
                 break;
                 
               default:
                 $this->bind_param($key, $val, PDO::PARAM_STR);
            }
        }
    }
    
    // --------------------------------------------------------------------    
    
    /**                               
    * Fetch prepared or none prepared last_query
    * 
    * @author   Ersin Güvenç
    * @version  0.1
    * @version  0.2 added prepared param
    * @param    boolean $prepared
    * @return   string
    */
    public function last_query($prepared = FALSE)
    {   
        if($prepared == TRUE AND self::isAssoc($this->last_values))
        {                                  
            $quote_added_vals = array();
            foreach(array_values($this->last_values) as $q)
            {
                $quote_added_vals[] = PDO::quote($q); // "'".$q."'"; 
            }
        
            return str_replace(array_keys($this->last_values), $quote_added_vals, $this->last_sql);
        }
            
        return $this->last_sql;
    }                 
    
    // --------------------------------------------------------------------
    
    /**
    * PDO Last Insert Id
    * 
    * @return  object PDO::Statement 
    */
    public function insert_id()
    {
        return parent::lastInsertId();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Alias of PDO_Statement::bindValue()
    * 
    * @param   string $param
    * @param   mixed $val
    * @param   string $type PDO FETCH CONSTANT
    */
    public function bind_value($param, $val, $type)
    {
        $this->PQ->bindValue($param, $val, $type);
        
        $this->use_bind_values = TRUE;
        $this->last_bind_values[$param] = $val;
    }
    
    // ------------------------------------------------------------------
    
    /**
    * Alias of PDO_Statement::bindParam()
    * 
    * @param   mixed $param
    * @param   mixed $val
    * @param   mixed $type  PDO FETCH CONSTANT
    * @param   mixed $length
    * @param   mixed $driver_options
    */
    public function bind_param($param, $val, $type, $length = NULL, $driver_options = NULL)
    {
        $this->PQ->bindParam($param, $val, $type, $length, $driver_options);  
        
        $this->use_bind_params = TRUE;
        $this->last_bind_params[$param] = $val;
    }        
        
    // --------------------------------------------------------------------
        
    /**
    * Get available drivers on your host
    * 
    * @return  object PDO::Statement
    */
    public function drivers()
    {
        return PDO::getAvailableDrivers();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get results as associative array
    * 
    * @return  array
    */
    public function assoc()
    {
        return $this->PQ->fetch(PDO::FETCH_ASSOC);
    }
    
    // --------------------------------------------------------------------
     
    /**
    * Get results as object
    * 
    * @return  object
    */
    public function obj()
    {                                  
        return $this->PQ->fetch(PDO::FETCH_OBJ);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Alias of $this-db->obj()
    * 
    * @return  object
    */
    public function row()
    {                                  
        return $this->PQ->fetch(PDO::FETCH_OBJ);  
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get number of rows
    * 
    * @return  integer
    */
    public function num_rows()
    {    
        return $this->PQ->rowCount();
    }     
    
    // --------------------------------------------------------------------
    
    /**
    * Get column names and numbers (both)
    * 
    * @return  mixed
    */
    public function both()
    {
        return $this->PQ->fetch(PDO::FETCH_BOTH);
    } 
    
    // --------------------------------------------------------------------
    
    /**
    * Native PDOStatement::fetch() 
    * function
    * 
    * @return  mixed
    */
    public function fetch($type = NULL)
    {
        return $this->PQ->fetch($type);
    } 
    
    // --------------------------------------------------------------------

    /**
    * Get "all results" by assoc, object, num, bound or 
    * anything what u want
    * 
    * @param    string $type (constant)
    * @return   mixed
    */
    public function fetch_all($type = NULL)
    {    
        return $this->PQ->fetchAll($type);
    } 
    
    // --------------------------------------------------------------------
    
    /**
    * Check array associative or not 
    * 
    * @access  private
    * @param   array $arr
    */
    private static function isAssoc($arr)
    {
        if( ! is_array($arr)) return FALSE;
        
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
    
 
} //end class.


/* End of file DB.php */
/* Location: .base/database/DB.php */

?>
