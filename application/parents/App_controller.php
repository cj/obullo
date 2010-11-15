<?php
defined('BASE') or exit('Access Denied!');
                                                     
Class App_controller 
{   
    function __autoloader()
    {         
        loader::base_helper('view');
    } 
}

/* End of file App_controller.php */
/* Location: ./application/parents/App_controller.php */