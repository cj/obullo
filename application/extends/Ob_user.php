<?php
  
  /**
  * OBULLO USER DEFINED COMMON FUNCTIONS
  * ob_user::my_function()
  *  
  * Just Put your common special functions here !!
  * Ob_user functions works everytime for every controller
  * So you should be put your common functions in
  * your application like 
  * 
  * o site navigation menu,
  * o module functions or auth.
  * 
  * it will work like this ob_user::yourfunction
  */

  Class ob_user
  {
      
      // automatically load your static classes.
      function __construct()
      {
          // ALSO YOU CAN load your static classes like this !
          // loader::library('mystatic_class',true);
          // loader::library('mystatic_class2',true);
          
          loader::library('navigation');
      }
      
      // forexample show navigation menu level1
      // ob::nav_level1('link');
      // ob::nav_level2('link');
      public function nav_level1()
      {
          return $this->navigation->nav_level1();
      }
      
      public function nav_level2()
      {
          return $this->navigation->nav_level2();
      }  
      
  } // end class.
  
  
?>
