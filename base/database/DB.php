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
 * @copyright       Copyright (c) 2009 Ersin Guvenc.
 * @license         public
 * @since           Version 1.0
 * @filesource
 */
 // ------------------------------------------------------------------------

Class DBException extends CommonException {}

function ob_query_timer_start()
{
    list($sm, $ss) = explode(' ', microtime());

    return ($sm + $ss);
}

function ob_query_timer_end()
{
    list($em, $es) = explode(' ', microtime());

    return ($em + $es);
}

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
 * @version         0.3 beta 1.0 rc1 changes ( direct query bug fixed ) removed auto bind value,
 *                  last query bug fixed.
 * @version         0.4 added profiler class variables, queries, $query_times
 * @version         0.5 added first_row(), last, next and prev functions
 */

Class OB_DB extends OB_DBAc_sw {

    public $prepare                 = FALSE;    // prepare switch
    public $p_opt                   = array();  // prepare options
    public $last_sql                = NULL;     // stores last queried sql
    public $last_values             = array();  // stores last executed PDO values by exec_count

    public $query_count             = 0;        // count all queries.
    public $exec_count              = 0;        // count exec methods.
    public $queries                 = array();  // stores queries for profiler
    public $cached_queries          = array();  // stores cached queries for profiler
    public $query_times             = array();  // query time for profiler
    public $benchmark               = '';       // stores benchmark info
    
    public $current_row             = 0;        // stores the current row
    public $stmt_result             = array();  // stores current result for first_row() next_row() iteration

    public $use_bind_values         = FALSE;    // bind value usage switch
    public $use_bind_params         = FALSE;    // bind param usage switch
    public $last_bind_values        = array();  // Last bindValues and bindParams
    public $last_bind_params        = array();  // We store binds values to array()
                                                // because of we need it in last_query() function

    private $Stmt                   = NULL;     // PDOStatement Object

    // Private variables
    public $_protect_identifiers    = TRUE;
    public $_reserved_identifiers   = array('*'); // Identifiers that should NOT be escaped

    /**
    * Pdo connection object.
    *
    * @var string
    */
    public $_conn = NULL;
    // --------------------------------------------------------------------

    /**
    * Connect to PDO
    *
    * @author   Ersin Guvenc
    * @param    string $dsn  Dsn
    * @param    string $user Db username
    * @param    mixed  $pass Db password
    * @param    array  $options Db Driver options
    * @return   void
    */
    public function pdo_connect($dsn, $user = NULL, $pass = NULL, $options = NULL)
    {
        lang_load('db');

        $this->_conn = new PDO($dsn, $user, $pass, $options);

        return $this;
    }

    /**
    * Set PDO native Prepare() function
    *
    * @author   Ersin Guvenc
    * @param    array $options prepare options
    */
    public function prep($options = array())
    {
        $this->p_opt   = $options;
        $this->prepare = TRUE;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Flexible Prepared or Direct Query
    *
    * @author  Ersin Guvenc
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
            $this->Stmt = $this->_conn->prepare($sql, $this->p_opt);

            $this->prep_queries[] = $sql;  // Save the  query for debugging

            ++$this->query_count;

            return $this;
        }

        //------------------------------------
        $start_time = ob_query_timer_start();

        $this->Stmt = $this->_conn->query($sql);

        $this->queries[] = $sql;   // Save the  query for debugging

        $end_time   = ob_query_timer_end();
        //------------------------------------

        $this->benchmark +=    $end_time - $start_time;
        $this->query_times[] = $end_time - $start_time;

        ++$this->query_count;

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
    * @version   0.1
    * @version   0.2  Switched from using gettype() to is_ , some PHP versions might change its output.
    * @return    mixed
    */
    public function escape($str)
    {
        if(is_string($str))
        return $this->escape_str($str);

        if(is_integer($str))
        return (int)$str;

        if(is_double($str))
        return (double)$str;

        if(is_float($str))
        return (float)$str;

        if(is_bool($str))
        return ($str === FALSE) ? 0 : 1;

        if(is_null($str))
        return 'NULL';
    }

    // --------------------------------------------------------------------

    /**
    * Execute prepared query
    *
    * @author   Ersin Guvenc
    * @version  0.1
    * @version  0.2     added secure like conditions support
    * @version  0.3     changed bindValue functionality
    * @version  0.3     removed auto bind value, changed value storage
    * @param    array   $array bound, DEFAULT MUST BE NULL.
    * @param    string  $bind_value
    * @return   object  | void
    */
    public function exec($array = NULL)
    {
        if(is_array($array))
        {
            if( ! self::_is_assoc($array))
            throw new DBException(lang('db_bind_data_must_assoc'));
        }

        //------------------------------------
        $start_time = ob_query_timer_start();

        $this->Stmt->execute($array);

        $this->cached_queries[] = end($this->prep_queries);   // Save the "cached" query for debugging

        $end_time   = ob_query_timer_end();
        //------------------------------------

        $this->benchmark += $end_time - $start_time;
        $this->query_times['cached'][] = $end_time - $start_time;

        // reset prepare variable and prevent collision with next query ..
        $this->prepare = FALSE;

        ++$this->exec_count;        // count execute of prepared statements ..

        $this->last_values = array();   // reset last bind values ..

        // store last executed bind values for last_query method.
        if(is_array($array))
        {
            $this->last_values[$this->exec_count] = $array;

        }elseif($this->use_bind_values)
        {
            $this->last_values[$this->exec_count] = $this->last_bind_values;

        }elseif($this->use_bind_params)
        {
            $this->last_values[$this->exec_count] = $this->last_bind_params;
        }

        // reset query bind usage informations ..
        $this->use_bind_values  = FALSE;
        $this->use_bind_params  = FALSE;
        $this->last_bind_values = array();
        $this->last_bind_params = array();

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Exec just for CREATE, DELETE, INSERT and
    * UPDATE operations it returns to
    * number of affected rows after the write
    * operations.
    *
    * @author   Ersin Guvenc
    * @param    string $sql
    * @version  0.1
    * @return   boolean
    */
    public function exec_query($sql)
    {
        $this->last_sql = $sql;

        //------------------------------------
        $start_time = ob_query_timer_start();

        $this->queries[] = $sql;    // Save the  query for debugging

        $affected_rows = $this->_conn->exec($sql);

        $end_time   = ob_query_timer_end();
        //------------------------------------

        $this->benchmark +=    $end_time - $start_time;
        $this->query_times[] = $end_time - $start_time;

        return $affected_rows;
    }

    // --------------------------------------------------------------------

    /**
    * Fetch prepared or none prepared last_query
    *
    * @author   Ersin Guvenc
    * @version  0.1
    * @version  0.2 added prepared param
    * @version  0.3 added bind_chr var and strpos function.
    * @param    boolean $prepared
    * @return   string
    */
    public function last_query($prepared = FALSE)
    {
        // let's make sure, is it prepared query ?
        if($prepared == TRUE AND self::_is_assoc($this->last_values))
        {
            $bind_keys = array();
            foreach(array_keys($this->last_values[$this->exec_count]) as $k)
            {
                $bind_chr = ':';
                if(strpos($k, ':') === 0)   // If user use ':' characters
                $bind_chr = '';             // Some users forgot this character

                $bind_keys[]  = "/\\$bind_chr".$k.'\b/';  // escape bind ':' character
            }

            $quoted_vals = array();
            foreach(array_values($this->last_values[$this->exec_count]) as $v)
            {
                $quoted_vals[] = $this->quote($v);
            }

            $this->last_values = array();  // reset last values.

            return preg_replace($bind_keys, $quoted_vals, $this->last_sql);
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
        return $this->_conn->lastInsertId();
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

        return $this;
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

        return $this;
    }

    // --------------------------------------------------------------------

    /**
    * Get available drivers on your host
    *
    * @return  object PDO::Statement
    */
    public function drivers()
    {
        return $this->_conn->getAvailableDrivers();
    }

    // --------------------------------------------------------------------

    /**
    * Get results as associative array
    *
    * @return  array
    */
    public function assoc()
    {
        return current($this->Stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // --------------------------------------------------------------------

    /**
    * Get results as object
    *
    * @return  object
    */
    public function obj()
    {
        return $this->row();
    }

    // --------------------------------------------------------------------

    /**
    * Alias of $this-db->obj()
    *
    * @return  object
    */
    public function row()
    {
        return current($this->Stmt->fetchAll(PDO::FETCH_OBJ));
    }

    // --------------------------------------------------------------------

    /**
    * Get number of rows, Does not support all db drivers.
    *
    * @return  integer
    */
    public function row_count()
    {
        return $this->Stmt->rowCount();
    }
    
    // --------------------------------------------------------------------

    /**
    * Get results for current db 
    * operation. (first_row(), next_row() .. )
    *     
    * @access   private
    * @param    integer $type
    * @return   array
    */
    private function _stmt_result($type)
    {
        if(count($this->stmt_result) > 0)
        {
            return $this->stmt_result;
        }
        
        $this->stmt_result = $this->Stmt->fetchAll($type);
        
        return $this->stmt_result;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Returns the "first" row
    *
    * @access    public
    * @return    object
    */    
    public function first_row($type = obj)
    {
        $result = $this->_stmt_result($type);

        if (count($result) == 0)
        {
            return $result;
        }
        
        return $result[0];
    }

    // --------------------------------------------------------------------
    
    /**
    * Returns the "last" row
    *
    * @access    public
    * @return    object
    */    
    public function last_row($type = obj)
    {
        $result = $this->_stmt_result($type);

        if (count($result) == 0)
        {
            return $result;
        }
        return $result[count($result) -1];
    }    
    
    // --------------------------------------------------------------------

    /**
    * Returns the "next" row
    *
    * Returns the next row results. next_rowset doesn't work for the mysql
    * driver. Also adds backwards compatibility for Codeigniter.
    *
    * @author CJ Lazell
    * @access	public
    * @return	object
    */	
    public function next_row($type = obj)
    {
        $result = $this->_stmt_result($type);

        if(count($result) == 0)
        {
            return $result;
        }
        
        if(isset($result[$this->current_row + 1]))
        {
            ++$this->current_row;
        }

        return $result[$this->current_row];
    }

    // --------------------------------------------------------------------

    /**
    * Returns the "previous" row
    *
    * @access    public
    * @return    object
    */    
    public function previous_row($type = obj)
    {
        $result = $this->_stmt_result($type);

        if (count($result) == 0)
        {
            return $result;
        }

        if (isset($result[$this->current_row - 1]))
        {
            --$this->current_row;
        }
        return $result[$this->current_row];
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Fetches the next row and returns it as an object.
    *
    * @param    string $class  OPTIONAL Name of the class to create.
    * @param    array  $config OPTIONAL Constructor arguments for the class.
    * @return   mixed One object instance of the specified class.
    */
    public function fetch_object($class = 'stdClass', array $config = array())
    {
        return $this->Stmt->fetchObject($class, $config);
    }

    // --------------------------------------------------------------------

    /**
    * Retrieve a statement attribute.
    *
    * @param   integer $key Attribute name.
    * @return  mixed      Attribute value.
    */
    public function get_attribute($key)
    {
        return $this->Stmt->getAttribute($key);
    }

    // --------------------------------------------------------------------

    /**
    * Returns metadata for a column in a result set.
    *
    * @param int $column
    * @return mixed
    */
    public function get_colmeta($column)
    {
        return $this->Stmt->getColumnMeta($column);
    }

    /**
    * Get column names and numbers (both)
    *
    * @return  mixed
    */
    public function both()
    {
        return current($this->Stmt->fetchAll(PDO::FETCH_BOTH));
    }

    // --------------------------------------------------------------------

    /**
    * Native PDOStatement::fetch() function
    *
    * @param    int $fetch_style = PDO::FETCH_BOTH
    * @param    int $cursor_orientation = PDO::FETCH_ORI_NEXT
    * @param    $cursor_offset = 0
    * @return   object
    */
    public function fetch()
    {
        $arg = func_get_args();

        switch (sizeof($arg))
        {
           case 0:
           return current($this->Stmt->fetchAll(PDO::FETCH_OBJ));
             break;
           case 1:
           return current($this->Stmt->fetchAll($arg[0]));
             break;
           case 2:
           return current($this->Stmt->fetchAll($arg[0], $arg[1]));
             break;
           case 3:
           return current($this->Stmt->fetchAll($arg[0], $arg[1], $arg[2]));
             break;
        }
    }

    // --------------------------------------------------------------------

    /**
    * Get "all results" by assoc, object, num, bound or
    * anything what u want
    *
    * @param    int $fetch_style  = PDO::FETCH_BOTH
    * @param    int $column_index = 0
    * @param    array $ctor_args  = array()
    * @return   object
    */
    public function fetch_all()
    {
        $arg = func_get_args();

        switch (sizeof($arg))
        {
           case 0:
           return $this->Stmt->fetchAll(PDO::FETCH_OBJ);
             break;
           case 1:
           return $this->Stmt->fetchAll($arg[0]);
             break;
           case 2:
           return $this->Stmt->fetchAll($arg[0], $arg[1]);
             break;
           case 3:
           return $this->Stmt->fetchAll($arg[0], $arg[1], $arg[2]);
             break;
        }
    }

    // --------------------------------------------------------------------

    /**
    * Returns a single column from the next row of a result set
    *
    * @param object
    */
    public function fetch_column($col = NULL)
    {
        return $this->Stmt->fetchColumn($col);
    }

    // --------------------------------------------------------------------

    /**
    * CodeIgniter backward compatibility (result)
    *
    * @return object
    */
    public function result()
    {
        return $this->Stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
    * CodeIgniter backward compatibility (result_array)
    *
    * @return  array
    */
    public function result_array()
    {
        return $this->Stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * CodeIgniter backward compatibility (result_array)
    *
    * @author CJ Lazell
    * @return  array
    */
    public function row_array()
    {
        return current($this->Stmt->fetchAll(PDO::FETCH_ASSOC));
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
        if(sizeof($arr) == 0) return FALSE;

        return array_keys($arr) !== range(0, count($arr) - 1);
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
    public function _protect_identifiers($item, $prefix_single = FALSE, $protect_identifiers = NULL, $field_exists = TRUE)
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
            $parts = explode('.', $item);

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


}

/* End of file DB.php */
/* Location: .base/database/DB.php */
