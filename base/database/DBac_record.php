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
 * Obullo Active Record with PDO Class.
 *
 * @package         Obullo 
 * @subpackage      Base.database     
 * @category        Database
 * @version         0.1
 * @version         0.2 added query builder
 * @version         0.3 added prepare 'like' support 
 * @version         0.4 added parent::exec();, added CRUD functions 
 */
 
Class DB_active_record extends PDO 
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
    
    /**
    * Store $this->_compile_select();
    * result into $sql var
    * 
    * @var string
    */
    public $sql;
    
    
    public function select($select = '*', $escape = NULL)
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
    }    
    
    /**
    * DISTINCT
    *
    * Sets a flag which tells the query string compiler to add DISTINCT
    *
    * @access   public
    * @param    bool
    * @return   object
    */
    public function distinct($val = TRUE)
    {
        $this->ar_distinct = (is_bool($val)) ? $val : TRUE;
    }
    
    
    public function from($from)
    {
        $this->ar_from[] = $from;
        
        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_from[]   = $from;
            $this->ar_cache_exists[] = 'from';
        }
    }
    
    public function where($key, $value = NULL, $escape = TRUE)
    {
        if($this->prepare) $escape = FALSE;
        
        $this->_where($key, $value, 'AND ', $escape);
    }
    
    public function or_where($key, $value = NULL, $escape = TRUE)
    {
        if($this->prepare) $escape = FALSE;
        
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
                $v = ' '.$this->escape($v);
                
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

    public function where_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values);
    }
    
    // --------------------------------------------------------------------

    public function or_where_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values, FALSE, 'OR ');
    }

    // --------------------------------------------------------------------

    public function where_not_in($key = NULL, $values = NULL)
    {
        return $this->_where_in($key, $values, TRUE);
    }
    
    // --------------------------------------------------------------------

    public function or_where_not_in($key = NULL, $values = NULL)
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
    public function _where_in($key = NULL, $values = NULL, $not = FALSE, $type = 'AND ')
    {
        if ($key === NULL OR $values === NULL)
        return;
        
        if ( ! is_array($values))
        $values = array($values);
        
        $not = ($not) ? ' NOT' : '';

        foreach ($values as $value)
        {
            if($this->prepare)
            {
                $this->ar_wherein[] = $value;
            } else {
                $this->ar_wherein[] = $this->escape($value);     
            }
        }

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
    
    public function like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side);
    }

    // --------------------------------------------------------------------

    public function not_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side, 'NOT');
    }
        
    // --------------------------------------------------------------------

    public function or_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side);
    }

    // --------------------------------------------------------------------

    public function or_not_like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side, 'NOT');
    }
    
    // --------------------------------------------------------------------

    public function orlike($field, $match = '', $side = 'both')
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
            
            // if pdo prepare used remove % operators..
            if($this->prepare) 
            {
                $like_statement = $prefix." $k $not LIKE ".$v;
                // echo $like_statement;
            } 
            else 
            {
                // escape LIKE condition wildcards
                $v = str_replace(array('%', '_'), array('\\%', '\\_'), $v);
                
                switch ($side)
                {
                   case 'before':
                     $like_statement = $prefix." $k $not LIKE ".$this->quote('%'."{$v}");
                     break;
                     
                   case 'after':
                     $like_statement = $prefix." $k $not LIKE ".$this->quote("{$v}".'%');
                     break;
                     
                   default:
                     $like_statement = $prefix." $k $not LIKE ".$this->quote('%'."{$v}".'%'); 
                }
                
            }
            
            $this->ar_like[] = $like_statement;
            
            //  print_r($this->ar_like); exit;
            
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
    * @access   public
    * @param    string
    * @return   object
    */
    public function group_by($by)
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

    public function groupby($by)
    {
        return $this->group_by($by);
    }    
    
    // -------------------------------------------------------------------- 
    
    public function having($key, $value = '', $escape = TRUE)
    {
        if($this->prepare) $escape = FALSE;
        
        return $this->_having($key, $value, 'AND ', $escape);
    }

    // -------------------------------------------------------------------- 

    public function orhaving($key, $value = '', $escape = TRUE)
    {
        if($this->prepare) $escape = FALSE;
        
        return $this->or_having($key, $value, $escape);
    }    
    
    // --------------------------------------------------------------------
    
    public function or_having($key, $value = '', $escape = TRUE)
    {
        if($this->prepare) $escape = FALSE;
        
        return $this->_having($key, $value, 'OR ', $escape);
    }
    
    /**
    * Sets the HAVING values
    *
    * Called by having() or or_having()
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   object
    */
    private function _having($key, $value = '', $type = 'AND ', $escape = TRUE)
    {
        if ( ! is_array($key))
        $key = array($key => $value);
    
        foreach ($key as $k => $v)
        {
            $prefix = (count($this->ar_having) == 0) ? '' : $type;

            if ( ! self::has_operator($k))
            $k .= ' = ';

            if ($v != '')
            $v = ' '.$this->escape($v);
            
            $this->ar_having[] = $prefix.$k.$v;
            if ($this->ar_caching === TRUE)
            {
                $this->ar_cache_having[] = $prefix.$k.$v;
                $this->ar_cache_exists[] = 'having';
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    public function orderby($orderby, $direction = '')
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
    public function order_by($orderby, $direction = '')
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

    public function limit($value, $offset = '')
    {
        $this->ar_limit = $value;

        if ($offset != '')
        $this->ar_offset = $offset;
    }
                                          
    // --------------------------------------------------------------------

    public function offset($offset)
    {
        $this->ar_offset = $offset;
    }
 
    /**
    * Limit string
    *
    * Generates a platform-specific LIMIT clause
    *
    * @access   public
    * @param    string    the sql query string
    * @param    integer    the number of rows to limit the query to
    * @param    integer    the offset value
    * @return   string
    */
    public function _limit($sql, $limit, $offset)
    {    
        if ($offset == 0)
        {
            $offset = '';
        }
        else
        {
            $offset .= ", ";
        }
        
        return $sql."LIMIT ".$offset.$limit;
    }
    
    
    /**
    * Get
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
    public function get($table = '', $limit = null, $offset = null)
    {
        if ($table != '') 
        {   
            $this->_track_aliases($table);
            $this->from($table);
        }
        
        if ( ! is_null($limit))
        $this->limit($limit, $offset);
            
        $this->sql = $this->_compile_select();

        //echo $this->sql; exit;
        
        if($this->prepare == FALSE)
        {
            $result = $this->query($this->sql);
            $this->_reset_select();
            
            return $result;
        
        } elseif($this->prepare)
        {
            $this->query($this->sql);  
            $this->_reset_select();
        }
 
    }    
    
    /**
    * Track Aliases
    *
    * Used to track SQL statements written with aliased tables.
    *
    * @access    private
    * @param     string    The table to inspect
    * @return    string
    */    
    private function _track_aliases($table)
    {
        if (is_array($table))
        {
            foreach ($table as $t)
            {
                $this->_track_aliases($t);
            }
            return;
        }
        
        // Does the string contain a comma?  If so, we need to separate
        // the string into discreet statements
        if (strpos($table, ',') !== FALSE)
        {
            return $this->_track_aliases(explode(',', $table));
        }
    
        // if a table alias is used we can recognize it by a space
        if (strpos($table, " ") !== FALSE)
        {
            // if the alias is written with the AS keyword, remove it
            $table = preg_replace('/ AS /i', ' ', $table);
            
            // Grab the alias
            $table = trim(strrchr($table, " "));
            
            // Store the alias, if it doesn't already exist
            if ( ! in_array($table, $this->ar_aliased_tables))
            {
                $this->ar_aliased_tables[] = $table;
            }
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
    private function _compile_select($select_override = FALSE)
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
                    $this->ar_select[$key] = $val;
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

        return $sql;
    }

    /**
    * From Tables
    *
    * This function implicitly groups FROM tables so there is no confusion
    * about operator precedence in harmony with SQL standards
    *
    * @access   public
    * @param    type
    * @return   type
    */
    public function _from_tables($tables)
    {
        if ( ! is_array($tables))
        $tables = array($tables);
        
        return $this->left.implode(', ', $tables).$this->right;
    }
    
    // --------------------------------------------------------------------

    /**
    * Start Cache
    *
    * Starts AR caching
    *
    * @access    public
    * @return    void
    */        
    public function start_cache()
    {
        $this->ar_caching = TRUE;
    }

    // --------------------------------------------------------------------

    /**
    * Stop Cache
    *
    * Stops AR caching
    *
    * @access    public
    * @return    void
    */        
    public function stop_cache()
    {
        $this->ar_caching = FALSE;
    }

    // --------------------------------------------------------------------

    /**
    * Flush Cache
    *
    * Empties the AR cache
    *
    * @access    public
    * @return    void
    */    
    public function flush_cache()
    {    
        $this->_reset_run(
                            array(
                                    'ar_cache_select'   => array(), 
                                    'ar_cache_from'     => array(), 
                                    'ar_cache_join'     => array(),
                                    'ar_cache_where'    => array(), 
                                    'ar_cache_like'     => array(), 
                                    'ar_cache_groupby'  => array(), 
                                    'ar_cache_having'   => array(), 
                                    'ar_cache_orderby'  => array(), 
                                    'ar_cache_set'      => array(),
                                    'ar_cache_exists'   => array()
                                )
                            );    
    }

    // --------------------------------------------------------------------

    /**
    * Merge Cache
    *
    * When called, this function merges any cached AR arrays with 
    * locally called ones.
    *
    * @access    private
    * @return    void
    */
    private function _merge_cache()
    {
        if (count($this->ar_cache_exists) == 0)
        return;

        foreach ($this->ar_cache_exists as $val)
        {
            $ar_variable    = 'ar_'.$val;
            $ar_cache_var   = 'ar_cache_'.$val;

            if (count($this->$ar_cache_var) == 0)
            continue;
    
            $this->$ar_variable = array_unique(array_merge($this->$ar_cache_var, $this->$ar_variable));
        }
    }

    // --------------------------------------------------------------------
        
    /**
    * Resets the active record values.  Called by the get() function
    *
    * @access   private
    * @param    array    An array of fields to reset
    * @return   void
    */
    private function _reset_run($ar_reset_items)
    {
        foreach ($ar_reset_items as $item => $default_value)
        {
            if ( ! in_array($item, $this->ar_store_array))
            $this->$item = $default_value;
        }
    }
 
   // --------------------------------------------------------------------

    /**
    * Resets the active record values.  Called by the get() function
    *
    * @access    private
    * @return    void
    */
    private function _reset_select()
    {
        $ar_reset_items = array(
                                'ar_select'         => array(), 
                                'ar_from'           => array(), 
                                'ar_join'           => array(), 
                                'ar_where'          => array(), 
                                'ar_like'           => array(), 
                                'ar_groupby'        => array(), 
                                'ar_having'         => array(), 
                                'ar_orderby'        => array(), 
                                'ar_wherein'        => array(), 
                                'ar_aliased_tables' => array(),
                                'ar_distinct'       => FALSE, 
                                'ar_limit'          => FALSE, 
                                'ar_offset'         => FALSE, 
                                'ar_order'          => FALSE,
                            );
        
        $this->_reset_run($ar_reset_items);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Resets the active record "write" values.
    *
    * Called by the insert() update() and delete() functions
    *
    * @access    private
    * @return    void
    */
    private function _reset_write()
    {    
        $ar_reset_items = array(
                                'ar_set'        => array(), 
                                'ar_from'       => array(), 
                                'ar_where'      => array(), 
                                'ar_like'       => array(),
                                'ar_orderby'    => array(), 
                                'ar_limit'      => FALSE, 
                                'ar_order'      => FALSE
                                );

        $this->_reset_run($ar_reset_items);
    }
    
    // --------------------------------------------------------------------
    
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
    
    // --------------------------------------------------------------------

    /**
    * PDO AUTO Quote
    * PDO "Smart" Escape String
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
                 $str = $this->quote($str, PDO::PARAM_STR); 
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
                 $str = $this->quote($str, PDO::PARAM_STR);
            }

        return $str;
    }
    
    // --------------------------------------------------------------------
                                             
    public function join($table, $condition, $direction = '')
    {
        $join = strtoupper($direction).' JOIN '.$table.' ON '.$condition.' ';
        
        $this->ar_join[] = $join;
        if ($this->ar_caching === TRUE)
        {
            $this->ar_cache_join[]   = $join;
            $this->ar_cache_exists[] = 'join';
        }   
    }
    
    // --------------------------------------------------------------------
    
    public function output()
    {
        return $this->last_sql;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * The "set" function.  Allows key/value pairs to be set for inserting or updating
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @param    boolean
    * @return   void
    */
    public function set($key, $value = '', $escape = TRUE)
    {
        if ( ! is_array($key))
        $key = array($key => $value);    

        foreach ($key as $k => $v)
        {
            if ($escape === FALSE)
            {
                $this->ar_set[$k] = "'".$v."'";
            }
            else
            {
                $this->ar_set[$k] = $this->escape($v);
            }
        }
        
    } 
    
    // --------------------------------------------------------------------
    
    /**
    * Update
    *
    * Compiles an update string and runs the query
    *
    * @author   Ersin Güvenç
    * @access   public
    * @param    string   the table to retrieve the results from
    * @param    array    an associative array of update values
    * @param    mixed    the where clause
    * @return   PDO exec number of affected rows
    */
    public function update($table = '', $set = NULL, $where = NULL, $limit = NULL)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();
        
        if ( ! is_null($set))
        $this->set($set);
    
        if (count($this->ar_set) == 0)
        {
            throw new DBException('Please set values for update operation !');
            
            return FALSE;
        }
                                         
        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new DBException('Please set table for update operation !'); 
                
                return FALSE;
            }
            
            $table = $this->ar_from[0];
        }
        
        if ($where != NULL)
        $this->where($where);
        
        if ($limit != NULL)
        $this->limit($limit);       
        
        $sql = $this->_update($table, $this->ar_set, $this->ar_where, $this->ar_orderby, $this->ar_limit);
        
        //echo $sql; exit;
                                   
        $this->_reset_write();
        
        return $this->exec_query($sql);  // return number of affected rows.  
    }
    
    // --------------------------------------------------------------------
                    
    /**
    * Generate an update string
    *
    * @access   public
    * @param    string   the table upon which the query will be performed
    * @param    array    an associative array data of key/values
    * @param    mixed    the "where" statement
    * @return   string        
    */    
    public function update_string($table, $data, $where)
    {
        if ($where == '')
        {
            return false;
        }
                    
        $fields = array();
        foreach($data as $key => $val)
        {
            $fields[$key] = $this->escape($val);
        }

        if ( ! is_array($where))
        {
            $dest = array($where);
        }
        else
        {
            $dest = array();
            foreach ($where as $key => $val)
            {
                $prefix = (count($dest) == 0) ? '' : ' AND ';
    
                if ($val !== '')
                {
                    if ( ! $this->_has_operator($key))
                    {
                        $key .= ' =';
                    }
                
                    $val = ' '.$this->escape($val);
                }
                            
                $dest[] = $prefix.$key.$val;
            }
        }        

        return $this->_update($table, $fields, $dest);
    }
    
    // --------------------------------------------------------------------
                     
    /**
    * Update standart statement (none PDO)
    *
    * Generates a platform-specific update string from the supplied data
    *
    * @author   Ersin Güvenç
    * @access   private
    * @param    string   the table name
    * @param    array    the update data
    * @param    array    the where clause
    * @param    array    the orderby clause
    * @param    array    the limit clause
    * @return   string
    */
    private function _update($table, $values, $where, $orderby = array(), $limit = FALSE)
    {
        foreach($values as $key => $val)
        $valstr[] = $key." = ".$val;
        
        $_limit = '';
        if($limit != FALSE)
        $_limit = ' LIMIT '.$limit;
        
        $_orderby = '';
        if(count($orderby) >= 1)
        $_orderby = ' ORDER BY '.implode(", ", $orderby);
     
        $sql = "UPDATE ".$table." SET ".implode(', ', $valstr);

        if($where != '' AND count($where) >=1)
        $sql .= " WHERE ".implode(" ", $where); 
     
        $sql .= $_orderby.$_limit;
        
        return $sql;
    }
                                                 
    /**
    * Insert
    *
    * Compiles an insert string and runs the query
    *
    * @access   public
    * @param    string   the table to retrieve the results from
    * @param    array    an associative array of insert values
    * @return   PDO exec number of affected rows.
    */
    public function insert($table = '', $set = NULL)
    {    
        if ( ! is_null($set))
        $this->set($set);
        
        if (count($this->ar_set) == 0)
        {
            throw new DBException('Please set value for insert operation !');
            
            return FALSE;
        }

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new DBException('Please set table for insert operation !');
                
                return FALSE;
            }
            
            $table = $this->ar_from[0];
        }

        $sql = $this->_insert($table, array_keys($this->ar_set), array_values($this->ar_set));
                     
        //echo $sql; exit;
        
        $this->_reset_write();
        
        $this->prepare = FALSE;
        
        return $this->exec_query($sql);  // return affected rows.      
    }
    
    // --------------------------------------------------------------------
 
    /**
    * Generate an insert string
    *
    * @access   public
    * @param    string    the table upon which the query will be performed
    * @param    array    an associative array data of key/values
    * @return   string        
    */    
    public function insert_string($table, $data)
    {
        $fields = array();
        $values = array();
        
        foreach($data as $key => $val)
        {
            $fields[] = $key;
            $values[] = $this->escape($val);
        }
                
        return $this->_insert($table, $fields, $values);
    }    
    
    // --------------------------------------------------------------------

    /**
    * Insert statement
    *
    * Generates a platform-specific insert string from the supplied data
    *
    * @access   private
    * @param    string   the table name
    * @param    array    the insert keys
    * @param    array    the insert values
    * @return   string
    */
    private function _insert($table, $keys, $values)
    {   
        return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
    }
    
    /**
    * Delete
    *
    * Compiles a delete string and runs the query
    *
    * @access   public
    * @param    mixed    the table(s) to delete from. String or array
    * @param    mixed    the where clause
    * @param    mixed    the limit clause
    * @param    boolean
    * @return   object
    */
    public function delete($table = '', $where = '', $limit = NULL, $reset_data = TRUE)
    {
        // Combine any cached components with the current statements
        $this->_merge_cache();

        if ($table == '')
        {
            if ( ! isset($this->ar_from[0]))
            {
                throw new DBException('Please set table for delete operation !');
                
                return FALSE;
            }

            $table = $this->ar_from[0];
        }
        elseif (is_array($table))
        {
            foreach($table as $single_table)
            $this->delete($single_table, $where, $limit, FALSE);
        
            $this->_reset_write();
            return;
        }

        if ($where != '')
        $this->where($where);
        
        if ($limit != NULL)
        $this->limit($limit);
        
        if (count($this->ar_where) == 0 && count($this->ar_wherein) == 0 && count($this->ar_like) == 0)
        {
            throw new DBException('Deletes are not allowed unless they contain a 
             \'where\' or \'like\' clause.');
            
            return FALSE;
        }        

        $sql = $this->_delete($table, $this->ar_where, $this->ar_like, $this->ar_limit);
        
        if ($reset_data)
        $this->_reset_write();
        
        return $this->exec_query($sql); // return number of  affected rows
    
    }  //end func.
    
    /**
    * Delete statement
    *
    * Generates a platform-specific delete string from the supplied data
    *
    * @access   public
    * @param    string   the table name
    * @param    array    the where clause
    * @param    string   the limit clause
    * @return   string
    */    
    public function _delete($table, $where = array(), $like = array(), $limit = FALSE)
    {
        $conditions = '';

        if (count($where) > 0 OR count($like) > 0)
        {
            $conditions  = "\nWHERE ";
            $conditions .= implode("\n", $this->ar_where);

            if (count($where) > 0 && count($like) > 0)
            $conditions .= " AND ";
            
            $conditions .= implode("\n", $like);
        }

        $limit = ( ! $limit) ? '' : ' LIMIT '.$limit;
    
        return "DELETE FROM ".$table.$conditions.$limit;
    }
    
    // --------------------------------------------------------------------

    /**
    * Tests whether the string has an SQL operator
    *
    * @access    private
    * @param    string
    * @return    bool
    */
    private function _has_operator($str)
    {
        $str = trim($str);
        if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
        {
            return FALSE;
        }

        return TRUE;
    }
    
                                  
} // end active record class.


?>
