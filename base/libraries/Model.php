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
 */                    

Class Model {

public $cm;   // called model

    function __construct()
    {
        // assign obullo libraries to all called models
        $this->_asn_lib(); 
        
        // this is just called model name 
        $this->cm = ucfirst(get_class($this));
    }
    
    function _asn_lib()
    {
        $OB = ob::instance();
        
        // declared objects
        $dec_ob = array_keys(get_object_vars($OB));
        //print_r($dec_ob);  open this line you can see the declared objects.
        //exit;
        
        //for using to declared objects and variables inside model that we assign before
        //we must assign them again inside to model class.
       
        foreach ($dec_ob as $key)
        {   
            if(!isset($this->$key) AND $key != $this->cm  AND $key != 'db')
            {
                $this->$key = $OB->$key;
            }
            
        }
        
    } // end func.
    
    
}
// END Model Class
