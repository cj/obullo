<?php 
defined('BASE') or exit('Access Denied!');
 
/**
* Obullo Framework (c) 2010.
* Procedural Session Implementation With stdClass. 
* Less coding, Less Memory.
* 
* @author      Ersin Guvenc.
* 
*/
Class SessionException extends CommonException {}

if( ! isset($_ses->_sion)) 
{
    $_ses = Ssc::instance();
    $_ses->_sion = new stdClass();
    $_ses->_sion->sess_encrypt_cookie  = FALSE;
    $_ses->_sion->sess_expiration      = '7200';
    $_ses->_sion->sess_match_ip        = FALSE;
    $_ses->_sion->sess_match_useragent = TRUE;
    $_ses->_sion->sess_cookie_name     = 'ob_session';
    $_ses->_sion->cookie_prefix        = '';
    $_ses->_sion->cookie_path          = '';
    $_ses->_sion->cookie_domain        = '';
    $_ses->_sion->sess_time_to_update  = 300;
    $_ses->_sion->encryption_key       = '';
    $_ses->_sion->flashdata_key        = 'flash';
    $_ses->_sion->time_reference       = 'time';
    $_ses->_sion->gc_probability       = 5;
    $_ses->_sion->userdata             = array();
}
 
/**
* Be carefull you shouldn't declare sess_start
* function more than one time, this is important
* for your application performance. But don't worry
* it will return to false automatically !!
* 
* @see Chapter / Helpers / Session Helper
* 
* @author   Ersin Guvenc
* @param    mixed $params
* @version  0.1
* @version  0.2  added extend support for driver files.
*/
if( ! function_exists('sess_start')) 
{
    function sess_start($params = array())
    {   
        static $session_start = NULL;
        
        if ($session_start == NULL)
        {
            $driver = (isset($params['sess_driver'])) ? $params['sess_driver'] : config_item('sess_driver');
            
            // Driver extend support
            $prefix      = config_item('subhelper_prefix');
            $driver_file = APP .'helpers'. DS .'drivers'. DS .'session'. DS .$prefix. $driver.'_driver'. EXT;
            
            if(file_exists($driver_file))
            {
                require($driver_file);
                loader::$_base_helpers[$prefix . $driver.'_driver'] = $prefix . $driver.'_driver';
            }
            
            loader::file('helpers'. DS .'drivers'. DS .'session'. DS .$driver.'_driver'. EXT, false, BASE);
            loader::$_base_helpers[$driver.'_driver'] = $driver.'_driver';

            _sess_start($params);
            $session_start = TRUE;
            return TRUE;
        }
        
        log_me('debug', "Sessions started"); 
        
        return FALSE;
    }
}

/* End of file session.php */
/* Location: ./base/helpers/session.php */