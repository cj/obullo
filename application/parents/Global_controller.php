<?php
defined('BASE') or exit('Access Denied!');


Class __autoloader extends App_controller 
{
    function __autoloader()
    {         
        parent::__autoloader();
        loader::base_helper('head_tag');  
    } 
}

Class Global_controller extends __autoloader
{                                     
      public function __global()
      {
          parent::__construct();                                             
      }
      
}

/* End of file Global_controller.php */
/* Location: ./application/parents/Global_controller.php */
?>