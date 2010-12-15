<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
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
 * Controller Class.
 *
 * Main Controller class.
 *
 * @package         Obullo 
 * @subpackage      Base.obullo     
 * @category        Libraries
 * @version         0.1
 */
Class Controller extends Obullo {

    public function __construct()       
    {   
        parent::__construct();  // Should be at the top.
                                
        $this->_ob_init();
        
        log_me('debug', "Controller Class Initialized");
    }

    /**
    * Initialize to default base libraries.
    * 
    * @author   Ersin Guvenc
    * @return   void
    */
    private function _ob_init()
    {
        $this->config = base_register('Config');
        $this->router = base_register('Router');
        $this->uri    = base_register('URI');
        $this->output = base_register('Output');
    }
}
// END Controller Class

/* End of file Controller.php */
/* Location: ./base/obullo/Controller.php */