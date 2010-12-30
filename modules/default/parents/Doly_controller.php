<?php
defined('BASE') or exit('Access Denied!');

/**
* Your copy paste Global Controller file.
*/

Class Doly_controller extends Controller
{                                     
    public function __construct()
    {
        parent::__construct();
        
        loader::base_helper('head_tag');
        loader::base_helper('url');
        
    }
      
}

/* End of file Doly_controller.php */
/* Location: ./application/parents/Doly_controller.php */