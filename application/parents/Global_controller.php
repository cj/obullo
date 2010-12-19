<?php
defined('BASE') or exit('Access Denied!');


Class Global_controller extends Controller
{                                     
    public function __construct()
    {
        parent::__construct();
        
        loader::base_helper('head_tag');
        loader::base_helper('url');
        
    }
      
}

/* End of file Global_controller.php */
/* Location: ./application/parents/Global_controller.php */