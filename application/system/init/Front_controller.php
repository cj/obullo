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
        
        // remove parent code 
        // copy base/base/OB_Front_Controller  __construct() 
        // method contents and paste here !
        // and customize this contents !
        parent::__construct();
    }
    
    public function run()
    {
        // remove parent code 
        // copy base/base/OB_Front_Controller run() 
        // method contents and paste here !
        // and customize this contents !
        parent::run();
    }
    
}   
// end of the class.
  
?>
