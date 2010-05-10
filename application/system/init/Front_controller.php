<?php
defined('BASE') or exit('Access Denied!');
      
/**
* This is your front controller class you can change it.
* It extends to Base Front Controller Class.
* You will just do method overriding.
* 
* @see  user_guide: Chapters / General Topics / Front Controller  
*/
  
Class Front_controller extends OB_Front_controller
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function run()
    {
        parent::run();
    } 
    
    public function close()
    {
        parent::close();
    }        
}   

/* End of file Front_controller.php */
/* Location: ./application/system/init/Front_controller.php */
?>