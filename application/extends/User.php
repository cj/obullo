<?php
  
  /**
  * OBULLO User.php
  * 
  * o SSC Pattern (c) 2009 Ersin Güvenç
  * o Super Static Controllers : User, Loader, Ob
  *  
  * Just Put your common special functions here !! "user" class works 
  * everywhere except helper files. So you should put your common functions
  * of your application which interested in these categories..  
  * 
  *       o site navigation menu func.,
  *       o authentication functions
  *       o module functions
  *       o and for your other special functions..
  * 
  * WARNING !: it will work for everywhere like this "user::yourfunction()",
  * if youe function is a global (if every controllers use this function) 
  * you should put it here, if its not a global function don't put it here 
  * because of your app 
  * performance ..
  *  
  */
  
  Class UserException extends CommonException{} // use it like this throw new UserException('error');
  
  Class __autoloader
  {
      function __construct()
      {
          // autoload libraries, helpers, lang, config files ..
          //loader::base(array('session','cookie'));
          loader::library(array('navigation'));
          //loader::base_helper(array('form'));
          //loader::helper(array());
          //loader::model(array());
          //loader::language(array());
          //loader::config(array());
          //loader::plugin(array());
      }   
  }
  Class user extends __autoloader
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
                                                     
                                                     
      function __user()
      {
          parent::__construct();
          
          $this->base_url = ob::base_url();
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
