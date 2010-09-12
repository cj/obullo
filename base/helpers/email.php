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
 * Obullo Email Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Validate email address
*
* @access	public
* @return	bool
*/
if( ! function_exists('valid_email') ) 
{
    function valid_email($address)
    {
	    return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
    }
}

// ------------------------------------------------------------------------

/**
* Send an email
*
* @access	public
* @return	bool
*/
if( ! function_exists('send_email') ) 
{	
    function send_email($recipient, $subject = 'Test email', $message = 'Hello World')
    {
	    return mail($recipient, $subject, $message);
    }
}

/* End of file email.php */
/* Location: ./base/helpers/email.php */