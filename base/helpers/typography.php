<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Güvenç (c) 2009.
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Typography Helpers

 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
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
function nl2br_except_pre($str)
{
	loader::base_lib('typography');
	
	return this()->typography->nl2br_except_pre($str);
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
function auto_typography($str, $reduce_linebreaks = FALSE)
{
	loader::base_lib('typography');
	
    return this()->typography->auto_typography($str, $reduce_linebreaks);
}

/* End of file typography_helper.php */
/* Location: ./base/helpers/typography.php */