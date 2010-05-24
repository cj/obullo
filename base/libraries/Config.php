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
 
Class ConfigException extends CommonException {}  

/**
 * Obullo Config Class

 * This class contains functions that enable config files to be managed
 *
 * @package     Obullo
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Ersin Guvenc
 * @link        
 */
Class OB_Config {
    
    public $config    = array();
    public $is_loaded = array();

    /**
     * Constructor
     *
     * Sets the $config data from the primary config.php file as a class variable
     *
     * @access  public
     * @param   string   the config file name
     * @param   boolean  if configuration values should be loaded into their own section
     * @param   boolean  true if errors should just return false, false if an error message should be displayed
     * @return  boolean  if the file was successfully loaded or not
     */
    public function __construct()
    {
        $this->config = get_config();
        
        log_message('debug', "Config Class Initialized");
    }
      
    // --------------------------------------------------------------------
    
    /**
    * Load Config File
    *
    * @access   public
    * @param    string    the config file name
    * @return   boolean   if the file was loaded correctly
    */    
    public function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file = ($file == '') ? 'config' : str_replace(EXT, '', $file);
    
        if (in_array($file, $this->is_loaded, TRUE))
        {
            return TRUE;
        }

        if ( ! file_exists(APP .'config'. DS .$file. EXT))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            
            throw new ConfigException('The configuration file '.$file. EXT .' does not exist.');
        }
    
        include(APP .'config'. DS .$file. EXT);

        if ( ! isset($config) OR ! is_array($config))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            
            throw new ConfigException('Your '.$file. EXT.' file does not appear to contain a valid configuration array.');
        }

        if ($use_sections === TRUE)
        {
            if (isset($this->config[$file]))
            {
                $this->config[$file] = array_merge($this->config[$file], $config);
            }
            else
            {
                $this->config[$file] = $config;
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
    * @access   public
    * @param    string    the config item name
    * @param    string    the index name
    * @param    bool
    * @return   string
    */
    public function item($item, $index = '')
    {    
        if ($index == '')
        {    
            if ( ! isset($this->config[$item]))
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
    * @access   public
    * @param    string    the config item name
    * @param    bool
    * @return   string
    */
    public function slash_item($item)
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
    * @access   public
    * @param    string    the URI string
    * @return   string
    */
    public function site_url($uri = '')
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
    * Set a config file item
    *
    * @access   public
    * @param    string    the config item key
    * @param    string    the config item value
    * @return   void
    */
    public function set_item($item, $value)
    {
        $this->config[$item] = $value;
    }

}

// END Config Class

/* End of file Config.php */
/* Location: ./base/libraries/Config.php */
?>