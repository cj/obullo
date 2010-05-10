<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 * 
 * @package         obullo      
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */ 

// ------------------------------------------------------------------------

Class CommonException extends Exception {};   

/**
 * Base Front Controller Class
 *
 * Control the first run of the application
 *
 * @package       Obullo
 * @subpackage    Base
 * @category      Front Controllers
 * @author        Ersin Güvenç
 * @version       0.1
 * @version       0.2 added base db and app config constants.
 * @link          
 */
Class OB_Front_controller {
    
    public $uri, $router, $output, $config, $benchmark;
    
    public function __construct()
    {
        require (BASE .'constants'. DS .'db'. EXT);
        require (BASE .'constants'. DS .'file'. EXT);
        require (APP  .'config'. DS .'constants'. EXT);  // Your constants .. 
        require (BASE .'obullo'. DS .'Errors'. EXT);       
        require (BASE .'obullo'. DS .'Registry'. EXT); 
        require (BASE .'obullo'. DS .'Common'. EXT);
        
        // Kill magic quotes
        if ( ! is_php('5.3')) { @set_magic_quotes_runtime(0); }
        
        ini_set('display_errors', config_item('display_errors'));
        date_default_timezone_set(config_item('timezone_set'));
        
        header('Content-type: text/html;charset='.config_item('charset')); // UTF-8 
        
        $this->uri       = base_register('URI');
        $this->router    = base_register('Router');
        $this->output    = base_register('Output');
        $this->config    = base_register('Config');
        $this->benchmark = base_register('Benchmark');  
    }
    
    public function run()
    {
        $this->benchmark->mark('total_execution_time_start');
        $this->benchmark->mark('loading_time_base_classes_start');
        
        // Check REQUEST uri if there is a Cached file exist
        if ($this->output->_display_cache($this->config, $this->uri) == TRUE) { exit; }
        
        $GLOBALS['d']   = $this->router->fetch_directory();   // Get requested directory
        $GLOBALS['c']   = $this->router->fetch_class();       // Get requested controller
        $GLOBALS['m']   = $this->router->fetch_method();      // Get requested method
        
        // Check the controller exists or not
        if ( ! file_exists(DIR .$GLOBALS['d']. DS .'controllers'. DS .$GLOBALS['c']. EXT))
        {
            if($this->router->query_string) show_404("{$GLOBALS['c']}/{$GLOBALS['m']}");
            
            throw new CommonException('Unable to load your default controller.
            Please make sure the controller specified in your Routes.php file is valid.');
        }
        
        require (BASE .'obullo'. DS .'Loader'. EXT);
        require (BASE .'obullo'. DS .'Obullo'. EXT);
        require (BASE .'obullo'. DS .'Controller'. EXT);
        require (BASE .'obullo'. DS .'Model'. EXT);
        
        // Set a mark point for benchmarking
        $this->benchmark->mark('loading_time_base_classes_end');
        
        // Mark a start point so we can benchmark the controller
        $this->benchmark->mark('execution_time_( '.$GLOBALS['d'].'/'.$GLOBALS['c'].'/'.$GLOBALS['m'].' )_start');
        
        // call the controller.
        require (DIR .$GLOBALS['d']. DS .'controllers'. DS .$GLOBALS['c']. EXT);

        if ( ! class_exists($GLOBALS['c']) OR $GLOBALS['m'] == 'controller'
              OR in_array(strtolower($GLOBALS['m']), array_map('strtolower', get_class_methods('Controller')))
            )
        {
            show_404("{$GLOBALS['c']}/{$GLOBALS['m']}");
        }
        
        // If Everyting ok Declare Called Controller !
        $OB = new $GLOBALS['c']();


        // Check method exist or not
        if ( ! in_array(strtolower($GLOBALS['m']), array_map('strtolower', get_class_methods($OB))))
        {
            show_404("{$GLOBALS['c']}/{$GLOBALS['m']}"); 
        }

        // Call the requested method.                1       2       3
        // Any URI segments present (besides the directory/class/method) 
        // will be passed to the method for convenience
        call_user_func_array(array($OB, $GLOBALS['m']), array_slice($OB->uri->rsegments, 3));
        
        // Mark a benchmark end point
        $this->benchmark->mark('execution_time_( '.$GLOBALS['d'].'/'.$GLOBALS['c'].'/'.$GLOBALS['m'].' )_end');
        
        // Write Cache file if cache on ! and Send the final rendered output to the browser
        $this->output->_display(); 
    }
    
    public function close()
    {   
        // Close all PDO connections..        
        foreach(ob::instance()->_dbs as $db_var)
        {
            ob::instance()->{$db_var} = NULL;
        }
        
        // Closing PDO Connections !
        // ..$OB->db = NULL;
        // . 
    }
    
    
}  // end class.

// END Front controller class

/* End of file Front_controller.php */
/* Location: ./base/obullo/Front_controller.php */
?>