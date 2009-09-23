<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC-min Framework software for PHP 5.2.4 or newer
 * Derived from Code Igniter
 *
 * @package         obullo
 * @filename        base/libraries/Controller.php        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */

 /**
 * Obullo Controller
 * 
 * @package         Obullo 
 * @subpackage      Base.libraries     
 * @category        Libraries
 * @version         1.0
 * @version         1.1 renamed Register as ob::register
 */   
 
Class Controller extends ob
{
    
    function __construct()       
    {   
        $this->ob_init();
        
        parent::__construct();
    }

    function ob_init()
    {
      // Load Automatically None Static Classes.
      /*
        $classes = array(
                    'config'    => 'Config',
                    'input'        => 'Input',
                    'benchmark'    => 'Benchmark',
                    'uri'        => 'URI',
                    'output'    => 'Output',
                    'lang'        => 'Language',
                    'router'    => 'Router'
                    );
      */
        $Classes = array(
                            'config'  => 'Config',
                            'input'   => 'Input',
                            'uri'     => 'URI',
                            );
        
        foreach ($Classes as $public_var => $Class)
        {
            $this->$public_var = base_register($Class);
        }
      
      // from now on we can none static classes like this
      // like this $this->class->function(); 
    
    } //end function.

         
} //end class.
  
  
?>
