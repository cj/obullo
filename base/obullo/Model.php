<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.libraries        
 * @copyright       Copyright (c) 2009 Ersin Guvenc.
 * @license          
 * @since           Version 1.0
 * @filesource
 */ 
 
/**
 * Model Class.
 *
 * Main model class.
 *
 * @package         Obullo 
 * @subpackage      Base.obullo     
 * @category        Libraries
 * @version         0.1
 * @version         0.2 added extend to ob
 * @version         0.3 depreciated get_object_vars, added _assing_db_objects
 */                    

Class Model {

    public function __construct()
    {
        $this->_assign_db_objects();

        log_message('debug', "Model Class Initialized");
    }
    
    /**
    * Assign all db objects to all Models.
    * 
    * Very bad idea assign all library objects to model !!!
    * We assign just db objects. -- Ersin
    */
    public function _assign_db_objects()
    {
        $OB = ob::instance();
        
        foreach($OB->_dbs as $key)
        {
            $this->$key = &$OB->$key;
        }
    
    }
      
}

// END Model Class

/* End of file Model.php */
/* Location: ./base/obullo/Model.php */
?>