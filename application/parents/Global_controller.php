<?php
defined('BASE') or exit('Access Denied!');

loader::file('parents'. DS .'Super_controller'. EXT);

Class __autoloader extends Super_controller {
    
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
          
          // You can change Super_controller variables ..
          $this->base_img = $this->config->source_url().'images/';                                                
      }
      
}
/* End of file Global_controller.php */
/* Location: ./application/parents/Global_controller.php */
?>