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
 * @version         0.4 ob->_dbs func. replaced with $ob->__ob_db_vars func.
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
        $OB = Obullo::instance();
        
        foreach($OB->__ob_db_vars as $db_name => $db_var)
        {
            echo __FUNCTION__.' : ' .$db_var.'<br />';
            $this->$db_var = &$OB->$db_var;
        }
    
    }
      
}

// END Model Class

/* End of file Model.php */
/* Location: ./base/obullo/Model.php */