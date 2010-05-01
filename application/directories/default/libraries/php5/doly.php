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
    
    public function init() {}
    

    public function doly()
    {
        // ..   
    }
    
} //end.

?>
