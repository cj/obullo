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
         loader::base_helper('content');
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
     private function _compile_benchmarks()
     {
         $profile = array();
         
         $_bench = ssc::instance();
          
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
        $output .= "<tr><th>".lang_item('profiler_benchmarks')."</th></tr>";
        
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
    private function _compile_queries()
    {
        $ob = ob::instance();
        
        // Let's determine which databases are currently connected to         
        if (count($ob->_dbs) == 0)
        {    
            $output  = '<div id="queries">';       
            $output .= "<table class=\"tableborder\">";
            $output .= "<tr><th>".lang_item('profiler_queries')."</th></tr>";
            
            $output .= "<tr><td class=\"td_val\">".lang_item('profiler_no_db')."</td></tr>";

            $output .= "</table>";
            $output .= "</div>";
        
            return $output;
        }
        
        // Load the text helper so we can highlight the SQL
        loader::base_helper('text');

        // Key words we want bolded
        $highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

        $output  = "";
            
        foreach ($ob->_dbs as $db)
        {
            $output .= '<div id="queries">';
            $output .= "<table class=\"tableborder\">"; 
            $output .= "<tr><th>Database ".lang_item('profiler_queries').": ".count($ob->{$db}->queries)."&nbsp;&nbsp;&nbsp;</th></tr>";
            
            
            if (count($ob->{$db}->queries) == 0)
            {
                $output .= "<tr><td class=\"td_val\">".lang_item('profiler_no_queries')."</td></tr>";
            }
            else
            {   
                $output .= "<tr><td valign='top' class=\"td\">Db Variable</td><td class=\"td_val\">".$db."</td></tr>";
                         
                foreach ($ob->{$db}->queries as $key => $val)
                {   
                
                    if(isset($ob->{$db}->query_times[$key])) 
                    {
                        $time = number_format($ob->{$db}->query_times[$key], 4);
                    } 
                     else 
                    {
                        $time = 'exec not exist';
                    } 
                    
                    $val  = highlight_code($val, ENT_QUOTES);
                    
                    // remove all spaces and newlines.
                    $val  = preg_replace('/[\t\s]+/s', ' ', $val);   // ( Obullo Changes )
                    $val  = preg_replace('/[\r\n]/', '<br />', $val);
                    
                    foreach ($highlight as $bold)
                    {
                        $val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);    
                    }
                    
                    $output .= "<tr><td valign='top' class=\"td\">".$time."&nbsp;&nbsp;</td><td class=\"td_val\">".$val."</td></tr>";
                }
            }
            
            $output .= "</table>";
            $output .= "</fieldset>";
            $output .= "</div>";
        }
        
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
        $output .= "<tr><th>".lang_item('profiler_get_data')."</th></tr>";

        if (count($_GET) == 0)
        {
            $output .= "<tr><td class=\"td_val\">".lang_item('profiler_no_get')."</td></tr>";
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
    private function _compile_post()
    {    
        $output  = '<div id="post">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang_item('profiler_post_data')."</th></tr>";
                
        if (count($_POST) == 0)
        {
            $output .= "<tr><td class=\"td_val\">".lang_item('profiler_no_post')."</td></tr>";
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
    private function _compile_uri_string()
    {    
        $ob = ob::instance();

        $output  = '<div id="uri_string">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang_item('profiler_uri_string')."</th></tr>";
        
        if ($ob->uri->uri_string == '')
        {
            $output .= "<tr><td class=\"td_val\">".lang_item('profiler_no_uri')."</td></tr>";
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
    private function _compile_controller_info()
    {            
        $output  = '<div id="controller_info">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang_item('profiler_controller_info')."</th></tr>";

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
    function _compile_memory_usage()
    {
        $output  = '<div id="memory">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang_item('profiler_memory_usage')."</th></tr>";
        
        if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
        {
            $output .= "<tr><td class=\"td_val\">".number_format($usage)." bytes</td></tr>";
        }
        else
        {
            $output .= "<tr><td class=\"td_val\">".lang_item('profiler_no_memory_usage')."</td></tr>";
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
    function _compile_loaded_files()
    {          
        $ob  = ob::instance();
        $ssc = ssc::instance();
        
        $output  = '<div id="loaded_files">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang_item('profiler_loaded_files')."</th></tr>";
        
        $base_helpers  = '';
        foreach(loader::$_base_helpers as $base_helper) { $base_helpers .= $base_helper .', '; }
                    
        $app_helpers  = '';
        foreach(loader::$_app_helpers as $app_helper) { $app_helpers .= $app_helper .', '; }
        
        $helpers  = '';
        foreach(loader::$_helpers as $helper) { $helpers .= $helper .', '; }
        
        $libraries  = '';
        foreach($ssc->_profiler_libs as $lib) { $libraries .= $lib .', '; }
        
        $models  = '';
        foreach($ssc->_profiler_mods as $mod) { $models .= $mod .', '; }
              
        $databases  = '';
        foreach($ob->_dbs as $db) { $databases .= $db .', '; }
        
        $scripts  = '';
        foreach($ssc->_profiler_scripts as $scr) { $scripts .= $scr .', '; }
        
        $files  = '';              
        foreach($ssc->_profiler_files as $file) { $files .= $file .', '; }
        
        $local_views  = '';
        foreach($ssc->_profiler_local_views as $view) { $local_views .= $view .'<br /> '; }
    
        $app_views  = '';
        foreach($ssc->_profiler_app_views as $view) { $app_views .= $view .'<br /> '; }
        
        $base_helpers = (isset($base_helpers{2})) ? substr($base_helpers, 0, -2) : '-';
        $app_helpers  = (isset($app_helpers{2})) ? substr($app_helpers, 0, -2) : '-';
        $helpers      = (isset($helpers{2})) ? substr($helpers, 0, -2) : '-';
        $libraries    = (isset($libraries{2})) ? substr($libraries, 0, -2) : '-';
        $models       = (isset($models{2})) ? substr($models, 0, -2) : '-';
        $databases    = (isset($databases{2})) ? substr($databases, 0, -2) : '-';
        $scripts      = (isset($scripts{2})) ? substr($scripts, 0, -2) : '-';
        $files        = (isset($files{2})) ? substr($files, 0, -2) : '-';
        
        $output .= "<tr><td class=\"td\">Base Helpers&nbsp;&nbsp;</td><td class=\"td_val\">".$base_helpers."</td></tr>";  
        $output .= "<tr><td class=\"td\">Application Helpers&nbsp;&nbsp;</td><td class=\"td_val\">".$app_helpers."</td></tr>";    
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
    
    
    /**
    * Replace single quotes and
    * prevent javascript errors.
    * 
    * @param mixed $string
    */
    private function _replace_single_quotes($string)
    {
        $patterns[0]     = "/'/";
        $patterns[1]     = "/\s/";
        $replacements[0] = '"';
        $replacements[1] = '&nbsp;';
        
        return preg_replace($patterns, $replacements, $string);
    }
    

}

// END OB_Profiler class

/* End of file Profiler.php */
/* Location: ./base/libraries/Profiler.php */
?>