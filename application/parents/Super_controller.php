<?php
defined('BASE') or exit('Access Denied!');

Class Super_controller {
    
    public $base, $base_url, $base_img;
    public $title, $head, $meta, $body;
    public $body_attributes = ''; 
    
    function __autoloader()
    {         
        loader::base_helper('content');
        
        $this->__initroot();  
    } 
    
    public function __initroot()
    {
        $this->base     = config_item('base_url');
        $this->base_url = $this->config->site_url();
        $this->base_img = $this->config->source_url().'images/'; 
    }
       
}

/* End of file Super_controller.php */
/* Location: ./application/parents/Super_controller.php */
?>