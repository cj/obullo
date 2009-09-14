<?php

    
    Class Model_blog extends Model
    {

        function __construct() {
            
            // Call the Model constructor
            parent::__construct();
            
            loader::database();
            //$this->load->database();

        }
        
        
        function test_function()
        {
            echo "<b>Model works.!!</b>";
            
            //loader::library('myclass');
            //$this->myclass->testDB();
            
            //$query = $this->db->query("SELECT * FROM articles");
            //$row = $query->row();
            
            //echo $row->article;
            
            echo "Model db result: <br />";
            //var_dump($this->db);
            //$query = $this->db->query("SELECT * FROM articles");
            //print_r($query->get_assoc());
            
            
            echo "Model session result: <br />";
            
            echo $this->session->get("test");
            
        }
    
    }



?>
