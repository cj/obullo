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

// ------------------------------------------------------------------------

/**
 * Shortcut For Lang Class       
 */
 
Class lang {
      
    public static function line($line = '')   
    {   
        return ob::instance()->lang->line($line);
    }
   
} // end of the class.

?>