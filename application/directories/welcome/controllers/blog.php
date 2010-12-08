<?php
  

  
Class Blog extends Controller {
    
    function __construct()
    {
        parent::__construct();
        parent::__global();
    }
    
    
    function write($arg = '')
    {
        echo 'Hello HMVC !<br />';
        echo 'POST: '. print_r($_POST, true).'<br />';
        echo 'argument:'. $arg;
    }
    
    function read()
    {
        echo 'READ !!! WORKS  !!';
    }
    
}
