<?php
defined('BASE') or exit('Access Denied!'); 

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 * 
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.database        
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
 * @license         public
 * @since           Version 1.0
 * @filesource
 */ 

/**
* DBAC_Switch On Class
* 
* If active record 'ON', Adapter class
* loads this class.
* 
* @package         Obullo 
* @subpackage      Base.database     
* @category        Database
* @version         0.1
*/
Class DBAC_Switch extends DB_active_record 
{
    /**
    * Brackets for FROM portion.
    * 
    * @var string
    */
    public $left  = ''; 
    public $right = '';
    
    /**
    * Store Curent PDO driver
    * 
    * @var string
    */
    public $db_driver = '';
    
    
    /**
    * Set db_driver
    * 
    * @param mixed $db_driver
    */
    public function set_driver($db_driver)
    {
        $this->db_driver = $db_driver;
        
        // factory for sql differences..
        switch ($db_driver)
        { 
           case 'MYSQL':
           // FROM portions brackets...
           $this->left  = '('; $this->right = ')';
             break;
           
           case 'ODBC':
           $this->left  = '('; $this->right = ')'; 
             break;
           
           case 'SQLITE':
           $this->left  = '('; $this->right = ')'; 
             break;
        }
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


/* End of file DBAC_Switch_on.php */
/* Location: ./base/database/DBAC_Switch_on.php */
?>
