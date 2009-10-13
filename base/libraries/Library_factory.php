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
        
        if(file_exists(APP.'libraries'.DS.$class.EXT)) 
        {   
            $file_exists = TRUE;
            
            require(APP.'libraries'.DS.$class.EXT);
            
        } elseif(file_exists(BASE.'libraries'.DS.ucfirst($class).EXT))
        {
            $file_exists = TRUE;
            
            require(BASE.'libraries'.DS.ucfirst($class).EXT);
        }
        
        return $file_exists;
        
    } // end func.
    
               
} // end class.
        
?>
