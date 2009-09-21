<?php

  Class mylibrary extends Library 
  {
      function __construct()
      {
          parent::__construct();
          
          parent::ob_user();
          
          loader::database();  
      }
   
      function test_ssc()
      {
      
          echo $this->base_url;
          
          //loader::database();
          $this->db->drivers();
        
          //echo 'my ssc library result: '.$ob->db->drivers();
        
          // static functions...
          //ob::input_set('name','ersin');
          //echo ob::input_get('name');
          
          echo ob::input_ip();
        
          ob_user::nav_level1();
          ob_user::nav_level2();
        
      }
      
  }
  
  
  
?>
