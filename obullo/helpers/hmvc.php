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
* @param    string  $request
* @param    integer $cache_time
* @return   object of HMVC class
*/
if( ! function_exists('hmvc_call') ) 
{
    function hmvc_call($request, $cache_time = 0)
    {
        $hmvc = base_register('HMVC');
        $hmvc->clear();                 // clear variables for each request.
        $hmvc->hmvc_request($request, $cache_time);
        
        return $hmvc;  // return to HMVC Object.
    }  

}

/* End of file hmvc.php */
/* Location: ./base/helpers/hmvc.php */