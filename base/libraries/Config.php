<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Framework software for PHP 5.2.4 or newer
 *
 * @package         obullo
 * @filename        base/Common.php        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0  @alpha
 * @filesource
 * @license
 */
 
Class ConfigException extends CommonException {}  

/* 
abstract class Config
{
    abstract public static function get($key);
    //get config item.
    abstract public static function set($key,$val);
    //set config item.
    abstract public static function load($file);
    //load config file.
}
*/

/**
 * CodeIgniter Config Class
 *
 * This class contains functions that enable config files to be managed
 *
 * @package     Obullo
 * @subpackage  Libraries
 * @category    Libraries
 * @author      ExpressionEngine Dev Team
 * @author      Ersin Güvenç
 * @link        
 */
 
class OB_Config {

    static $is_loaded = array();

    /**
    * Load Config File
    *
    * @access    public
    * @param    string    the config file name
    * @return    boolean    if the file was loaded correctly
    */    
    static function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file = ($file == '') ? 'config' : str_replace(EXT, '', $file);
    
        if (in_array($file, self::$is_loaded, TRUE))
        return TRUE;

        if ( ! file_exists(APP.'config/'.$file.EXT))
        {
            if ($fail_gracefully === TRUE)
            return FALSE;
            
            throw new ConfigException('The configuration file '.$file.EXT.' does not exist.');
        }
    
        include(APP.'config/'.$file.EXT);

        if ( ! isset($config) OR ! is_array($config))
        {
            if ($fail_gracefully === TRUE)
            return FALSE;
            
            throw new ConfigException('Your '.$file.EXT.' file does not appear to contain a 
            valid configuration array.');
        }

        if ($use_sections === TRUE)
        {
            if (isset($this->config[$file]))
            {
                self::$config[$file] = array_merge($this->config[$file], $config);
            }
            else
            {
                self::$config[$file] = $config;
            }
        }
        else
        {
            $this->config = array_merge($this->config, $config);
        }

        $this->is_loaded[] = $file;
        unset($config);

        log_message('debug', 'Config file loaded: config/'.$file.EXT);
        return TRUE;
    }
      
    // --------------------------------------------------------------------

    /**
    * Fetch a config file item
    *
    *
    * @access    public
    * @param    string    the config item name
    * @param    string    the index name
    * @param    bool
    * @return    string
    */
    public static function item($item, $index = '')
    {    
        if ($index == '')
        {    
            if ( ! isset(self::$config[$item]))
            {
                return FALSE;
            }

            $pref = $this->config[$item];
        }
        else
        {
            if ( ! isset($this->config[$index]))
            {
                return FALSE;
            }

            if ( ! isset($this->config[$index][$item]))
            {
                return FALSE;
            }

            $pref = $this->config[$index][$item];
        }

        return $pref;
    }
      
      // --------------------------------------------------------------------

    /**
     * Fetch a config file item - adds slash after item
     *
     * The second parameter allows a slash to be added to the end of
     * the item, in the case of a path.
     *
     * @access    public
     * @param    string    the config item name
     * @param    bool
     * @return    string
     */
    function slash_item($item)
    {
        if ( ! isset($this->config[$item]))
        {
            return FALSE;
        }

        $pref = $this->config[$item];

        if ($pref != '' && substr($pref, -1) != '/')
        {    
            $pref .= '/';
        }

        return $pref;
    }
      
    // --------------------------------------------------------------------

    /**
     * Site URL
     *
     * @access    public
     * @param    string    the URI string
     * @return    string
     */
    function site_url($uri = '')
    {
        if (is_array($uri))
        {
            $uri = implode('/', $uri);
        }

        if ($uri == '')
        {
            return $this->slash_item('base_url').$this->item('index_page');
        }
        else
        {
            $suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
            return $this->slash_item('base_url').$this->slash_item('index_page').preg_replace("|^/*(.+?)/*$|", "\\1", $uri).$suffix;
        }
    }
    
    // --------------------------------------------------------------------

    /**
     * System URL
     *
     * @access    public
     * @return    string
     */
    function system_url()
    {
        $x = explode("/", preg_replace("|/*(.+?)/*$|", "\\1", BASEPATH));
        return $this->slash_item('base_url').end($x).'/';
    }
      
    // --------------------------------------------------------------------

    /**
     * Set a config file item
     *
     * @access    public
     * @param    string    the config item key
     * @param    string    the config item value
     * @return    void
     */
    function set_item($item, $value)
    {
        $this->config[$item] = $value;
    }

} // end class

?>
