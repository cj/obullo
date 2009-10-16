<?php

   
Class Model_test_db extends Model
{

    function __construct()
    {    
        // Call the Model constructor
        parent::__construct();
        
        // Load database connection
        loader::database();
    }


    public function query1()
    {   
        
    } //end func.
    
    
    public function query2()
    {   
        
    } //end func.

    
} //end class


?>
