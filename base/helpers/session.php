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