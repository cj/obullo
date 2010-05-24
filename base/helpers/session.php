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

$_ses = ssc::instance();
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
 
/**
* Be carefull you shouldn't declare sess_start
* function more than one time, this is important
* for your application performance. But don't worry
* about if you declare this func. more than one time
* it will return to false automatically !!
* 
* @author   Ersin Guvenc
* @param    mixed $params
*/
function sess_start($params = array())
{   
    static $session_start = NULL;
    
    if ($session_start == NULL)
    {
        $driver = (isset($params['sess_driver'])) ? $params['sess_driver'] : config_item('sess_driver');
        loader::file('helpers'. DS .'drivers'. DS .'session'. DS .$driver.'_driver', false, BASE);
    
        _sess_start($params);
        $session_start = TRUE;
        return TRUE;
    }
    
    log_message('debug', "Sessions started"); 
    
    return FALSE;
}

/* End of file session.php */
/* Location: ./base/helpers/session.php */
?>