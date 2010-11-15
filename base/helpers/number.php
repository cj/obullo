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
 * Obullo Number Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Formats a numbers as bytes, based on size, and adds the appropriate suffix
*
* @access	public
* @param   mixed  will be cast as int
* @param	int	   precision
* @return	string
*/
if( ! function_exists('byte_format') ) 
{
    function byte_format($num, $precision = 1)
    {
        lang_load('number');

        if ($num >= 1000000000000) 
        {
            $num = round($num / 1099511627776, $precision);
            $unit = lang('terabyte_abbr');
        }
        elseif ($num >= 1000000000) 
        {
            $num = round($num / 1073741824, $precision);
            $unit = lang('gigabyte_abbr');
        }
        elseif ($num >= 1000000) 
        {
            $num = round($num / 1048576, $precision);
            $unit = lang('megabyte_abbr');
        }
        elseif ($num >= 1000) 
        {
            $num = round($num / 1024, $precision);
            $unit = lang('kilobyte_abbr');
        }
        else
        {
            $unit = lang('bytes');
            return number_format($num).' '.$unit;
        }

        return number_format($num, $precision).' '.$unit;
    }    
}

/* End of file number.php */
/* Location: ./base/helpers/number.php */