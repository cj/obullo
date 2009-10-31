<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Framework software for PHP 5.2.4 or newer
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.libraries        
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
 * @license         http://www.opensource.org/licenses/gpl-3.0.html GPL  
 * @since           Version 1.0 @alpha
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
 */                    

Class Model extends ob {

public $myself;   // called model

    function __construct()
    {
        // assign obullo libraries to all called models
        $this->_asn_lib(); 
        
        // this is just called model name 
        $this->myself = ucfirst(get_class($this));
    }
    
    function _asn_lib()
    {
        $OB = ob::instance();
        
        // declared objects
        $dec_ob = array_keys(get_object_vars($OB));       
       
        foreach ($dec_ob as $key)                                
        {   
            if( ! isset($this->$key) AND $key != $this->myself) 
            {
                $this->$key = $OB->$key;
            }
            
        }
        
        
    } // end func.
    
    
}
// END Model Class
