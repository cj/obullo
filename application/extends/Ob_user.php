<?php
  
  /**
  * OBULLO USER DEFINED COMMON FUNCTIONS
  * 
  * o SSC Pattern (c) 2009 Ersin Güvenç
  * o We use Super Static Controllers 
  * o for prevent long writing ($this->navigation->nav_level1())
  * o we just write user::nav_level1();
  *  
  * Just Put your common special functions here !!
  * "user" functions works everytime for every controller
  * So you should put your common functions in
  * your application which interested in like
  * this categories..  
  * 
  *       o site navigation menu func.,
  *       o authentication functions
  *       o module functions
  *       o and for your other special libraries..
  * 
  * it will work like this ob_user::yourfunction
  */

  Class user
  {
      
      public $base_url = 'http://localhost/obullo/';
      public $base_img = 'application/resources/images/';
      public $base_css = 'application/resources/css/';
                   
      /**
      * parent::__user();
      * User common __construct for all controllers
      */
      function __user()
      {
          // ALSO YOU CAN load your static classes like this !
          // loader::library('mystatic_class',true);
          // loader::library('mystatic_class2',true);
          
          loader::library('navigation');
          //loader::library('session');

          
          echo 'this my top __Constructor for all controllers ! It comes from /application/extends/Ob_user.php<br />';
      }
      
      function header()
      {
          //echo 'Controller Header functions here!<br />';
          
          $data['title_tag'] = 'Common title for every page !';
          
          $data['head_tag'] = '';
          $data['head_tag'].= '';
          
          return $data;
      }
      
      // get update form post variables
      function form_db_values($formfields, $db_data){}
      
      // SSC Pattern (c) 2009 Ersin Güvenç
      // We use super static controllers 
      // for prevent long writing ($this->navigation->nav_level1())
      // we just write user::nav_level1();
      public function nav_level1()
      {
          return $this->navigation->nav_level1().'<br />';
      }
      
      public function nav_level2()
      {
          return $this->navigation->nav_level2().'<br />';
      }  
      
  } // end class.
  
  
?>
