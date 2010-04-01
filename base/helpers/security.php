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
 * Obullo Security Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Güvenç
 * @link        
 */

// ------------------------------------------------------------------------

/**
* XSS Filtering
*
* @access	public
* @param	string
* @param	bool	whether or not the content is an image file
* @return	string
*/	
function xss_clean($str, $is_image = FALSE)
{
	$OB = ob::instance();
    
	return $OB->input->xss_clean($str, $is_image);
}

// --------------------------------------------------------------------

/**
* Hash encode a string
*
* @access	public
* @param	string
* @return	string
*/	
function dohash($str, $type = 'sha1')
{
	if ($type == 'sha1')
	{
		if ( ! function_exists('sha1'))
		{
			if ( ! function_exists('mhash'))
			{	
				require_once(BASE.'libraries'.DIRECTORY_SEPARATOR.'Sha1'.EXT);
				$SH = new OB_SHA;
				return $SH->generate($str);
			}
			else
			{
				return bin2hex(mhash(MHASH_SHA1, $str));
			}
		}
		else
		{
			return sha1($str);
		}	
	}
	else
	{
		return md5($str);
	}
}
	
// ------------------------------------------------------------------------

/**
* Strip Image Tags
*
* @access	public
* @param	string
* @return	string
*/	
function strip_image_tags($str)
{
	$str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
	$str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);
		
	return $str;
}
	
// ------------------------------------------------------------------------

/**
* Convert PHP tags to entities
*
* @access	public
* @param	string
* @return	string
*/	
function encode_php_tags($str)
{
	return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
}

/* End of file security_helper.php */
/* Location: ./base/helpers/security.php */
