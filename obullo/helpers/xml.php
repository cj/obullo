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
 * Obullo Xml Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Convert Reserved XML characters to Entities
*
* @access	public
* @param	string
* @return	string
*/
if ( ! function_exists('xml_convert'))
{
    function xml_convert($str, $protect_all = FALSE)
    {
        $temp = '__TEMP_AMPERSANDS__';

        // Replace entities to temporary markers so that 
        // ampersands won't get messed up    
        $str = preg_replace("/&#(\d+);/", "$temp\\1;", $str);

        if ($protect_all === TRUE)
        {
            $str = preg_replace("/&(\w+);/",  "$temp\\1;", $str);
        }

        $str = str_replace(array("&","<",">","\"", "'", "-"),
                            array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;", "&#45;"),
                            $str);

        // Decode the temp markers back to entities        
        $str = preg_replace("/$temp(\d+);/","&#\\1;",$str);

        if ($protect_all === TRUE)
        {
            $str = preg_replace("/$temp(\w+);/","&\\1;", $str);
        }

        return $str;
    }    
}

/* End of file xml.php */
/* Location: ./base/helpers/xml.php */