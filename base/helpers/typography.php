<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Framework software for PHP 5.2.4 or newer
 * Derived From Code Igniter 
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @license         http://obullo.com/manual/license 
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Typography Helpers
 * Derived From Code Igniter
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      ExpressionEngine Dev Team
 * @author      Ersin Güvenç
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Convert newlines to HTML line breaks except within PRE tags
*
* @access	public
* @param	string
* @return	string
*/	
if ( ! function_exists('nl2br_except_pre'))
{
	function nl2br_except_pre($str)
	{
		$OB = ob::instance();
	
		loader::base_library('typography');
		
		return $OB->typography->nl2br_except_pre($str);
	}
}
	
// ------------------------------------------------------------------------

/**
 * Auto Typography Wrapper Function
 *
 *
 * @access	public
 * @param	string
 * @param	bool	whether to reduce multiple instances of double newlines to two
 * @return	string
 */
if ( ! function_exists('auto_typography'))
{
	function auto_typography($str, $reduce_linebreaks = FALSE)
	{
		$OB = ob::instance();	
		loader::base_library('typography');
		return $OB->typography->auto_typography($str, $reduce_linebreaks);
	}
}

/* End of file typography_helper.php */
/* Location: ./system/helpers/typography_helper.php */