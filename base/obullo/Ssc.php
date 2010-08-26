<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo        
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009 - 2010.
 * @since           Version 1.0
 * @filesource
 * @license
 */
 
/**
* Super Static Controllers (SSC). (c) 2010.
* Control the Procedural (static) Functions.
* 
* @author   Ersin Guvenc
*/
Class ssc {
    
    private static $instance;
    
    public $_ng   = NULL; // la_ng
    public $_er   = NULL; // log_er
    public $_vi   = NULL; // vi_ew
    public $_put  = NULL; // in_put
    public $_ity  = NULL; // secur_ity 
    public $_sion = NULL; // ses_sion
    public $_mark = NULL; // bench_mark
    
    public $_profiler_mods  = array(); // profiler models
    public $_profiler_libs  = array(); // profiler libs
    public $_profiler_files = array(); // profiler files
    public $_profiler_scripts        = array(); // profiler scripts
    public $_profiler_app_views      = array(); // profiler app views
    public $_profiler_local_views    = array(); // profiler local views
    public $_profiler_loaded_helpers = array(); // profiler loaded helpers
    public $_profiler_config_files   = array(); // profiler config files
    public $_profiler_lang_files     = array(); // profiler lang files
    
    public static function instance()
    {
        if( ! isset(self::$instance))
        {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
}

// END ssc Class

/* End of file Ssc.php */
/* Location: ./base/obullo/Ssc.php */ 
?>