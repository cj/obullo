<?php

  Class mylibrary extends Library 
  {
      function __construct()
      {
          parent::__construct();
          
          
      }
   
      function test_ssc()
      {
          
          loader::database();
        
        //$ob = ob::instance();
        //echo 'my ssc library result: '.$ob->db->drivers();
        
        // static functions...
        //ob::input_set('name','ersin');
        //echo ob::input_get('name');
        
        echo ob::user_ip();
        
        ob_user::nav_level1();
        ob_user::nav_level2();
        
      }
      
  }
  
  
  
?>
