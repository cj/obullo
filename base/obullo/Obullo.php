<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo        
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009 - 2010.
 * @since           Version 1.0
 * @filesource
 * @license
 */
 
 /**
 * Global Controllers ("Obullo MVC2") 
 * Dual Controller, Model and View (c) 2010  
 * 
 * @package         Obullo   
 * @subpackage      Base.obullo  
 * @category        Controller
 * 
 * @version 0.1
 * @version 0.2 added core functions like ob::register
 * @version 0.3 added extending to SSC, moved register
 *              functions to common.php
 * @version 0.4 @deprecated SSC pattern, added $load variable for loader support
 *              helpers, functions..
 * @version 0.5 added 'Obullo MVC2 Global controller' functionality
 * @version 0.6 added this() shortcut function, added get_config();
 * @version 0.7 !! Returns of the SSC pattern !! :), added SSC class.
 */

define('OBULLO_VERSION', 'Obullo Beta 1.0 rc1');

//------------- Global Controller Pattern Extend Switch --------------//

$_parents = get_config('parents');

$_controller = $GLOBALS['d'].'.'.$GLOBALS['c'];

if(isset($_parents[$_controller]))
{
    if( ! file_exists(APP .'parents'. DS .$_parents[$_controller]. EXT))
    throw new CommonException('Unable locate to parent controller file: '.$_controller. EXT);
    
    require(APP .'parents'. DS .$_parents[$_controller]. EXT); 

    eval('Class Controller_CORE extends '.$_parents[$_controller].'{}');
      
} else 
{   
    require(APP .'parents'. DS .'Global_controller'. EXT);
    
    eval('Class Controller_CORE extends Global_controller{}');   
}

//------------- Global Controller Pattern Extend Switch --------------//

/**
* Obullo Core Class (Super Object) (c) 2010.
* @author   Ersin Guvenc
*/
Class ob extends Controller_CORE
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
    * @author Ersin Guvenc
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
* @author  Ersin Guvenc
* 
* A Pretty handy function for ob::instance();
* ob::instance = $this so this() = ob::instance isn't it ?.
* 
* We use "this()" function in View files because of the
* readability.
*/
function this(){ return ob::instance(); }

/**
* Super Static Controllers (SSC). (c) 2010.
* Control the Procedural Functions.
* @author   Ersin Guvenc
*/
Class ssc {
    
    private static $instance;
    
    public $_ent  = NULL; // cont_ent
    public $_put  = NULL; // in_put
    public $_ity  = NULL; // secur_ity 
    public $_sion = NULL; // ses_sion

    public static function instance()
    {
        if( ! isset(self::$instance))
        {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
}


// END Ob Class

/* End of file Obullo.php */
/* Location: ./base/obullo/Obullo.php */
?>