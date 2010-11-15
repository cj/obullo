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
 * Global Controllers ( GC ) 
 * Dual Controller, Model and View (c) 2010  
 * 
 * @package         Obullo   
 * @subpackage      Base.obullo  
 * @category        Controller
 * @author          Global Controllers (c) Ersin Guvenc
 * 
 * @version 0.1
 * @version 0.2 added core functions like ob::register
 * @version 0.3 added extending to SSC, moved register
 *              functions to common.php
 * @version 0.4 @deprecated SSC pattern, added $load variable for loader support
 *              helpers, functions..
 * @version 0.5 added 'Obullo Global controller' functionality
 * @version 0.6 added this() shortcut function, added get_config();
 * @version 0.7 !! Returns of the SSC pattern !! :), added SSC class.
 * @version 0.8 Moved ssc to ssc.php , added extend switch support to foreach folders.
 * @version 0.9 added '*' function, added profiler_set() function.
 * @version 1.0 added parse_parents() function, ob:instance() renamed as Obullo::instance()
 * @version 1.1 changed Obullo Mvc2 name as Obullo GC
 */

define('OBULLO_VERSION', '1.0.1');

//------------- Global Controller Extend Switch --------------//

function parse_parents()
{
    $_parents = get_config('parents');

    if(isset($_parents['directory'][$GLOBALS['d']]))  // Is there a literal directory match ?
    {
        return (string)$_parents['controller'][$GLOBALS['d']];
    }

    if(isset($_parents['controller'][$GLOBALS['c']]))  // Is there a literal controller match ?
    {
        return (string)$_parents['controller'][$GLOBALS['c']];
    }

    // Loop through the parents array looking for directory wild-cards
    foreach($_parents['directory'] as $pattern => $gc) 
    {
        if (preg_match('/'.$pattern.'/', $GLOBALS['c']))
        {     
            return (string)$_parents['controller'][$pattern];
        }
    }
    
    // Loop through the parents array looking for controller wild-cards
    foreach($_parents['controller'] as $pattern => $gc)
    {
        if (preg_match('/'.$pattern.'/', $GLOBALS['c']))
        {     
            return (string)$_parents['controller'][$pattern];
        }
    }
    
    return 'Global_controller';
}

$_Global_controller = parse_parents();

if( ! file_exists(APP .'parents'. DS .$_Global_controller. EXT)) 
{
     throw new CommonException('Unable locate to Global Controller file: '.$_Global_controller. EXT);
}

loader::file('parents'. DS .$_Global_controller. EXT);
profiler_set('files', 'parents'. DS .'App_controller'. EXT,  APP .'parents'. DS .'App_controller'. EXT);

eval('Class Controller_CORE extends '.$_Global_controller.'{}'); 

//------------- Global Controller Pattern Extend Switch --------------// 


/**
* Obullo Core Class (Super Object) (c) 2010.
* @author   Ersin Guvenc
*/
Class Obullo extends Controller_CORE
{       
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
    * Obullo::instance();
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
* A Pretty handy function for Obullo::instance();
* Obullo::instance = $this so this() = Obullo::instance isn't it ?
* 
* We use "this()" function if not available $this in anywhere.
*/
function this() { return Obullo::instance(); }


// END Ob Class

/* End of file Obullo.php */
/* Location: ./base/obullo/Obullo.php */