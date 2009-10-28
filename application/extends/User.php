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
  * WARNING !: it will work for everywhere like this "user::yourfunction()",
  * if a function global (if you use it for every controllers) you should put
  * it here, if its not a global function don't put it here because of your app 
  * speed can go down..
  *  
  */
  
  Class UserException extends CommonException{}
  // you also use exception errors like --> throw new UserException('error');
  
  Class user
  {
      
      public $base_url = 'http://localhost/obullo/';
      public $base_img = 'base/views/images/';
      public $base_css = 'base/views/css/';
      
      // Build html content
      public $title_tag = '';
      public $head_tag  = '';
      public $body_tag  = '';
      public $h1  = '';
      public $h2  = '';
      public $h3  = '';
      
      public $data = array(); // view data
                   
      /**
      * parent::__user();
      * User common __construct for all controllers
      */
      function __user()
      {
          $this->base_url = ob::base_url();
          
          // also you load your static classes like this !
          
          // loader::library('mystatic_class',true);
          // loader::library('mystatic_class2',true);
          
          // loader::library('navigation');
          // loader::base('session');

          
          //echo 'this my top __Constructor for all controllers ! It comes from /application/extends/Ob_user.php<br />';
      }
      
      function __header()
      {
          // this is header for every function
          $this->data = array(); // data container for view files
          $this->title_tag = 'Default common title tag for every page !';
          $this->body_tag  = 'Default body tag for every page !';
      } 
      
      // parent index for all controller index() methods...
      function __index(){}
                                     
      
      // set a back url user::set_back_url();
      // session class must be loaded
      function set_back_url()
      {
          // set back url for $_GET urls..
          return $this->session->set_userdata('back_url', $_SERVER['QUERY_STRING']);
      }
      
      // get a back url user::back_url();
      // session class must be loaded
      function back_url()
      {
          return $this->session->userdata('back_url');
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
