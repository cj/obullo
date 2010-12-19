<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2010.
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Profiler Class
 *
 * This class enables you to display benchmark, query, and other data.
 * 
 * @package     Obullo
 * @subpackage  Libraries
 * @category    Libraries
 * @author      
 * @see         Chapters / General Topics / Profiling Your Application
 */
Class OB_Profiler {
    
     public function __construct()
     {
         lang_load('profiler');
         loader::base_helper('view');
     }
     
    // --------------------------------------------------------------------

    /**
     * Auto Profiler
     *
     * This function cycles through the entire array of mark points and
     * matches any two points that are named identically (ending in "_start"
     * and "_end" respectively).  It then compiles the execution times for
     * all points and returns it as an array
     *
     * @access    private
     * @return    array
     */
     public function _compile_benchmarks()
     {
         $profile = array();
         
         $_bench = Ssc::instance();
          
         foreach ($_bench->_mark->marker as $key => $val)
         {
             // We match the "end" marker so that the list ends
             // up in the order that it was defined
             if (preg_match("/(.+?)_end/i", $key, $match))
             {             
                 if (isset($_bench->_mark->marker[$match[1].'_end']) AND isset($_bench->_mark->marker[$match[1].'_start']))
                 {
                     $profile[$match[1]] = benchmark_elapsed_time($match[1].'_start', $key);
                 }
             }
         }

        // Build a table containing the profile data.
        // Note: At some point we should turn this into a template that can
        // be modified.  We also might want to make this data available to be logged
    
        $output  = '<div id="benchmark">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang('profiler_benchmarks')."</th></tr>";
        
        foreach ($profile as $key => $val)
        {
            $key = ucwords(str_replace(array('_', '-'), ' ', $key));
            $output .= "<tr><td class=\"td\">".$key."&nbsp;&nbsp;</td><td class=\"td_val\">".$val."</td></tr>";
        }
        
        $output .= "</table>";
        $output .= "</div>";
         
        return $output;
    
     }
     
    // --------------------------------------------------------------------

    /**
     * Compile Queries
     *
     * @access    private
     * @return    string
     */    
    public function _compile_queries()
    {
        $ob = this();
        $total_dbs = profiler_get('databases');
        
        // Let's determine which databases are currently connected to         
        if (count($total_dbs) == 0)
        {    
            $output  = '<div id="queries">';       
            $output .= "<table class=\"tableborder\">";
            $output .= "<tr><th align='center'>".lang('profiler_queries')."</th></tr>";
            
            $output .= "<tr><td class=\"td_val\">".lang('profiler_no_db')."</td></tr>";

            $output .= "</table>";
            $output .= "</div>";
        
            return $output;
        }
        
        // Load the text helper so we can highlight the SQL
        loader::base_helper('text');

        // Key words we want bolded
        $highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

        $output  = "";
            
        foreach ($total_dbs as $db_name => $db_var)
        {
            if(isset($ob->{$db_var}))
            {
                $total_queries = count($ob->{$db_var}->cached_queries) + count($ob->{$db_var}->queries);
                
                $output .= '<div id="queries">';
                $output .= "<table class=\"tableborder\">"; 
                $output .= "<tr><th>".lang('profiler_queries').": ".$total_queries."&nbsp;&nbsp;&nbsp;</th></tr>";
                
                //---------------------- Direct Queries ---------------------------//
                
                if ($total_queries == 0)
                {
                    $output .= "<tr><td class=\"td_val\">".lang('profiler_no_queries')."</td></tr>";
                }
                else
                {   
                    $output .= "<tr><td valign='top' class=\"td\"><span class='label'>Database</span></td>";
                    $output .= "<td class=\"td_val\">".$db_name."</td></tr>";
                    
                    foreach ($ob->{$db_var}->queries as $key => $val)
                    {   
                        $time = '';
                        if(isset($ob->{$db_var}->query_times[$key])) 
                        {
                            $time = number_format($ob->{$db_var}->query_times[$key], 4);
                        }
                        
                        $val = wordwrap($val, 60,"\n");
                        $val = highlight_code($val, ENT_QUOTES);
                        
                        // remove all spaces and newlines, prevent js errors.
                        $val = preg_replace('/[\t\s]+/s', ' ', $val);   // ( Obullo Changes )
                        $val = preg_replace('/[\r\n]/', '<br />', $val);
                        
                        foreach ($highlight as $bold)
                        {
                            $val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);    
                        }
                        
                        $output .= "<tr><td valign='top' class=\"td\"><span class='label'>";
                        $output .= $time."</span>&nbsp;&nbsp;</td><td class=\"td_val\">".$val."</td></tr>";
                    }
                }
                
                //---------------------- Cached  Queries ---------------------------//
                
                if (count($ob->{$db_var}->cached_queries) == 0)
                {
                    $output .= "";
                }
                else
                {   
                    $i = 0;
                    $is_cached = '';
                    foreach ($ob->{$db_var}->cached_queries as $key => $val)
                    {   
                        ++$i;
                        
                        if(isset($ob->{$db_var}->query_times['cached'][$key])) 
                        {
                            $time = number_format($ob->{$db_var}->query_times['cached'][$key], 4);
                        } 
                         else 
                        {
                            $time = '<span class="notice">exec not exist !</span>';
                        } 
                        
                        $val = wordwrap($val, 60,"\n");
                        $val = highlight_code($val, ENT_QUOTES);
                        
                        // remove all spaces and newlines, prevent javascript errors.
                        $val = preg_replace('/[\t\s]+/s', ' ', $val);   // ( Obullo Changes )
                        $val = preg_replace('/[\r\n]/', '<br />', $val);

                        
                        foreach ($highlight as $bold)
                        {
                            $val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);    
                        }
                        
                        if($i > 1) 
                        {
                            $is_cached = '&nbsp;<span class="cached_query">(Cached)</span>';
                        }
                        
                        $output .= "<tr><td valign='top' class=\"td\"><span class='label'>" .$time. $is_cached;
                        $output .= "</span>&nbsp;&nbsp;</td><td class=\"td_val\">".$val."</td></tr>";
                    }
                }
                
                $output .= "</table>";
                $output .= "</div>";
            
            } // end isset
        
        } // end foreach
        
        return $output; 
    }
    
    // --------------------------------------------------------------------

    /**
     * Compile $_GET Data
     *
     * @access    private
     * @return    string
     */    
    public function _compile_get()
    {    
        $output  = '<div id="get">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang('profiler_get_data')."</th></tr>";

        if (count($_GET) == 0)
        {
            $output .= "<tr><td class=\"td_val\">".lang('profiler_no_get')."</td></tr>";
        }
            else
        {
            foreach ($_GET as $key => $val)
            {
                if ( ! is_numeric($key))
                {
                    $key = "'".$key."'";
                }
            
                $output .= "<tr><td class=\"td\">&#36;_GET[".$key."]&nbsp;&nbsp;</td><td class=\"td_val\">";
                if (is_array($val))
                {
                    $output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, true))) . "</pre>";
                }
                else
                {
                    $output .= htmlspecialchars(stripslashes($val));
                }
                $output .= "</td></tr>";
            }
            
        }
        
        $output .= "</table>";
        $output .= "</div>";

        return $output;    
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Compile $_POST Data
     *
     * @access    private
     * @return    string
     */     
    public function _compile_post()
    {    
        $output  = '<div id="post">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang('profiler_post_data')."</th></tr>";
                
        if (count($_POST) == 0)
        {
            $output .= "<tr><td class=\"td_val\">".lang('profiler_no_post')."</td></tr>";
        }
        else
        {
            foreach ($_POST as $key => $val)
            {
                if ( ! is_numeric($key))
                {
                    $key = "'".$key."'";
                }
            
                $output .= "<tr><td class=\"td\">&#36;_POST[".$key."]&nbsp;&nbsp;</td><td class=\"td_val\">";
                if (is_array($val))
                {
                    $output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, true))) . "</pre>";
                }
                else
                {
                    $output .= htmlspecialchars(stripslashes($val));
                }
                $output .= "</td></tr>";
            }
            
        }
        
        $output .= "</table>";
        $output .= "</div>";

        return $output;    
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Show query string
     *
     * @access    private
     * @return    string
     */    
    public function _compile_uri_string()
    {    
        $ob = this();

        $output  = '<div id="uri_string">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang('profiler_uri_string')."</th></tr>";
        
        if ($ob->uri->uri_string == '')
        {
            $output .= "<tr><td class=\"td_val\">".lang('profiler_no_uri')."</td></tr>";
        }
        else
        {
            $output .= "<tr><td class=\"td_val\">".$ob->uri->uri_string."</td></tr>";
        }
        
        $output .= "</table>";
        $output .= "</div>";

        return $output;    
    }

    // --------------------------------------------------------------------
    
    /**
     * Show the controller and function that were called
     *
     * @access    private
     * @return    string
     */    
    public function _compile_controller_info()
    {            
        $output  = '<div id="controller_info">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang('profiler_controller_info')."</th></tr>";

        $output .= "<tr><td class=\"td_val\">".$GLOBALS['d'].' / '.$GLOBALS['c'].' / '.$GLOBALS['m']."</td></tr>";
        
        $output .= "</table>";
        $output .= "</div>";
        return $output;    
    }
    // --------------------------------------------------------------------
    
    /**
     * Compile memory usage
     *
     * Display total used memory
     *
     * @access    public
     * @return    string
     */
    public function _compile_memory_usage()
    {
        $output  = '<div id="memory">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang('profiler_memory_usage')."</th></tr>";
        
        if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
        {
            $output .= "<tr><td class=\"td_val\">".number_format($usage)." bytes</td></tr>";
        }
        else
        {
            $output .= "<tr><td class=\"td_val\">".lang('profiler_no_memory_usage')."</td></tr>";
        }
        
        $output .= "</table>";
        $output .= "</div>";
        return $output;  
    }

    // --------------------------------------------------------------------
    
    /**
     * Compile memory usage
     *
     * Display total used memory
     *
     * @access    public
     * @return    string
     */
    public function _compile_loaded_files()
    {          
        $ob  = this();
        $helper_prefix   = config_item('subhelper_prefix');
        $subclass_prefix = config_item('subclass_prefix');
    
        $output  = '<div id="loaded_files">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang('profiler_loaded_files')."</th></tr>";
        
        $config_files = '';
        foreach(profiler_get('config_files') as $config_file) { $config_files .= $config_file .'<br />'; }
        
        $lang_files   = '';
        foreach(profiler_get('lang_files') as $lang_file) { $lang_files .= $lang_file .'<br />'; }
        
        $base_helpers  = '';
        foreach(loader::$_base_helpers as $base_helper) 
        { 
            if(strpos($base_helper, $helper_prefix) === 0)
            {
                $base_helpers .= str_replace($helper_prefix, "<span class='subhelper_prefix'>$helper_prefix</span>", $base_helper).'<br />';
            } 
            else 
            {
                $base_helpers .= $base_helper .'<br />';
            }
        }
        
        $loaded_helpers  = '';
        foreach(profiler_get('loaded_helpers') as $loaded_helper) 
        { 
            if(strpos($loaded_helper, $helper_prefix) === 0)
            {
                $loaded_helpers .= str_replace($helper_prefix, "<span class='subhelper_prefix'>$helper_prefix</span>", $loaded_helper).'<br />';
            } 
            else 
            {
                $loaded_helpers .= $loaded_helper .'<br />';
            }
        }
                    
        $app_helpers  = '';
        foreach(loader::$_app_helpers as $app_helper) { $app_helpers .= $app_helper .'<br />'; }
        
        $helpers  = '';
        foreach(loader::$_helpers as $helper) { $helpers .= $helper .'<br />'; }
        
        $libraries  = '';
        foreach(profiler_get('libraries') as $lib_key => $lib) 
        { 
            if(strpos($lib, $subclass_prefix) === 0)
            {
                $libraries .= str_replace($subclass_prefix, "<span class='subclass_prefix'>$subclass_prefix</span>", $lib).'<br />';
            } 
            else
            {
                $libraries .= $lib .'<span class="class_operations"> (' . $lib_key . ') </span><br />'; 
            }

        }
        
        $models  = '';
        foreach(profiler_get('models') as $mod) { $models .= $mod .'<br />'; }
              
        $databases  = '';
        foreach(profiler_get('databases') as $db_name => $db_var) { $databases .= $db_var.'<br />'; }
        
        $scripts  = '';
        foreach(profiler_get('scripts') as $scr) { $scripts .= $scr .'<br />'; }
        
        $files  = '';              
        foreach(profiler_get('files') as $file) { $files .= $file .'<br />'; }
        
        $local_views  = '';
        foreach(profiler_get('local_views') as $view) { $local_views .= $view .'<br /> '; }
    
        $app_views  = '';
        foreach(profiler_get('app_views') as $view) { $app_views .= $view .'<br /> '; }
        
        $base_helpers   = (isset($base_helpers{2}))   ? $base_helpers : '-';
        $app_helpers    = (isset($app_helpers{2}))    ? $app_helpers : '-';
        $loaded_helpers = (isset($loaded_helpers{2})) ? $loaded_helpers : '-';
        $helpers        = (isset($helpers{2}))        ? $helpers : '-';
        $libraries      = (isset($libraries{2}))      ? $libraries : '-';
        $models         = (isset($models{2}))         ? $models : '-';
        $databases      = (isset($databases{2}))      ? $databases : '-';
        $scripts        = (isset($scripts{2}))        ? $scripts : '-';
        $files          = (isset($files{2}))          ? $files : '-';
        
        $output .= "<tr><td class=\"td\">Config Files&nbsp;&nbsp;</td><td class=\"td_val\">".$config_files."</td></tr>";  
        $output .= "<tr><td class=\"td\">Lang Files&nbsp;&nbsp;</td><td class=\"td_val\">".$lang_files."</td></tr>";  
        $output .= "<tr><td class=\"td\">Base Helpers&nbsp;&nbsp;</td><td class=\"td_val\">".$base_helpers."</td></tr>";  
        $output .= "<tr><td class=\"td\">Application Helpers&nbsp;&nbsp;</td><td class=\"td_val\">".$app_helpers."</td></tr>";    
        $output .= "<tr><td class=\"td\">Loaded Helpers&nbsp;&nbsp;</td><td class=\"td_val\">".$loaded_helpers."</td></tr>";    
        $output .= "<tr><td class=\"td\">Local Helpers&nbsp;&nbsp;</td><td class=\"td_val\">".$helpers."</td></tr>";    
        $output .= "<tr><td class=\"td\">Libraries&nbsp;&nbsp;</td><td class=\"td_val\">".$libraries."</td></tr>";    
        $output .= "<tr><td class=\"td\">Models&nbsp;&nbsp;</td><td class=\"td_val\">".$models."</td></tr>";    
        $output .= "<tr><td class=\"td\">Databases&nbsp;&nbsp;</td><td class=\"td_val\">".$databases."</td></tr>";    
        $output .= "<tr><td class=\"td\">Scripts&nbsp;&nbsp;</td><td class=\"td_val\">".$scripts."</td></tr>";    
        $output .= "<tr><td class=\"td\">Local Views&nbsp;&nbsp;</td><td class=\"td_val\">".$local_views."</td></tr>";    
        $output .= "<tr><td class=\"td\">Application Views&nbsp;&nbsp;</td><td class=\"td_val\">".$app_views."</td></tr>";    
        $output .= "<tr><td class=\"td\">External Files&nbsp;&nbsp;</td><td class=\"td_val\">".$files."</td></tr>";    
        
        $output .= "</table>";
        $output .= "</div>";
         
        return $output;  
    }
    
    // --------------------------------------------------------------------
   
    /**
    * Run the Profiler
    *
    * @access    public
    * @return    string
    */    
    public function run()
    {
        $output  = "<div id=\"obullo_profiler\">";
        $output .= $this->_compile_uri_string();
        $output .= $this->_compile_controller_info();
        $output .= $this->_compile_memory_usage();
        $output .= $this->_compile_benchmarks();
        $output .= $this->_compile_get();
        $output .= $this->_compile_post();
        $output .= $this->_compile_loaded_files();
        $output .= $this->_compile_queries();
        $output .= '</div>';


        return $output;
    }


}

// END OB_Profiler class

/* End of file Profiler.php */
/* Location: ./base/libraries/Profiler.php */
