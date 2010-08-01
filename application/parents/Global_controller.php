<?php
defined('BASE') or exit('Access Denied!');

loader::file('parents'. DS .'App_controller'. EXT);

/**
* Autoloader for Global Controller
*/
Class __autoloader extends App_controller 
{
    function __autoloader()
    {         
        parent::__autoloader();
        
        loader::base_helper('content');
        loader::base_helper('head_tag');  
    } 
}
/**
* Global Controller
*/
Class Global_controller extends __autoloader
{                                     
      public function __global()
      {
          parent::__construct();
          
          // You can override to App_controller variables ..
          // $this->base_img = $this->config->source_url().'images/';                                                
      }
      
}
/* End of file Global_controller.php */
/* Location: ./application/parents/Global_controller.php */
?>