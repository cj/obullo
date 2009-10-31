<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC-min Framework software for PHP 5.2.4 or newer
 * Derived from Code Igniter
 *
 * @package         obullo    
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
 * @version         1.1 renamed Register as base_register
 * @version         1.2 added 'extends to ob'
 * @version         1.3 added __autoloader()
 * @deprecated      self::__autoloader()
 */   
 
Class Controller extends ob
{
    
    function __construct()       
    {   
        // WARNING ! parent::__construct()
        // must be at the top otherwise
        // __autoloader functionality does not work !
        parent::__construct();
        
        $this->ob_init();
    }

    function ob_init()
    {
       // Load Automatically Base None Static Classes.

        $Classes = array(                          
                            'config'    => 'Config',
                            'input'     => 'Input',
                            //'benchmark' => 'Benchmark',
                            'uri'       => 'URI',
                            //'output'    => 'Output',
                            'lang'      => 'Language',
                            'router'    => 'Router'
                            );
        
        foreach ($Classes as $public_var => $Class)
        {
            $this->$public_var = base_register($Class);
        }
               
        
      // deprecated autloader
      //$this->__autoloader();
               
      // from now on we can use none static classes like this
      // like this $this->class->function(); 
    
    } //end function.

         
} //end class.
  
  
?>
