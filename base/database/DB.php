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

Class OB_DB_active_record extends PDO 
{                                             
    public $ar_select              = array();
    public $ar_distinct            = FALSE;
    public $ar_from                = array();
    public $ar_join                = array();
    public $ar_where               = array();
    public $ar_like                = array();
    public $ar_groupby             = array();
    public $ar_having              = array();
    public $ar_limit               = FALSE;
    public $ar_offset              = FALSE;
    public $ar_order               = FALSE;
    public $ar_orderby             = array();
    public $ar_set                 = array();    
    public $ar_wherein             = array();
    public $ar_aliased_tables      = array();
    public $ar_store_array         = array();
    
    // Active Record Caching variables
    public $ar_caching             = FALSE;
    public $ar_cache_exists        = array();
    public $ar_cache_select        = array();
    public $ar_cache_from          = array();
    public $ar_cache_join          = array();
    public $ar_cache_where         = array();
    public $ar_cache_like          = array();
    public $ar_cache_groupby       = array();
    public $ar_cache_having        = array();
    public $ar_cache_orderby       = array();
    public $ar_cache_set           = array();    
    
    public $sql;
    
    /**
    * Store last queried sql
    * 
    * @var string
    */
    public $last_sql = '';

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
    
    
    // PDO prepare function.
    function prep($options = array())
    {
        $this->p_opt   = &$options;
        $this->prepare = TRUE;
    }
    
    
    function select($select = '*', $escape = NULL)
    {
        // Set the global value if this was sepecified    
        //if (is_bool($escape))
        //$this->_protect_identifiers = $escape;

        if (is_string($select))
        $select = explode(',', $select);
        
        foreach ($select as $val)
        {
            $val = trim($val);

            if ($val != '')
            {
                $this->ar_select[] = $val;

                if ($this->ar_caching === TRUE)
                {
                    $this->ar_cache_select[] = $val;
                    $this->ar_cache_exists[] = 'select';
                }
            }
        }
        
        //$this->sql_select = 'SELECT ';
        //$this->sql_select.= $select.' ';  
    }    
    
    /**
    * DISTINCT
    *
    * Sets a flag which tells the query string compiler to add DISTINCT
    *
    * @access    public
    * @param    bool
    * @return    object
    */
    function distinct($val = TRUE)
    {
        $this->ar_distinct = (is_bool($val)) ? $val : TRUE;
        //return $this;
    }
    
    function from($from)
    {
        $this->ar_from[] = $from;
        
        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_from[]   = $from;
            $this->ar_cache_exists[] = 'from';
        }
    }
    
    function where($key, $value = NULL, $escape = TRUE)
    {
        $this->_where($key, $value, 'AND ', $escape);
    }
    
    function or_where($key, $value = NULL, $escape = TRUE)
    {
        return $this->_where($key, $value, 'OR ', $escape);
    }
    
    /**
    * Where
    *
    * Called by where() or orwhere()
    *
    * @access   private
    * @param    mixed
    * @param    mixed
    * @param    string
    * @version  0.1
    * @return   void
    */
    private function _where($key, $value = NULL, $type = 'AND ', $escape = NULL)
    {
        if ( ! is_array($key))
        $key = array($key => $value);
        
        foreach ($key as $k => $v)
        {   
            $prefix = (count($this->ar_where) == 0 AND count($this->ar_cache_where) == 0) ? '' : $type;
            
            if (is_null($v) && ! self::has_operator($k))
            $k .= ' IS NULL';  // value appears not to have been set, assign the test to IS NULL  
            
            if ( ! is_null($v))
            {
                if ($escape === TRUE)
                $v = ' '.self::escape($v);
                
                if ( ! self::has_operator($k))
                $k .= ' =';
            }

            $this->ar_where[] = $prefix.$k.$v;
            if ($this->ar_caching === TRUE)
            {
                $this->ar_cache_where[]  = $prefix.$k.$v;
                $this->ar_cache_exists[] = 'where';
            }
        }
    }

    function where_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values);
    }
    
    // --------------------------------------------------------------------

    function or_where_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values, FALSE, 'OR ');
    }

    // --------------------------------------------------------------------

    function where_not_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values, TRUE);
    }
    
    // --------------------------------------------------------------------

    function or_where_not_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values, TRUE, 'OR ');
    }
        
    /**
    * Where_in
    *
    * Called by where_in, where_in_or, where_not_in, where_not_in_or
    *
    * @access   public
    * @param    string    The field to search
    * @param    array     The values searched on
    * @param    boolean   If the statement would be IN or NOT IN
    * @param    string    
    * @return   object
    */
    function _where_in($key = NULL, $values = NULL, $not = FALSE, $type = 'AND ')
    {
        if ($key === NULL OR $values === NULL)
        return;
        
        if ( ! is_array($values))
        $values = array($values);
        
        $not = ($not) ? ' NOT' : '';

        foreach ($values as $value)
        $this->ar_wherein[] = self::escape($value);

        $prefix = (count($this->ar_where) == 0) ? '' : $type;
 
        $where_in = $prefix . $key . $not . " IN (" . implode(", ", $this->ar_wherein) . ") ";

        $this->ar_where[] = $where_in;
        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_where[] = $where_in;
            $this->ar_cache_exists[] = 'where';
        }

        // reset the array for multiple calls
        $this->ar_wherein = array();
        
    }
    
    function like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side);
    }

    // --------------------------------------------------------------------

    function not_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side, 'NOT');
    }
        
    // --------------------------------------------------------------------

    function or_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side);
    }

    // --------------------------------------------------------------------

    function or_not_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side, 'NOT');
    }
    
    // --------------------------------------------------------------------

    function orlike($field, $match = '', $side = 'both')
    {
        return $this->or_like($field, $match, $side);
    }
    
    /**
    * Like
    *
    * Called by like() or orlike()
    *
    * @access   private
    * @param    mixed
    * @param    mixed
    * @param    string
    * @return   void
    */
    private function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
    {
        if ( ! is_array($field))
        $field = array($field => $match);
     
        foreach ($field as $k => $v)
        {
            $prefix = (count($this->ar_like) == 0) ? '' : $type;

            $v = self::escape($v);

            if ($side == 'before')
            {
                $like_statement = $prefix." $k $not LIKE '%{$v}'";
            }
            elseif ($side == 'after')
            {
                $like_statement = $prefix." $k $not LIKE '{$v}%'";
            }
            else
            {
                $like_statement = $prefix." $k $not LIKE '%{$v}%'";
            }
            
            $this->ar_like[] = $like_statement;
            if ($this->ar_caching === TRUE)
            {
                $this->ar_cache_like[]   = $like_statement;
                $this->ar_cache_exists[] = 'like';
            }
        }
    }
    
    /**
    * GROUP BY
    *
    * @access    public
    * @param    string
    * @return    object
    */
    function group_by($by)
    {
        if (is_string($by))
        $by = explode(',', $by);
        
        foreach ($by as $val)
        {
            $val = trim($val);
        
            if ($val != '')
            {
                $this->ar_groupby[] = $val;
                
                if ($this->ar_caching === TRUE)
                {
                    $this->ar_cache_groupby[] = $val;
                    $this->ar_cache_exists[] = 'groupby';
                }
            }
        }
    }
    
    // --------------------------------------------------------------------

    function groupby($by)
    {
        return $this->group_by($by);
    }    
    
    // -------------------------------------------------------------------- 
    
    function having($key, $value = '', $escape = TRUE)
    {
        return $this->_having($key, $value, 'AND ', $escape);
    }

    // -------------------------------------------------------------------- 

    function orhaving($key, $value = '', $escape = TRUE)
    {
        return $this->or_having($key, $value, $escape);
    }    
    
    // --------------------------------------------------------------------
    
    function or_having($key, $value = '', $escape = TRUE)
    {
        return $this->_having($key, $value, 'OR ', $escape);
    }
    
    /**
    * Sets the HAVING values
    *
    * Called by having() or or_having()
    *
    * @access    private
    * @param    string
    * @param    string
    * @return    object
    */
    function _having($key, $value = '', $type = 'AND ', $escape = TRUE)
    {
        if ( ! is_array($key))
        $key = array($key => $value);
    
        foreach ($key as $k => $v)
        {
            $prefix = (count($this->ar_having) == 0) ? '' : $type;

            if ( ! self::has_operator($k))
            $k .= ' = ';

            if ($v != '')
            $v = ' '.self::escape($v);
            
            $this->ar_having[] = $prefix.$k.$v;
            if ($this->ar_caching === TRUE)
            {
                $this->ar_cache_having[] = $prefix.$k.$v;
                $this->ar_cache_exists[] = 'having';
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    function orderby($orderby, $direction = '')
    {
        return $this->order_by($orderby, $direction);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Sets the ORDER BY value
    *
    * @access   public
    * @param    string
    * @param    string    direction: asc or desc
    * @return   object
    */
    function order_by($orderby, $direction = '')
    {
        $direction = strtoupper(trim($direction)); 
        if($direction != '')
        {
            switch($direction)
            {
                case 'ASC':
                $direction = ' ASC';    
                break;
                
                case 'DESC':
                $direction = ' DESC';
                break;
                
                default:
                $direction = ' ASC';
            }
        }
    
        if (strpos($orderby, ',') !== FALSE)
        {
            $temp = array();
            foreach (explode(',', $orderby) as $part)
            {
                $part = trim($part);            
                $temp[] = $part;
            }
            
            $orderby = implode(', ', $temp);            
        }
        else
        {
            $orderby = $orderby;
        }
    
        $orderby_statement = $orderby.$direction;
        
        $this->ar_orderby[] = $orderby_statement;
        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_orderby[] = $orderby_statement;
            $this->ar_cache_exists[] = 'orderby';
        }

    }
    
    // --------------------------------------------------------------------

    function limit($value, $offset = '')
    {
        $this->ar_limit = $value;

        if ($offset != '')
        $this->ar_offset = $offset;
    }
    
    // --------------------------------------------------------------------

    function offset($offset)
    {
        $this->ar_offset = $offset;
    }
    
    
    /**
    * The "set" function.  Allows key/value pairs to be set for inserting or updating
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @param    boolean
    * @return   void
    */
    function set($key, $value = '', $escape = TRUE)
    {
        $key = $this->_object_to_array($key);
    
        if ( ! is_array($key))
        $key = array($key => $value);    

        foreach ($key as $k => $v)
        {
            if ($escape === FALSE)
            {
                $this->ar_set[$k] = $v;
            }
            else
            {
                $this->ar_set[$k] = self::escape($v);
            }
        }
    }
    
    /**
    * Table alias of CI Get
    *
    * Compiles the select statement based on the other functions called
    * and runs the query
    *
    * @access   public
    * @param    string    the table
    * @param    string    the limit clause
    * @param    string    the offset clause
    * @return   object | void
    */
    function table($table = '', $limit = null, $offset = null)
    {
        if ($table != '')
        $this->from($table);
        
        if ( ! is_null($limit))
        $this->limit($limit, $offset);
            
        // WARNING !!! bunu exec() içine koymaya çalış... 
        $this->sql = $this->_compile_select();

        echo $this->sql; exit;
        
        if($this->prepare == FALSE)
        {
            $result = $this->query($this->sql);
            $this->_reset_select();
            return $result;
        
        } elseif($this->prepare)
        {
            $this->query($this->sql);  
        }
 
    } 
    
    
    // --------------------------------------------------------------------

    /**
     * Compile the SELECT statement
     *
     * Generates a query string based on which functions were used.
     * Should not be called directly.  The get() function calls it.
     *
     * @access    private
     * @return    string
     */
    function _compile_select($select_override = FALSE)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();

        // ----------------------------------------------------------------
        
        // Write the "select" portion of the query

        if ($select_override !== FALSE)
        {
            $sql = $select_override;
        }
        else
        {
            $sql = ( ! $this->ar_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';
        
            if (count($this->ar_select) == 0)
            {
                $sql .= '*';        
            }
            else
            {                
                // Cycle through the "select" portion of the query and prep each column name.
                // The reason we protect identifiers here rather then in the select() function
                // is because until the user calls the from() function we don't know if there are aliases
                foreach ($this->ar_select as $key => $val)
                {
                    $this->ar_select[$key] = $this->_protect_identifiers($val);
                }
                
                $sql .= implode(', ', $this->ar_select);
            }
        }

        // ----------------------------------------------------------------
        
        // Write the "FROM" portion of the query

        if (count($this->ar_from) > 0)
        {
            $sql .= "\nFROM ";

            $sql .= $this->_from_tables($this->ar_from);
        }

        // ----------------------------------------------------------------
        
        // Write the "JOIN" portion of the query

        if (count($this->ar_join) > 0)
        {
            $sql .= "\n";

            $sql .= implode("\n", $this->ar_join);
        }

        // ----------------------------------------------------------------
        
        // Write the "WHERE" portion of the query

        if (count($this->ar_where) > 0 OR count($this->ar_like) > 0)
        {
            $sql .= "\n";

            $sql .= "WHERE ";
        }

        $sql .= implode("\n", $this->ar_where);

        // ----------------------------------------------------------------
        
        // Write the "LIKE" portion of the query
    
        if (count($this->ar_like) > 0)
        {
            if (count($this->ar_where) > 0)
            {
                $sql .= "\nAND ";
            }

            $sql .= implode("\n", $this->ar_like);
        }

        // ----------------------------------------------------------------
        
        // Write the "GROUP BY" portion of the query
    
        if (count($this->ar_groupby) > 0)
        {
            $sql .= "\nGROUP BY ";
            
            $sql .= implode(', ', $this->ar_groupby);
        }

        // ----------------------------------------------------------------
        
        // Write the "HAVING" portion of the query
        
        if (count($this->ar_having) > 0)
        {
            $sql .= "\nHAVING ";
            $sql .= implode("\n", $this->ar_having);
        }

        // ----------------------------------------------------------------
        
        // Write the "ORDER BY" portion of the query

        if (count($this->ar_orderby) > 0)
        {
            $sql .= "\nORDER BY ";
            $sql .= implode(', ', $this->ar_orderby);
            
            if ($this->ar_order !== FALSE)
            {
                $sql .= ($this->ar_order == 'desc') ? ' DESC' : ' ASC';
            }        
        }

        // ----------------------------------------------------------------
        
        // Write the "LIMIT" portion of the query
        
        if (is_numeric($this->ar_limit))
        {
            $sql .= "\n";
            $sql = $this->_limit($sql, $this->ar_limit, $this->ar_offset);
        }

        $this->last_sql = $sql; 
        
        return $sql;
    }

    
    /**
    * Tests whether the string has an SQL operator
    *
    * @access   private
    * @param    string
    * @return   bool
    */
    private static function has_operator($str)
    {
        $str = trim($str);
        if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
        {
            return FALSE;
        }

        return TRUE;
    }

    /**
    * PDO "Smart" Escape String
    *
    * Escapes data based on type
    * Sets boolean and null types
    *
    * @access    public
    * @param     string
    * @return    mixed        
    */    
    public static function escape($str)
    {
        if (is_string($str))
        {                    
            $str = "'".self::quote($str, PDO::PARAM_STR)."'";
        }
        elseif (is_bool($str))
        {
            $str = ($str === FALSE) ? 0 : 1;
        }
        elseif (is_null($str))
        {
            $str = 'NULL';
        }

        return $str;
    }
    
                                             
    function join($table, $condition, $direction = '')
    {
        $join = strtoupper($direction).' JOIN '.$table.' ON '.$condition.' ';
        
        $this->ar_join[] = $join;
        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_join[]   = $join;
            $this->ar_cache_exists[] = 'join';
        }   
    }
    
    
    function output()
    {
        $this->_compile_select();
        return $this->last_sql;
    }
                                                        
    // update insert func ları için DB_driver.php ye ebak
    // ÖNEMLİ !! : pdo::quote kullan $this->escape yerine
    // pdo::quote default filter string
    function update_string($table, $data, $where)
    {
        
    }
    
}

Class DB_Adapter extends OB_DB_active_record {}

//Class DB_Adapter extends PDO {
    
    /**
    * prepare switch
    * 
    * @var boolean
    */
//    private $prepare = FALSE;
    
    /**
    * Prepare options
    * 
    * @var mixed
    */
//    private $p_opt = array();
    
    
    // PDO prepare function.
//    function prep($options = array())
//    {
//        $this->p_opt   = $options;
//        $this->prepare = TRUE;
//    }
    
//}

Class DB extends DB_Adapter
{
    
    /**
    * Parent Query pdo query result
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
    
    
    function __construct($dsn, $user = NULL, $pass = NULL, $options = NULL)
    {
        parent::__construct($dsn, $user, $pass, $options);
        
        //throw new DBException('SQLSTATE: test error!');
    } 
    
    // Direct or prepared query         
    function query($sql = NULL)
    {   
        $this->last_sql = $sql;
        
        if($this->prepare)
        {
            $this->PQ = parent::prepare($sql,$this->p_opt); 
            return NULL;
        }
        
        $this->PQ = parent::query($sql);

        return $this;
    }                    
        
    // Execute prepared query
    function exec($array = NULL)
    { 
        if($this->last_sql != NULL AND $this->exec_count == 0)
        {
            $this->query($this->last_sql);
        }
    
        // if no query builded by active record
        // switch to pdo::statement
        $this->PQ->execute($array);
        
        // $this->prepared_last_query; preg_replace ':fields'
        // we implement it later
        
        // reset prepare variable 
        $this->prepare = FALSE;
        
        ++$this->exec_count; 
        
        return NULL;
    }
        
    // fetch last_query
    function last_query()
    {
        return $this->last_sql;
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
    function update()
    {
        
    }
    
    // http://tr.php.net/manual/en/pdostatement.bindvalue.php
    function insert(){}
    
    // PDO exec() functionuna bak affected rows a otomatik dönüyor.
    function delete(){}
    
    function last_id(){}

 
} //end class.
 
?>
