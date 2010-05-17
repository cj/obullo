<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo      
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */

/**
 * Session Class
 *
 * @package      Obullo
 * @subpackage   Libraries
 * @category     Sessions
 * @author       Ersin Guvenc
 * @link         
 */
Abstract Class OB_Session {

    public $sess_encrypt_cookie        = FALSE;
    public $sess_driver                = 'cookie';  // Obullo changes .. 
    public $sess_db_var                = 'db';      // Obullo changes .. 
    public $sess_table_name            = '';
    public $sess_expiration            = 7200;
    public $sess_match_ip              = FALSE;
    public $sess_match_useragent       = TRUE;
    public $sess_cookie_name           = 'ob_session';
    public $cookie_prefix              = '';
    public $cookie_path                = '';
    public $cookie_domain              = '';
    public $sess_time_to_update        = 300;
    public $encryption_key             = '';
    public $flashdata_key              = 'flash';
    public $time_reference             = 'time';
    public $gc_probability             = 5;
    public $userdata                   = array();
    public $OB                         = NULL;
    public $now;
    
    // Obullo changes ..
    public $sess_db                    = NULL;
    public $old_sessid                 = NULL;
    public $new_sessid                 = NULL;

    /**
    * Session Constructor
    *
    * The constructor runs the session routines automatically
    * whenever the class is instantiated.
    */        
    public function __construct($params = array())
    {
        log_message('debug', "Session Class Initialized");

        // Set the super object to a local variable for use throughout the class
        $this->OB = ob::instance();
        
        // Set all the session preferences, which can either be set 
        // manually via the $params array above or via the config file
        foreach (array('sess_encrypt_cookie', 'sess_driver', 'sess_db_var', 'sess_table_name', 
        'sess_expiration', 'sess_match_ip', 'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 
        'cookie_domain', 'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key)
        {
            $this->$key = (isset($params[$key])) ? $params[$key] : config_item($key);
        }
                
        // Load the string helper so we can use the strip_slashes() function
        loader::base_helper('string');
        
        // Set the "now" time.  Can either be GMT or server time, based on the
        // config prefs.  We use this to set the "last activity" time
        $this->now = $this->_get_time();

        // Set the session length. If the session expiration is
        // set to zero we'll set the expiration two years from now.
        if ($this->sess_expiration == 0)
        $this->sess_expiration = (60*60*24*365*2);
                         
        // Set the cookie name
        $this->sess_cookie_name = $this->cookie_prefix.$this->sess_cookie_name;
        
        $this->_session_start();
    }
              
    /**
    * Start the sessions.
    * 
    */
    public function _session_start()
    {
        // abstract driver functions here.
        
        // Run the Session routine. If a session doesn't exist we'll 
        // create a new one.  If it does, we'll update it
        if ( ! $this->sess_read())
        {
            $this->sess_create();
        }
        else
        {    
            $this->sess_update();
        }
        
        // Delete 'old' flashdata (from last request)
        $this->_flashdata_sweep();
        
        // Mark all new flashdata as old (data will be deleted before next request)
        $this->_flashdata_mark();

        // Delete expired sessions if necessary
        $this->_sess_gc();

        log_message('debug', "Session routines successfully run");
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Fetch the current session data if it exists
    *
    * @access    public
    * @return    array() sessions.
    */
    public function sess_read()
    {    
        // Fetch the cookie
        $session = $this->OB->input->cookie($this->sess_cookie_name);
    
        // No cookie?  Goodbye cruel world!...
        if ($session === FALSE)
        {               
            log_message('debug', 'A session cookie was not found.');
            return FALSE;
        }
        
        // Decrypt the cookie data
        if ($this->sess_encrypt_cookie == TRUE)
        {
            $encrypt = encrypt::instance();
            $encrypt->init();
            
            $session = $encrypt->decode($session);
        }
        else
        {    
            // encryption was not used, so we need to check the md5 hash
            $hash    = substr($session, strlen($session)-32); // get last 32 chars
            $session = substr($session, 0, strlen($session)-32);

            // Does the md5 hash match?  This is to prevent manipulation of session data in userspace
            if ($hash !==  md5($session.$this->encryption_key))
            {
                log_message('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt.');
                
                $this->destroy();
                return FALSE;
            }
        }
        
        // Unserialize the session array
        $session = $this->_unserialize($session);
        
        // Is the session data we unserialized an array with the correct format?
        if ( ! is_array($session) OR ! isset($session['session_id']) OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) OR ! isset($session['last_activity'])) 
        {               
            $this->destroy();
            return FALSE;
        }
        
        // Is the session current?
        if (($session['last_activity'] + $this->sess_expiration) < $this->now)
        {
            $this->destroy();
            return FALSE;
        }

        // Does the IP Match?
        if ($this->sess_match_ip == TRUE AND $session['ip_address'] != $this->OB->input->ip_address())
        {
            $this->destroy();
            return FALSE;
        }
        
        // Does the User Agent Match?
        if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr($this->OB->input->user_agent(), 0, 50)))
        {
            $this->destroy();
            return FALSE;
        }
        
        return $session;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Write the session data
     *
     * @access    public
     * @return    void
     */
    public function sess_write()
    {
        return;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Create a new session
    *
    * @access    public
    * @return    void
    */
    public function sess_create()
    {    
        $sessid = '';
        while (strlen($sessid) < 32)
        {
            $sessid .= mt_rand(0, mt_getrandmax());
        }
        
        // To make the session ID even more secure we'll combine it with the user's IP
        $sessid .= $this->OB->input->ip_address();
    

        $this->userdata = array(
                            'session_id'     => md5(uniqid($sessid, TRUE)),
                            'ip_address'     => $this->OB->input->ip_address(),
                            'user_agent'     => substr($this->OB->input->user_agent(), 0, 50),
                            'last_activity'  => $this->now
                            );
        
        // Write the cookie
        // none abstract $this->_set_cookie(); 
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Update an existing session
    *
    * @access    public
    * @return    void
    */
    public function sess_update()
    {
        // We only update the session every five minutes by default
        if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
        {
            return;
        }
    
        // Save the old session id so we know which record to 
        // update in the database if we need it
        $this->old_sessid = $this->userdata['session_id'];
        $new_sessid = '';
        while (strlen($new_sessid) < 32)
        {
            $new_sessid .= mt_rand(0, mt_getrandmax());
        }
        
        // To make the session ID even more secure we'll combine it with the user's IP
        $new_sessid .= $this->OB->input->ip_address();
        
        // Turn it into a hash
        $this->new_sessid = md5(uniqid($new_sessid, TRUE));
        
        // Update the session data in the session data array
        $this->userdata['session_id']    = $this->new_sessid;
        $this->userdata['last_activity'] = $this->now;
        
        // _set_cookie() will handle this for us if we aren't using database sessions
        // by pushing all userdata to the cookie.
        $cookie_data = NULL;
        
        // Write the cookie
        // none abstract $this->_set_cookie($cookie_data);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Destroy the current session
    *
    * @access    public
    * @return    void
    */
    public function destroy()
    {   
        // Kill the cookie
        setcookie(
                    $this->sess_cookie_name, 
                    addslashes(serialize(array())), 
                    ($this->now - 31500000), 
                    $this->cookie_path, 
                    $this->cookie_domain, 
                    FALSE
                    );
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Fetch a specific item from the session array
    *
    * @access   public
    * @param    string
    * @return   string
    */        
    public function get($item) // obullo changes ...
    {
        return ( ! isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
    }

    // --------------------------------------------------------------------
    
    /**
    * Fetch all session data
    *
    * @access    public
    * @return    mixed
    */    
    public function all_userdata()
    {
        return ( ! isset($this->userdata)) ? FALSE : $this->userdata;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Add or change data in the "userdata" array
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @return   void
    */        
    public function set($newdata = array(), $newval = '') // obullo changes ...
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }
    
        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $this->userdata[$key] = $val;
            }
        }

        $this->sess_write();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Delete a session variable from the "userdata" array
    *
    * @access    array
    * @return    void
    */        
    public function un_set($newdata = array())  // obullo changes ...
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => '');
        }
    
        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                unset($this->userdata[$key]);
            }
        }
    
        $this->sess_write();
    }
    
    // ------------------------------------------------------------------------

    /**
    * Add or change flashdata, only available
    * until the next request
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @return   void
    */
    public function set_flash($newdata = array(), $newval = '') // obullo changes ...
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }
        
        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $flashdata_key = $this->flashdata_key.':new:'.$key;
                $this->set($flashdata_key, $val);
            }
        }
    } 
    
    // ------------------------------------------------------------------------

    /**
    * Keeps existing flashdata available to next request.
    *
    * @access   public
    * @param    string
    * @return   void
    */
    public function keep_flash($key) // obullo changes ...
    {
        // 'old' flashdata gets removed.  Here we mark all 
        // flashdata as 'new' to preserve it from _flashdata_sweep()
        // Note the function will return FALSE if the $key 
        // provided cannot be found
        $old_flashdata_key = $this->flashdata_key.':old:'.$key;
        $value = $this->get($old_flashdata_key);

        $new_flashdata_key = $this->flashdata_key.':new:'.$key;
        $this->set($new_flashdata_key, $value);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Fetch a specific flashdata item from the session array
    *
    * @access   public
    * @param    string
    * @return   string
    */    
    public function get_flash($key) // obullo changes ...
    {
        $flashdata_key = $this->flashdata_key.':old:'.$key;
        return $this->get($flashdata_key);
    }

    // ------------------------------------------------------------------------

    /**
    * Identifies flashdata as 'old' for removal
    * when _flashdata_sweep() runs.
    *
    * @access    private
    * @return    void
    */
    private function _flashdata_mark()
    {
        $userdata = $this->all_userdata();
        foreach ($userdata as $name => $value)
        {
            $parts = explode(':new:', $name);
            if (is_array($parts) && count($parts) === 2)
            {
                $new_name = $this->flashdata_key.':old:'.$parts[1];
                $this->set($new_name, $value);
                $this->un_set($name);
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
    * Removes all flashdata marked as 'old'
    *
    * @access    private
    * @return    void
    */
    private function _flashdata_sweep()
    {
        $userdata = $this->all_userdata();
        foreach ($userdata as $key => $value)
        {
            if (strpos($key, ':old:'))
            {
                $this->un_set($key);
            }
        }

    }

    // --------------------------------------------------------------------
    
    /**
    * Get the "now" time
    *
    * @access    private
    * @return    string
    */
    private function _get_time()
    {   
        $time = time();
        if (strtolower($this->time_reference) == 'gmt')
        {
            $now  = time();
            $time = mktime( gmdate("H", $now), 
            gmdate("i", $now), 
            gmdate("s", $now), 
            gmdate("m", $now), 
            gmdate("d", $now), 
            gmdate("Y", $now)
            );
        }
        
        return $time;
    }

    // --------------------------------------------------------------------
    
    /**
    * Write the session cookie
    *
    * @access    public
    * @return    void
    */
    public function _set_cookie($cookie_data = NULL)
    {
        if (is_null($cookie_data))
        {
            $cookie_data = $this->userdata;
        }
    
        // Serialize the userdata for the cookie
        $cookie_data = $this->_serialize($cookie_data);
        
        if ($this->sess_encrypt_cookie == TRUE)
        {
            $encrypt = encrypt::instance();
            $encrypt->init();
        
            $cookie_data = $encrypt->encode($cookie_data);
        }
        else
        {
            // if encryption is not used, we provide an md5 hash to prevent userside tampering
            $cookie_data = $cookie_data . md5($cookie_data.$this->encryption_key);
        }
        
        // Set the cookie
        setcookie(
                    $this->sess_cookie_name,
                    $cookie_data,
                    $this->sess_expiration + time(),
                    $this->cookie_path,
                    $this->cookie_domain,
                    0
                );
    }

    // --------------------------------------------------------------------
    
    /**
    * Serialize an array
    *
    * This function first converts any slashes found in the array to a temporary
    * marker, so when it gets unserialized the slashes will be preserved
    *
    * @access   private
    * @param    array
    * @return   string
    */    
    protected function _serialize($data)
    {
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                $data[$key] = str_replace('\\', '{{slash}}', $val);
            }
        }
        else
        {
            $data = str_replace('\\', '{{slash}}', $data);
        }
        
        return serialize($data);
    }

    // --------------------------------------------------------------------
    
    /**
    * Unserialize
    *
    * This function unserializes a data string, then converts any
    * temporary slash markers back to actual slashes
    *
    * @access    private
    * @param    array
    * @return    string
    */        
    public function _unserialize($data)
    {
        $data = @unserialize(strip_slashes($data));
        
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                $data[$key] = str_replace('{{slash}}', '\\', $val);
            }
            
            return $data;
        }
        
        return str_replace('{{slash}}', '\\', $data);
    }

    // --------------------------------------------------------------------
    
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
        return;
    }

    
}
// END Session Class

/* End of file Session.php */
/* Location: ./base/libraries/Session.php */
?>