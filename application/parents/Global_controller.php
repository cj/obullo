<?php
defined('BASE') or exit('Access Denied!');

Class __autoloader {
    
    function __construct()
    {         
        loader::base_helper('html'); 
        loader::base_lib('content');
    } 
}
Class Global_controller extends __autoloader
{
      public $base     = '';
      public $base_img = '';
      
      public $title_tag = '';
      public $head_tag  = '';
      public $meta_tag  = '';
      public $body_tag  = '';
      public $body_attributes = '';
                                              
      public function __global()
      {
          parent::__construct();
                                              
          $this->base     = config_item('base_url');
          $this->base_img = config_item('source_url').'/images/';
          
          $this->meta_tag = '';
          
          $this->meta_tag  = meta('Expires', 'Fri, Jan 01 1900 00:00:00 GMT', 'equiv');
          $this->meta_tag .= meta('Pragma', 'no-cache', 'equiv');
          $this->meta_tag .= meta('Cache-Control', 'no-cache', 'equiv');
          $this->meta_tag .= meta('Content-type', 'text/html; charset=utf-8', 'equiv');
          $this->meta_tag .= meta('Lang', 'en', 'equiv');
          $this->meta_tag .= meta('description', '');
          $this->meta_tag .= meta('author', '');
      }
      
} // end.

 
?>
