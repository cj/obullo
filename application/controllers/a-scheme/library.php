<?php
  // a clear Library scheme example
  // copy and paste it
  
  Class Example extends Library 
  {
      function __construct()
      {
          parent::__construct();
                  
          loader::database();
          
          // you don't need it if you declare it before in the controller
          // parent::__user();  
          
      }
      
      function db_drivers()
      {
          $this->db->drivers();
      }
  
  }
  
?>
