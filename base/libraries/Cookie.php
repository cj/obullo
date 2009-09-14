<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Framework software for PHP 5.2.4 or newer
 *
 * @package         obullo
 * @filename        base/libraries/Cookie.php        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */ 
 
Class Cookie 
{

private $time;
private $cookie_path;
private $cookie_domain;
    
    function __construct()
    {
        //global $config;
        
        $this->time = time() + (7 * 24 * 60 * 60);
        $this->cookie_path =  "/";                    //$config['cookie_path'];
        $this->cookie_domain = ".yourdomain.com";     //$config['cookie_domain'];
    }
    
    
    function set($key,$val)
    {
        setcookie( $key, $val, $this->time ,$this->cookie_path, $this->cookie_domain, false, true);
    }
    
    function _unset(){
        
        $_COOKIE = array();
    }
    
    function get($key)
    {
        
        if(!empty($_COOKIE[$key])){
        return $_COOKIE[$key];
        }else{
        return false;
        }
    }
    
    function set_data($data = array())
    {
        if(is_array($data))
        {
            foreach($data as $key=>$val)
            {
            $_COOKIE[$key] = $val;
            }
        }
    }
  
} //end of the class..

 
?>
