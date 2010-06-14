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

// ------------------------------------------------------------------------

/**
 * Obullo Profiler Class
 *
 * This class enables you to display benchmark, query, and other data.
 *
 * @todo  Html codes will come from template
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
            $output .= "<tr><td class=\"td\">".$key."&nbsp;&nbsp;</td><td class=\"td\">".$val."</td></tr>";
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
        $dbs = array();

        /*
        // Let's determine which databases are currently connected to
        foreach (get_object_vars($this->CI) as $CI_object)
        {
            if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
            {
                $dbs[] = $CI_object;
            }
        }
                    
        if (count($dbs) == 0)
        {
            $output  = "\n\n";
            $output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
            $output .= "\n";
            $output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_queries').'&nbsp;&nbsp;</legend>';
            $output .= "\n";        
            $output .= "\n\n<table cellpadding='4' cellspacing='1' border='0' width='100%'>\n";
            $output .="<tr><td width='100%' style='color:#0000FF;font-weight:normal;background-color:#eee;'>".$this->CI->lang->line('profiler_no_db')."</td></tr>\n";
            $output .= "</table>\n";
            $output .= "</fieldset>";
            
            return $output;
        }
        
        // Load the text helper so we can highlight the SQL
        $this->CI->load->helper('text');

        // Key words we want bolded
        $highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

        $output  = "\n\n";
            
        foreach ($dbs as $db)
        {
            $output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
            $output .= "\n";
            $output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_database').':&nbsp; '.$db->database.'&nbsp;&nbsp;&nbsp;'.$this->CI->lang->line('profiler_queries').': '.count($this->CI->db->queries).'&nbsp;&nbsp;&nbsp;</legend>';
            $output .= "\n";        
            $output .= "\n\n<table cellpadding='4' cellspacing='1' border='0' width='100%'>\n";
        
            if (count($db->queries) == 0)
            {
                $output .= "<tr><td width='100%' style='color:#0000FF;font-weight:normal;background-color:#eee;'>".$this->CI->lang->line('profiler_no_queries')."</td></tr>\n";
            }
            else
            {                
                foreach ($db->queries as $key => $val)
                {                    
                    $time = number_format($db->query_times[$key], 4);

                    $val = highlight_code($val, ENT_QUOTES);
    
                    foreach ($highlight as $bold)
                    {
                        $val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);    
                    }
                    
                    $output .= "<tr><td width='1%' valign='top' style='color:#990000;font-weight:normal;background-color:#ddd;'>".$time."&nbsp;&nbsp;</td><td style='color:#000;font-weight:normal;background-color:#ddd;'>".$val."</td></tr>\n";
                }
            }
            
            $output .= "</table>\n";
            $output .= "</fieldset>";
            
        }
        
        return $output;
        */
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
            $output .= "<tr><td class=\"td\">".lang_item('profiler_no_get')."</td></tr>";
        }
            else
        {
            foreach ($_GET as $key => $val)
            {
                if ( ! is_numeric($key))
                {
                    $key = "'".$key."'";
                }
            
                $output .= "<tr><td class=\"td\">&#36;_GET[".$key."]&nbsp;&nbsp;</td><td class=\"td\">";
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
            $output .= "<tr><td class=\"td\">".lang_item('profiler_no_post')."</td></tr>";
        }
        else
        {
            foreach ($_POST as $key => $val)
            {
                if ( ! is_numeric($key))
                {
                    $key = "'".$key."'";
                }
            
                $output .= "<tr><td class=\"td\">&#36;_POST[".$key."]&nbsp;&nbsp;</td><td class=\"td\">";
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
            $output .= "<tr><td class=\"td\">".lang_item('profiler_no_uri')."</td></tr>";
        }
        else
        {
            $output .= "<tr><td class=\"td\">".$ob->uri->uri_string."</td></tr>";
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

        $output .= "<tr><td class=\"td\">".$GLOBALS['d'].' / '.$GLOBALS['c'].' / '.$GLOBALS['m']."</td></tr>";
        
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
        $output  = '<div id="controller_info">';       
        $output .= "<table class=\"tableborder\">";
        $output .= "<tr><th>".lang_item('profiler_memory_usage')."</th></tr>";
        
        if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
        {
            $output .= "<tr><td class=\"td\">".number_format($usage)." bytes</td></tr>";
        }
        else
        {
            $output .= "<tr><td class=\"td\">".lang_item('profiler_no_memory_usage')."</td></tr>";
        }
        
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
        $output = "<div id=\"obullo_profiler\" style=\"clear:both;background-color:#fff;padding:10px;\">";
        $output .= $this->_compile_uri_string();
        $output .= $this->_compile_controller_info();
        $output .= $this->_compile_memory_usage();
        $output .= $this->_compile_benchmarks();
        $output .= $this->_compile_get();
        $output .= $this->_compile_post();
        $output .= $this->_compile_queries();

        $output .= '</div>';

        return $output;
    }

}

// END OB_Profiler class

/* End of file Profiler.php */
/* Location: ./base/libraries/Profiler.php */
?>