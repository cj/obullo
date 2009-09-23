<?php

    
    Class Model_blog extends Model
    {

        function __construct() {
            
            // Call the Model constructor
            parent::__construct();
            
            loader::database();
            //$this->load->database();
            
            
            
        }
        
        
        function test()
        {
            $this->db->drivers();
            echo "<b>Model_blog works.!!</b><br /><br />";
            
        }
    
    }



?>
