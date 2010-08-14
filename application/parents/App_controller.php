<?php
defined('BASE') or exit('Access Denied!');
                                                     
Class App_controller 
{
    public $base, $base_url, $base_img;
    public $title, $head, $meta, $body;
    public $body_attributes = ''; 
    
    function __autoloader()
    {         
        loader::base_helper('content');
        
        ini_set('display_errors', config_item('display_errors'));
        date_default_timezone_set(config_item('timezone_set'));
        
        $this->base     = $this->config->base_url();
        $this->base_url = $this->config->site_url();
        $this->base_img = $this->config->source_url() . 'images/';
        
    } 
}

/* End of file App_controller.php */
/* Location: ./application/parents/App_controller.php */
?>