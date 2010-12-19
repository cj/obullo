<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009 - 2010.
 *
 * PHP5 MVC Based Minimalist Software.
 *
 * @package         obullo    
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */
 
  /**
 * Global Controllers ( GC ) 
 * Dual Controller, Model and View (c) 2010  
 * 
 * @package         Obullo   
 * @subpackage      Base.controller 
 * @category        Controller
 * @author          Global Controllers (c) Ersin Guvenc
 * 
 * @version 0.1 removed Obullo.php and moved all contents to Controller.php.
 * @version 0.2 depreciated old global controllers functionality, deleted parse_parents() func.
 */

define('OBULLO_VERSION', '1.0.1');
 
 /**
 * Controller Class.
 *
 * Main Controller class.
 *
 * @package         Obullo 
 * @subpackage      Base.obullo     
 * @category        Libraries
 * @version         0.1
 * @version         0.2 added extends App_controller
 */
Class Controller extends App_controller {

    private static $instance;
    
    public function __construct()       
    {   
        self::$instance = &$this;
                             
        $this->config = base_register('Config');
        $this->router = base_register('Router');
        $this->uri    = base_register('URI');
        $this->output = base_register('Output');
                                
        parent::__autoloader();     // Initialize to Application Controller __autoloader().
                                    // This functionality added in version 1.0.1
        
        log_me('debug', "Controller Class Initialized");
    }

    /**
    * this();
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
    
}

/**
* @author  Ersin Guvenc
* 
* A Pretty handy function this();
* We use "this()" function if not available $this anywhere.
*/
function this() { return Controller::instance(); }

// END Controller Class

/* End of file Controller.php */
/* Location: ./base/obullo/Controller.php */