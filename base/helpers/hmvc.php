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
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Hmvc Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Call HMVC Request using HMVC Class.
* 
* @access   public
* @param    string $request
* @return   object of HMVC class
*/
if( ! function_exists('hmvc_call') ) 
{
    function hmvc_call($request)
    {
        $hmvc = base_register('HMVC');
        $hmvc->clear();
        $hmvc->hmvc_request($request);
        
        // return to HMVC Object.
        return $hmvc;
    }  

}

/* End of file hmvc.php */
/* Location: ./base/helpers/hmvc.php */