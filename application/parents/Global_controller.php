<?php
defined('BASE') or exit('Access Denied!');

Class __autoloader {
    
    function __construct()
    {         
        loader::base_helper('content');   
        loader::base_helper('head_tag');   
    } 
}
Class Global_controller extends __autoloader
{
      public $base, $base_url, $base_img;
      public $title, $head, $meta, $body;
      public $body_attributes = '';
                                              
      public function __global()
      {
          parent::__construct();
          
          $this->base     = config_item('base_url');
          $this->base_url = config_item('base_url')  . config_item('index_page');
          $this->base_img = config_item('source_url').'/images/';
          
          $this->meta     = meta('Content-type', 'text/html; charset=utf-8', 'equiv');
          $this->meta    .= meta('description', '');
          $this->meta    .= meta('author', '');
                                                  
      }
      
}
/* End of file Global_controller.php */
/* Location: ./application/parents/Global_controller.php */
?>