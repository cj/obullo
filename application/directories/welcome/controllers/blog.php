<?php
  

  
Class Blog extends Controller {
    
    function __construct()
    {
        parent::__construct();
        parent::__global();
    }
    
    
    function write($arg = '')
    {
        $this->output->cache(1000);
        
        ob_start();
        echo 'Hello HMVC !<br />';
        echo 'POST: '. print_r($_POST, true).'<br />';
        echo 'argument:'. $arg;
        
        $this->output->append_output(ob_get_contents());
    }
    
    function read()
    {
        echo 'READ !!! WORKS  !!';
    }
    
}
