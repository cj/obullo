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
 * @filesource
 * @license
 */

Class LogException extends CommonException {}  

/**
 * Logging Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */
if( ! isset($_log->_er)) 
{
    $_log = Ssc::instance();
    $_log->_er = new stdClass();

    $_log->_er->_log_path  = '';
    $_log->_er->_threshold = 1;
    $_log->_er->_date_fmt  = 'Y-m-d H:i:s';
    $_log->_er->_enabled   = TRUE;
    $_log->_er->_levels    = array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4');


    $_config = get_config();
    $_log->_er->_log_path = ($_config['log_path'] != '') ? $_config['log_path'] : APP.'system'.DS.'logs'.DS;

    if ( ! is_dir($_log->_er->_log_path) OR ! is_really_writable($_log->_er->_log_path))
    {
        $_log->_er->_enabled = FALSE;
    }

    if (is_numeric($_config['log_threshold']))
    {
        $_log->_er->_threshold = $_config['log_threshold'];
    }
        
    if ($_config['log_date_format'] != '')
    {
        $_log->_er->_date_fmt = $_config['log_date_format'];
    }
}

// --------------------------------------------------------------------

/**
 * Write Log File
 *
 * Generally this function will be called using the global log_me() function
 *
 * @access   public
 * @param    string    the error level
 * @param    string    the error message
 * @param    bool    whether the error is a native PHP error
 * @return   bool
 */        
if( ! function_exists('log_write') ) 
{
    function log_write($level = 'error', $msg, $php_error = FALSE)
    {        
        $_log = Ssc::instance();
        
        if ($_log->_er->_enabled === FALSE)
        {
            return FALSE;
        }

        $level = strtoupper($level);
        
        if ( ! isset($_log->_er->_levels[$level]) OR ($_log->_er->_levels[$level] > $_log->_er->_threshold))
        {
            return FALSE;
        }

        $filepath = $_log->_er->_log_path.'log-'.date('Y-m-d').EXT;
        $message  = '';
        
        if ( ! file_exists($filepath))
        {
            $message .= "<"."?php  if ( ! defined('BASE')) exit('Access Denied!'); ?".">\n\n";
        }
            
        if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
        {
            return FALSE;
        }

        $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($_log->_er->_date_fmt). ' --> '.$msg."\n";
        
        flock($fp, LOCK_EX);    
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);

        @chmod($filepath, FILE_WRITE_MODE);         
        return TRUE;
    }
}

/* End of file log.php */
/* Location: ./base/helpers/loaded/log.php */