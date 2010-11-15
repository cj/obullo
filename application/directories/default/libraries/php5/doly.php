<?php

/**
* Your copy paste php5 library file.
*/
Class doly implements PHP5_Library
{
    private static $instance;
    
    public static function instance()
    {
       if(! (self::$instance instanceof self))
       {
            self::$instance = new self();
       } 
       
       return self::$instance;
    }
    
    public function init() 
    {    
        return self::instance();
    }
    

    public function doly_func()
    {
        // ..   
    }
    
} 

/* End of file doly.php */
/* Location: ./application/libraries/php5/doly.php */