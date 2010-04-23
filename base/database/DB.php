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
 // ------------------------------------------------------------------------
 
Class DBException extends CommonException {} 
 
/**
 * DB Class.
 *
 * Extending to PDO Class.
 *
 * @package         Obullo 
 * @subpackage      Base.database     
 * @category        Database
 * @version         0.1
 * @version         0.2 added active record class
 * @version         0.3 beta 1.0 rc1 changes ( direct query bug fixed ), added use_bindcolumn constant 
 */
 
Class OB_DB extends OB_DBAc_sw {
    
    public $prepare = FALSE;  // prepare switch
    public $p_opt = array();  // prepare options
    public $last_sql = '';    // store last queried sql 
    public $last_values;      // store last executed PDO values
    public $exec_count = 0;   // count executed func.
    
    public $use_bind_values = FALSE;    // bind value usage switch
    public $use_bind_params = FALSE;    // bind param usage switch
    
    private $Stmt = NULL;     // PDOStatement Object

    public $last_bind_values = array(); // Last bindValues and bindParams
    public $last_bind_params = array(); // We store binds values to array() 
                                        // because of we need it in last_query() function

    // Private variables
    public $_protect_identifiers    = TRUE;
    public $_reserved_identifiers   = array('*'); // Identifiers that should NOT be escaped
    
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
    public function pdo_connect($dsn, $user = NULL, $pass = NULL, $options = NULL)
    {
        //echo 'connected!'.$dsn;
        parent::__construct($dsn, $user, $pass, $options);
        
        return $this;
    }  
    
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
            $this->Stmt = parent::prepare($sql, $this->p_opt);
            
            ++$this->exec_count;
            
            return $this;   // beta 1.0 rc1 changes ( direct query bug fixed )
        }
        
        $this->Stmt = parent::query($sql);

        ++$this->exec_count;
        
        return $this;
    }                    
    
    // --------------------------------------------------------------------
        
    /**
     * Escape LIKE String
     *
     * Calls the individual driver for platform
     * specific escaping for LIKE conditions
     * 
     * @access   public
     * @param    string
     * @return   mixed
     */
    public function escape_like($str, $side = 'both')    
    {   
        return $this->escape_str($str, TRUE, $side);
    }
    
    // --------------------------------------------------------------------

    /**
    * "Smart" Escape String via PDO
    *
    * Escapes data based on type
    * Sets boolean and null types
    *
    * @access    public
    * @param     string
    * @return    mixed        
    */    
    public function escape($str)
    {
        switch (gettype($str))              
            {
               case 'string':
                 $str = $this->escape_str($str); 
                 break;
                 
               case 'integer':
                 $str = $this->quote($str, PDO::PARAM_INT);  
                 break;
                 
               case 'boolean':
                 $str = ($str === FALSE) ? 0 : 1;
                 break;
               
               case 'null':
                 $str = 'NULL';
                 break;
                 
               default:
                 $str = $this->escape_str($str);
            }

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
            if( ! self::_is_assoc($array))
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
        
        // if( ! self::_is_assoc($array)) $array = NULL;
        
        // if no query builded by active record
        // switch to pdo::statement
        $this->Stmt->execute($array);
        
        // reset prepare variable 
        $this->prepare = FALSE;
        
        ++$this->exec_count; 
        
        return $this;
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
        $this->last_sql = &$sql;
        
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
        if($prepared == TRUE AND self::_is_assoc($this->last_values))
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
        $this->Stmt->bindValue($param, $val, $type);
        
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
        $this->Stmt->bindParam($param, $val, $type, $length, $driver_options);  
        
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
        return $this->Stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // --------------------------------------------------------------------
     
    /**
    * Get results as object
    * 
    * @return  object
    */
    public function obj()
    {                                  
        return $this->Stmt->fetch(PDO::FETCH_OBJ);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Alias of $this-db->obj()
    * 
    * @return  object
    */
    public function row()
    {                                  
        return $this->Stmt->fetch(PDO::FETCH_OBJ);  
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get number of rows
    * 
    * @return  integer
    */
    public function num_rows()
    {    
        return $this->Stmt->rowCount();
    }     
    
    // --------------------------------------------------------------------
    
    /**
    * Get column names and numbers (both)
    * 
    * @return  mixed
    */
    public function both()
    {
        return $this->Stmt->fetch(PDO::FETCH_BOTH);
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
        return $this->Stmt->fetch($type);
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
        return $this->Stmt->fetchAll($type);
    } 
    
    // --------------------------------------------------------------------

    
    /**
    * Check array associative or not 
    * 
    * @access  private
    * @param   array $arr
    */
    private static function _is_assoc($arr)
    {
        if( ! is_array($arr)) return FALSE;
        
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
    
    // --------------------------------------------------------------------

    public function output()
    {
        return $this->last_sql;
    }
   
    // --------------------------------------------------------------------
    
    /**
    * Protect Identifiers
    *
    * This function adds backticks if appropriate based on db type
    *
    * @access   private
    * @param    mixed    the item to escape
    * @return   mixed    the item with backticks
    */
    private function protect_identifiers($item, $prefix_single = FALSE)
    {
        return $this->_protect_identifiers($item, $prefix_single);
    }

    // --------------------------------------------------------------------

    /**
    * Protect Identifiers
    *
    * This function is used extensively by the Active Record class, and by
    * a couple functions in this class. 
    * It takes a column or table name (optionally with an alias) and inserts
    * the table prefix onto it.  Some logic is necessary in order to deal with
    * column names that include the path.  Consider a query like this:
    *
    * SELECT * FROM hostname.database.table.column AS c FROM hostname.database.table
    *
    * Or a query with aliasing:
    *
    * SELECT m.member_id, m.member_name FROM members AS m
    *
    * Since the column name can include up to four segments (host, DB, table, column)
    * or also have an alias prefix, we need to do a bit of work to figure this out and
    * insert the table prefix (if it exists) in the proper position, and escape only
    * the correct identifiers.
    *
    * @access   private
    * @param    string
    * @param    bool
    * @param    mixed
    * @param    bool
    * @return   string
    */    
    function _protect_identifiers($item, $prefix_single = FALSE, $protect_identifiers = NULL, $field_exists = TRUE)
    {
        if ( ! is_bool($protect_identifiers))
        {
            $protect_identifiers = $this->_protect_identifiers;
        }

        if (is_array($item))
        {
            $escaped_array = array();

            foreach($item as $k => $v)
            {
                $escaped_array[$this->_protect_identifiers($k)] = $this->_protect_identifiers($v);
            }

            return $escaped_array;
        }

        // Convert tabs or multiple spaces into single spaces
        $item = preg_replace('/[\t ]+/', ' ', $item);
    
        // If the item has an alias declaration we remove it and set it aside.
        // Basically we remove everything to the right of the first space
        $alias = '';
        if (strpos($item, ' ') !== FALSE)
        {
            $alias = strstr($item, " ");
            $item = substr($item, 0, - strlen($alias));
        }

        // This is basically a bug fix for queries that use MAX, MIN, etc.
        // If a parenthesis is found we know that we do not need to 
        // escape the data or add a prefix.  There's probably a more graceful
        // way to deal with this, but I'm not thinking of it -- Rick
        if (strpos($item, '(') !== FALSE)
        {
            return $item.$alias;
        }

        // Break the string apart if it contains periods, then insert the table prefix
        // in the correct location, assuming the period doesn't indicate that we're dealing
        // with an alias. While we're at it, we will escape the components
        if (strpos($item, '.') !== FALSE)
        {
            $parts    = explode('.', $item);
            
            // Does the first segment of the exploded item match
            // one of the aliases previously identified?  If so,
            // we have nothing more to do other than escape the item
            if (in_array($parts[0], $this->ar_aliased_tables))
            {
                if ($protect_identifiers === TRUE)
                {
                    foreach ($parts as $key => $val)
                    {
                        if ( ! in_array($val, $this->_reserved_identifiers))
                        {
                            $parts[$key] = $this->_escape_identifiers($val);
                        }
                    }
                
                    $item = implode('.', $parts);
                }            
                return $item.$alias;
            }
            
            // Is there a table prefix defined in the config file?  If not, no need to do anything
            if ($this->dbprefix != '')
            {
                // We now add the table prefix based on some logic.
                // Do we have 4 segments (hostname.database.table.column)?
                // If so, we add the table prefix to the column name in the 3rd segment.
                if (isset($parts[3]))
                {
                    $i = 2;
                }
                // Do we have 3 segments (database.table.column)?
                // If so, we add the table prefix to the column name in 2nd position
                elseif (isset($parts[2]))
                {
                    $i = 1;
                }
                // Do we have 2 segments (table.column)?
                // If so, we add the table prefix to the column name in 1st segment
                else
                {
                    $i = 0;
                }
                
                // This flag is set when the supplied $item does not contain a field name.
                // This can happen when this function is being called from a JOIN.
                if ($field_exists == FALSE)
                {
                    $i++;
                }

                // Verify table prefix and replace if necessary
                if ($this->swap_pre != '' && strncmp($parts[$i], $this->swap_pre, strlen($this->swap_pre)) === 0)
                {
                    $parts[$i] = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->dbprefix."\\1", $parts[$i]);
                }
                                
                // We only add the table prefix if it does not already exist
                if (substr($parts[$i], 0, strlen($this->dbprefix)) != $this->dbprefix)
                {
                    $parts[$i] = $this->dbprefix.$parts[$i];
                }
                
                // Put the parts back together
                $item = implode('.', $parts);
            }
            
            if ($protect_identifiers === TRUE)
            {
                $item = $this->_escape_identifiers($item);
            }
            
            return $item.$alias;
        }

        // Is there a table prefix?  If not, no need to insert it
        if ($this->dbprefix != '')
        {
            // Verify table prefix and replace if necessary
            if ($this->swap_pre != '' && strncmp($item, $this->swap_pre, strlen($this->swap_pre)) === 0)
            {
                $item = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->dbprefix."\\1", $item);
            }

            // Do we prefix an item with no segments?
            if ($prefix_single == TRUE AND substr($item, 0, strlen($this->dbprefix)) != $this->dbprefix)
            {
                $item = $this->dbprefix.$item;
            }        
        }

        if ($protect_identifiers === TRUE AND ! in_array($item, $this->_reserved_identifiers))
        {
            $item = $this->_escape_identifiers($item);
        }
        
        return $item.$alias;
    }

 
} //end class.


/* End of file DB.php */
/* Location: .base/database/DB.php */

?>
