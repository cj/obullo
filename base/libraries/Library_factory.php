<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 Minimalist software for PHP 5.2.4 or newer
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Base.libraries        
 * @copyright       Copyright (c) 2009 Ersin Güvenç.
 * @license         http://www.opensource.org/licenses/gpl-3.0.html GPL  
 * @filesource
 */ 
 
/**
 * Library factory class
 *
 * Include library files
 *
 * @package         Obullo 
 * @subpackage      Base.libraries     
 * @category        Loader
 * @version         0.1
 */

Class OB_Library
{
    
    /**
    * Library Factory
    * 
    * @access public static
    * @author Ersin Güvenç
    * @version 1.0
    * @return boolean $file_exists
    */
    static function factory($class)
    {
        $file_exists = FALSE;
        
        $Class = strtolower($class);
        
        if(file_exists(APP.'libraries'.DIRECTORY_SEPARATOR.$Class.EXT)) 
        {   
            $file_exists = TRUE;
            
            require(APP.'libraries'.DIRECTORY_SEPARATOR.$Class.EXT);
            
        } elseif(file_exists(BASE.'libraries'.DIRECTORY_SEPARATOR.ucfirst($Class).EXT))
        {
            $file_exists = TRUE;
            
            require(BASE.'libraries'.DIRECTORY_SEPARATOR.ucfirst($Class).EXT);
        }
        
        return $file_exists;
        
    } // end func.
    
    
    // @ Support for loader::libray() inside from public model functions 
    // If you declare a library like this loader::library(); 
    // inside from model __construct() it works good this ok
    // because loader::model() function already loads it via $OB->$model_name->_asn_lib();
    // but when u declare it inside a model function it will not work
    // so you will get an error: Undefined property: Model_test::$myclass
    // This function fix the problem, assigns all library files to model 

    static function asn_to_models()
    {
        $OB = ob::instance();
        
        if (count($OB->mods) == 0)
        return;
        
        foreach ($OB->mods as $model_name)
        $OB->$model_name->_asn_lib();
    }
    
    
    static function asn_to_libraries()
    {
        $OB = ob::instance();
        
        if (count($OB->libs) == 0)
        return;
        
        foreach ($OB->libs as $lib_name)
        $OB->$lib_name->_asn_lib();
    }
    
        
               
} // end class.
        
?>
