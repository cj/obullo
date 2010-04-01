<?php
defined('BASE') or exit('Access Denied!'); 

/**
* DBAC_Switch Off Class
* 
* If active record 'OFF', Adapter class
* loads this class.
* 
* @package         Obullo 
* @subpackage      Base.database     
* @category        Database
* @version         0.1
*/

Class DBAC_Switch extends PDO 
{
    /**
    * Store current PDO driver
    * 
    * @var mixed
    */
    public $db_driver = '';
    
    // --------------------------------------------------------------------
    
    /**
    * Set Db Driver
    * 
    * @param  string $db_driver
    */
    public function set_driver($db_driver)
    {
        $this->db_driver = $db_driver;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get current db driver.
    * 
    * @return  string driver name
    */
    public function get_driver()
    {
         return $this->db_driver;
    }
    
}  

/* End of file DBAC_Switch_off.php */
/* Location: ./base/database/DBAC_Switch_off.php */
  
?>
