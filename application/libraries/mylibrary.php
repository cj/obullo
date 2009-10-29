<?php
            
  Class mylibrary extends Library 
  {
      function __construct()
      {
          //parent::__construct();
                  
          loader::database();
          
          // you don't need it if you declare it before in test controller
          //parent::__user();  
          
      }
   
      function test_ssc()
      {
          
          echo '<br />'.$this->base_url.' <-- this comes from extends/User.php<br />';
          
          // I can use db class directly from my lib.
          // WITHOUT ci = &get_instance() func unlike CI
          echo '<br /><b>i can use db functions directly from library.(Without get_instance() func.)</b><br />';
          $this->db->drivers();
        
          echo '<br /><b>i can use static functions from library:</b> '.ob::ip().'<br />';
        
          // My Super Static Class Functions
          // Look at application/extends/Ob_user.php
          user::nav_level1();
          echo '<br />';
          user::nav_level2();
        
      }
      
  }

?>
