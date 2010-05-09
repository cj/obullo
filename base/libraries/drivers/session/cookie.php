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
 
Class OB_Session_cookie_driver extends OB_Session {
    
    /**
    * Session Constructor
    *
    * The constructor runs the session routines automatically
    * whenever the class is instantiated.
    */        
    public function __construct($params = array())
    {
        parent::__construct($params);
    }
    
    /**
    * Start the sessions.
    * 
    */
    public function _session_start()
    {
        parent::_session_start();
    }
    
    
    public function sess_read()
    {
        $session = parent::sess_read();
        
        if($session === FALSE) { return FALSE; }
        
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
        $this->_set_cookie();
        return;  
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
        
        // Write the cookie
        $this->_set_cookie(NULL);
    }

    /**
    * Destroy the current session
    *
    * @access    public
    * @return    void
    */
    public function destroy()
    {    
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
        return;
    }
    
    
}
 
// END Session Cookie Driver Class

/* End of file cookie.php */
/* Location: ./base/libraries/drivers/session/cookie.php */
?>
