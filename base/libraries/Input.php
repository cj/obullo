<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC-min Framework software for PHP 5.2.4 or newer
 *
 * @package         obullo
 * @filename        base/libraries/Session.php        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */ 

 
 //just simulation we implement it later...
Class OB_Input 
{
    
    public $items = array();
    
    function set($key,$val)
    {
        $this->items[$key] = $val;
    }
    
    function get($key)
    {
        return $this->items[$key];
    }
    
    function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
    
  
}//end of the class..

?>