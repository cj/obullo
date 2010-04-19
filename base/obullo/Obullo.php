<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo        
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009 - 2010.
 * @since           Version 1.0
 * @filesource
 * @license
 */
 
 /**
 * Obullo MVC2 (Dual Controller, Model and View) (c) 2010  
 * 
 * @package         Obullo   
 * @subpackage      Base.obullo  
 * @category        Controller
 * 
 * @version 0.1
 * @version 0.2 added core functions like ob::register
 * @version 0.3 added extending to SSC, moved register
 *              functions to common.php
 * @version 0.4 @deprecated SSC, added $load variable for loader support
 *              helpers, functions..
 * @version 0.5 added 'Obullo MVC2 Global controller' functionality
 * @version 0.6 added this() shortcut function, added get_config();
 */

define('OBULLO_VERSION', 'Obullo Beta 1.0 rc1');

//------------- Global Controller Extend Switch --------------//

$_parents = get_config('parents');

$_controller = $GLOBALS['d'].'.'.$GLOBALS['c'];

if(isset($_parents[$_controller]))
{
    if( ! file_exists(APP.'parents'.DS.$_parents[$_controller].EXT))
    throw new CommonException('Unable locate to /parents controller file: '.$_controller.EXT);
    
    require(APP.'parents'.DS.$_parents[$_controller].EXT); 

    eval('Class Core_Controller extends '.$_parents[$_controller].'{}');
      
} else 
{   
    require(APP.'parents'.DS.'Global_controller'.EXT);
    
    eval('Class Core_Controller extends Global_controller{}');   
}

//------------- Global Controller Extend Switch --------------//

/**
* Obullo Core Class (Super Object)
*/
Class ob extends Core_Controller
{   
    /**
    * Obullo Models
    * Store all loaded Models
    * 
    * @var array
    */
    public $_mods = array();
    
    /**
    * Obullo Libraries
    * Store all loaded Libraries
    * 
    * @var array
    */
    public $_libs = array();
    
    /**
    * Obullo Databases
    * Store all loaded Database Objects
    * 
    * @var array
    */
    public $_dbs = array();
       
    /**
    * Obullo instance
    * 
    * @var object
    */
    private static $instance;

    /**
    * Construct func.
    * @return void
    */
    public function __construct()
    {   
        self::$instance = &$this;
    }

    /**
    * ob::instance();
    * 
    * Obullo Super Object in Every Where
    *  
    * @author Ersin Güvenç
    * @version 1.0 
    * @version 1.1 get_instance renamed and moved here
    * @return object
    */
    public static function instance()
    {       
        return self::$instance;
    } 
    
} // end class.
                          
/**
* @author  Ersin Güvenç
* 
* A Pretty handy function for ob::instance();
* ob::instance = $this so this() = ob::instance;
* 
* We use "this()" function in View files.
*/
function this(){ return ob::instance(); }

?>
