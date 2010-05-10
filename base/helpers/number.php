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
 * Obullo Number Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Güvenç
 * @link        
 */

// ------------------------------------------------------------------------

/**
 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
 *
 * @access	public
 * @param	mixed	// will be cast as int
 * @return	string
 */
function byte_format($num)
{                         
	$OB = ob::instance();
	$OB->lang->load('number');

	if ($num >= 1000000000000) 
	{
		$num = round($num / 1099511627776, 1);
		$unit = $OB->lang->line('terabyte_abbr');
	}
	elseif ($num >= 1000000000) 
	{
		$num = round($num / 1073741824, 1);
		$unit = $OB->lang->line('gigabyte_abbr');
	}
	elseif ($num >= 1000000) 
	{
		$num = round($num / 1048576, 1);
		$unit = $OB->lang->line('megabyte_abbr');
	}
	elseif ($num >= 1000) 
	{
		$num = round($num / 1024, 1);
		$unit = $OB->lang->line('kilobyte_abbr');
	}
	else
	{
		$unit = $OB->lang->line('bytes');
		return number_format($num).' '.$unit;
	}

	return number_format($num, 1).' '.$unit;
}

/* End of file number.php */
/* Location: ./base/helpers/number.php */
?>