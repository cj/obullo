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
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
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
 * @subpackage      Base.libraries     
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
    * 
    * @author  Ersin Güvenç
    */
    public function _assign_db_objects()
    {
        foreach(ob::instance()->_dbs as $key)
        {
            $this->$key = &ob::instance()->$key;
        }
    
    } // end func.
      
}
// end Model Class
