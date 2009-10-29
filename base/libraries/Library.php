<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 Minimalist software for PHP 5.2.4 or newer
 * Derived From Code Igniter
 * 
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.libraries        
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
 * @license         http://www.opensource.org/licenses/gpl-3.0.html GPL  
 * @filesource
 */
  
 /**
 * Library Class
 *
 * @package         Obullo 
 * @subpackage      Base.libraries     
 * @category        Library
 * @version         0.1
 */
 
Class Library extends ob
{
    
public $myself = ''; 
    
    function __construct()
    {
        $this->_asn_lib(); 
        
        // this is just called model name 
        $this->myself = ucfirst(get_class($this));
    }
    
    function _asn_lib()
    {
        $OB = ob::instance();
        
        // declared objects
        $dec_ob = array_keys(get_object_vars($OB));
       
        // print_r($dec_ob);

        foreach ($dec_ob as $key)                                 
        {   
            if( ! isset($this->$key) AND $key != $this->myself)
            {
                $this->$key = $OB->$key;
            }
            
        }
        
    } // end func.
    
    
} // end class.
 
 

?>
