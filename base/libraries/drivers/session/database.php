<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo      
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */
 
// ------------------------------------------------------------------------

/**
 * Session Cookie Driver
 *
 * @package      Obullo
 * @subpackage   Libraries
 * @category     Sessions
 * @author       Ersin Güvenç
 * @link         
 */
 
Class OB_Session_database_driver extends OB_Session {
    
    /**
    * Session Constructor
    *
    * The constructor runs the session routines automatically
    * whenever the class is instantiated.
    */        
    public function __construct($params = array())
    {
        parent::__construct($params);
        
        log_message('debug', 'Session Database Driver Initialized');
    }
    
    /**
    * Start the sessions.
    * 
    */
    public function _session_start()
    {
        // if database variable exists ..
        if($this->OB->{$this->sess_database_var} instanceof PDO)
        {
            $this->sess_db = &$this->OB->{$this->sess_database_var};
        } 
        else
        {
            if( ! $this->OB->db instanceof PDO) 
            {
                throw new SessionException('Session class works with database class so 
                you must load database object by loader::database() function.');
            }
            
            $this->sess_db = &$this->OB->db;
        }
        
        parent::_session_start();
    
    }
    
    /**
    * Fetch the current session data if it exists
    *
    * @access    public
    * @return    void
    */
    public function sess_read()
    {
        $session = parent::sess_read();
        
        if($session === FALSE) { return FALSE; } 
        
        $this->sess_db->where('session_id', $session['session_id']);
                
        if ($this->sess_match_ip == TRUE)
        {
            $this->sess_db->where('ip_address', $session['ip_address']);
        }

        if ($this->sess_match_useragent == TRUE)
        {
            $this->sess_db->where('user_agent', $session['user_agent']);
        }
        
        $query = $this->sess_db->get($this->sess_table_name);

        // No result?  Kill it!
        if ($query->num_rows() == 0)
        {
            $this->destroy();
            return FALSE;
        }

        // Is there custom data?  If so, add it to the main session array
        $row = $query->row();
        
        if (isset($row->user_data) AND $row->user_data != '')
        {
            $custom_data = $this->_unserialize($row->user_data);

            if (is_array($custom_data))
            {
                foreach ($custom_data as $key => $val)
                {
                    $session[$key] = $val;
                }
            }
        }                
    
        // Session is valid!
        $this->userdata = $session;
        unset($session);
        
        return TRUE;
    
    }
    
    /**
    * Write the session data
    *
    * @access    public
    * @return    void
    */
    public function sess_write()
    {
        // set the custom userdata, the session data we will set in a second
        $custom_userdata = $this->userdata;
        $cookie_userdata = array();
        
        // Before continuing, we need to determine if there is any custom data to deal with.
        // Let's determine this by removing the default indexes to see if there's anything left in the array
        // and set the session data while we're at it
        foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
        {
            unset($custom_userdata[$val]);
            $cookie_userdata[$val] = $this->userdata[$val];
        }
        
        // Did we find any custom data?  If not, we turn the empty array into a string
        // since there's no reason to serialize and store an empty array in the DB
        if (count($custom_userdata) === 0)
        {
            $custom_userdata = '';
        }
        else
        {    
            // Serialize the custom data array so we can store it
            $custom_userdata = $this->_serialize($custom_userdata);
        }
        
        // Run the update query
        $this->sess_db->where('session_id', $this->userdata['session_id']);
        $this->sess_db->update($this->sess_table_name, array('last_activity' => $this->userdata['last_activity'], 
        'user_data' => $custom_userdata));

        // Write the cookie.  Notice that we manually pass the cookie data array to the
        // _set_cookie() function. Normally that function will store $this->userdata, but 
        // in this case that array contains custom data, which we do not want in the cookie.
        $this->_set_cookie($cookie_userdata);
    }
    
    /**
    * Create a new session
    *
    * @access    public
    * @return    void
    */
    public function sess_create()
    {    
        parent::sess_create();
        
        $this->sess_db->insert($this->sess_table_name, $this->userdata);
                
        // Write the cookie
        $this->_set_cookie(); 
    }
    
    /**
    * Update an existing session
    *
    * @access    public
    * @return    void
    */
    public function sess_update()
    {
        parent::sess_update();
        
        // Update the session ID and last_activity field in the DB if needed

        // set cookie explicitly to only have our session data
        $cookie_data = array();
        foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
        {
            $cookie_data[$val] = $this->userdata[$val];
        }
    
        $this->sess_db->where('session_id', $this->old_sessid);
        $this->sess_db->update($this->sess_table_name, 
        array('last_activity' => $this->now,'session_id' => $this->new_sessid));
        
        // Write the cookie
        $this->_set_cookie($cookie_data);
    }
    
    /**
    * Destroy the current session
    *
    * @access    public
    * @return    void
    */
    public function destroy()
    {    
        if(isset($this->userdata['session_id']))
        {
            // Kill the session DB row
            $this->sess_db->where('session_id', $this->userdata['session_id']);
            $this->sess_db->delete($this->sess_table_name);
        }
        
        parent::destroy();
    }
    
    /**
    * Garbage collection
    *
    * This deletes expired session rows from database
    * if the probability percentage is met
    *
    * @access    public
    * @return    void
    */
    public function _sess_gc()
    {
        srand(time());
        
        if ((rand() % 100) < $this->gc_probability)
        {
            $expire = $this->now - $this->sess_expiration;
            
            $this->sess_db->where("last_activity < {$expire}");
            $this->sess_db->delete($this->sess_table_name);

            log_message('debug', 'Session garbage collection performed.');
        }
    }
    
}

// END Session Database Driver Class

/* End of file database.php */
/* Location: ./base/libraries/drivers/session/database.php */
?>