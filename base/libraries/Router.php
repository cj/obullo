<?php                                       
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo
 * @subpackage      Base.base        
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @filesource
 * @license
 */

Class RouterException extends CommonException {} 

 /**
 * Router Class
 * Parses URIs and determines routing 
 *
 * @package     Obullo
 * @subpackage  Base
 * @category    URI
 * @author      Ersin Guvenc
 * @version     0.1 changed php4 rules as php5
 * @version     0.2 Routing structure changed as /directory/class/method/arg..
 * @version     0.3 added query string support d= directory & c= class & m= method
 * @link        
 */
Class OB_Router {
    
    public $config;    
    public $routes              = array();
    public $error_routes        = array();
    public $class               = '';
    public $method              = 'index';
    public $directory           = '';
    public $uri_protocol        = 'auto';
    public $default_controller;
    public $query_string        = FALSE; // Obullo  1.0 changes
    
    /**
    * Constructor
    * Runs the route mapping function.
    * 
    * @author   Ersin Guvenc
    * @version  0.1
    * @version  0.2 added config index method and include route
    */
    public function __construct()
    {
        $routes = get_config('routes');   // Obullo changes..
        
        $this->routes = ( ! isset($routes) OR ! is_array($routes)) ? array() : $routes;
        unset($routes);
        
        $this->method = $this->routes['index_method'];
        $this->uri    = base_register('URI');
        $this->_set_routing();        
                
        log_message('debug', "Router Class Initialized");
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set the route mapping
    *
    * This function determines what should be served based on the URI request,
    * as well as any "routes" that have been set in the routing config file.
    *
    * @access    private
    * @author    Ersin Guvenc
    * @version   0.1
    * @version   0.2 added query_sting = true var
    * @return    void
    */
    private function _set_routing()
    {
        // Are query strings enabled in the config file?
        // If so, we're done since segment based URIs are not used with query strings.
        if (config_item('enable_query_strings') === TRUE AND isset($_GET[config_item('controller_trigger')]))
        {
            // obullo 1.0 changes 
            $this->query_string = TRUE;
            // obullo 1.0 changes
        
            $this->set_directory(trim($this->uri->_filter_uri($_GET[config_item('directory_trigger')])));
            $this->set_class(trim($this->uri->_filter_uri($_GET[config_item('controller_trigger')])));

            if (isset($_GET[config_item('function_trigger')]))
            {
                $this->set_method(trim($this->uri->_filter_uri($_GET[config_item('function_trigger')]))); 
            }
            
            return;
        }

        // Set the default controller so we can display it in the event
        // the URI doesn't correlated to a valid controller.
        $this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);    
        
        // Fetch the complete URI string
        $this->uri->_fetch_uri_string();
    
        // Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
        if ($this->uri->uri_string == '')
        {
            if ($this->default_controller === FALSE)
            {
                throw new RouterException("Unable to determine what should be displayed. A default route has not been specified in the routing file.");
            }

            // Turn the default route into an array.  We explode it in the event that
            // the controller is located in a subfolder
            //$segments = $this->_validate_request(explode('/', $this->default_controller));
            $segments = $this->_validate_request(explode('/', $this->default_controller)); 
        
            // Set the class and method
            $this->set_class($segments[1]);
            $this->set_method($this->routes['index_method']);  // index
    
            // Assign the segments to the URI class
            $this->uri->rsegments = $segments;
            
            // re-index the routed segments array so it starts with 1 rather than 0
            $this->uri->_reindex_segments();
            
            log_message('debug', "No URI present. Default controller set.");
            return;
        }
        unset($this->routes['default_controller']);
        
        // Do we need to remove the URL suffix?
        $this->uri->_remove_url_suffix();
        
        // Compile the segments into an array
        $this->uri->_explode_segments();

        // Parse any custom routing that may exist
        $this->_parse_routes();        
        
        // Re-index the segment array so that it starts with 1 rather than 0
        $this->uri->_reindex_segments();
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set the Route
    *
    * This function takes an array of URI segments as
    * input, and sets the current class/method
    *
    * @access   private
    * @author   Ersin Guvenc
    * @param    array
    * @param    bool
    * @version  0.1
    * @version  0.2 Changed $segments[0] as $segments[1]  and 
    *           $segments[1] as $segments[2]
    * @return   void
    */
    private function _set_request($segments = array())
    {   
        $segments = $this->_validate_request($segments);
        
        if (count($segments) == 0)
        return;
                        
        $this->set_class($segments[1]);
        
        if (isset($segments[2]))
        {
                // A standard method request
                $this->set_method($segments[2]);   
        }
        else
        {
            // This lets the "routed" segment array identify that the default
            // index method is being used.
            $segments[2] = $this->routes['index_method'];
        }
        
        // Update our "routed" segment array to contain the segments.
        // Note: If there is no custom routing, this array will be
        // identical to $this->uri->segments
        $this->uri->rsegments = $segments;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Validates the supplied segments.  Attempts to determine the path to
    * the controller.
    *
    * @author   Ersin Guvenc
    * @access   private
    * @param    array
    * @version  Changed segments[0] as segments[1]
    *           added directory set to segments[0]
    * @return   array
    */    
    private function _validate_request($segments)
    {
        // $segments[0] = directory
        // $segments[1] = controller name
        
        if( ! isset($segments[0]) ) $segments[0] = '';
        if( ! isset($segments[1]) ) $segments[1] = '';
        
        
        // Check directory
        if (is_dir(DIR.$segments[0]))
        {  
            $this->set_directory($segments[0]);
            
            if( ! empty($segments[1])) 
            {
                if (file_exists(DIR.$segments[0].DS.'controllers'.DS.$segments[1].EXT))
                return $segments;  
            }

        }

        show_404($segments[0].' / '.$segments[1]);
    }

    // --------------------------------------------------------------------

    /**
    * Parse Routes
    *
    * This function matches any routes that may exist in
    * the config/routes.php file against the URI to
    * determine if the class/method need to be remapped.
    *
    * @access    private
    * @return    void
    */
    private function _parse_routes()
    {
        // Do we even have any custom routing to deal with?
        // There is a default scaffolding trigger, so we'll look just for 1
        if (count($this->routes) == 1)
        {             
            $this->_set_request($this->uri->segments);
            return;
        }

        // Turn the segment array into a URI string
        $uri = implode('/', $this->uri->segments);

        // Is there a literal match?  If so we're done
        if (isset($this->routes[$uri]))
        {
            $this->_set_request(explode('/', $this->routes[$uri]));        
            return;
        }
                
        // Loop through the route array looking for wild-cards
        foreach ($this->routes as $key => $val)
        {                        
            // Convert wild-cards to RegEx
            $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
            
            // Does the RegEx match?
            if (preg_match('#^'.$key.'$#', $uri))
            {            
                // Do we have a back-reference?
                if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
                {
                    $val = preg_replace('#^'.$key.'$#', $val, $uri);
                }
            
                $this->_set_request(explode('/', $val));        
                return;
            }
        }
        
        // If we got this far it means we didn't encounter a
        // matching route so we'll set the site default route
        $this->_set_request($this->uri->segments);
    }

    // --------------------------------------------------------------------
    
    /**
    * Set the class name
    *
    * @access    public
    * @param     string
    * @return    void
    */    
    public function set_class($class)
    {
        $this->class = $class;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Fetch the current class
    *
    * @access    public
    * @return    string
    */    
    public function fetch_class()
    {
        return $this->class;
    }
    
    // --------------------------------------------------------------------
    
    /**
    *  Set the method name
    *
    * @access    public
    * @param     string
    * @return    void
    */    
    public function set_method($method)
    {
        $this->method = $method;
    }

    // --------------------------------------------------------------------
    
    /**
    *  Fetch the current method
    *
    * @access    public
    * @return    string
    */    
    public function fetch_method()
    {
        if ($this->method == $this->fetch_class())
        {
            return $this->routes['index_method'];
        }

        return $this->method;
    }

    // --------------------------------------------------------------------
    
    /**
    *  Set the directory name
    *
    * @access    public
    * @param    string
    * @return    void
    */    
    public function set_directory($dir)
    {
        $this->directory = $dir.'';  // Obullo changes..
    }

    // --------------------------------------------------------------------
    
    /**
    *  Fetch the sub-directory (if any) that contains the requested controller class
    *
    * @access    public
    * @return    string
    */    
    public function fetch_directory()
    {
        return $this->directory;
    }

}
// END Router Class

/* End of file Router.php */
/* Location: ./base/libraries/Router.php */
?>