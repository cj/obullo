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
 * Obullo Inflector Helpers
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
 * Singular
 *
 * Takes a plural word and makes it singular
 *
 * @access	public
 * @param	string
 * @return	str
 */	
if ( ! function_exists('singular'))
{	
	function singular($str)
	{
		$str = strtolower(trim($str));
		$end = substr($str, -3);
	
		if ($end == 'ies')
		{
			$str = substr($str, 0, strlen($str)-3).'y';
		}
		elseif ($end == 'ses')
		{
			$str = substr($str, 0, strlen($str)-2);
		}
		else
		{
			$end = substr($str, -1);
		
			if ($end == 's')
			{
				$str = substr($str, 0, strlen($str)-1);
			}
		}
	
		return $str;
	}
}

// --------------------------------------------------------------------

/**
 * Plural
 *
 * Takes a singular word and makes it plural
 *
 * @access	public
 * @param	string
 * @param	bool
 * @return	str
 */	
if ( ! function_exists('plural'))
{	
	function plural($str, $force = FALSE)
	{
		$str = strtolower(trim($str));
		$end = substr($str, -1);

		if ($end == 'y')
		{
			// Y preceded by vowel => regular plural
			$vowels = array('a', 'e', 'i', 'o', 'u');
			$str = in_array(substr($str, -2, 1), $vowels) ? $str.'s' : substr($str, 0, -1).'ies';
		}
		elseif ($end == 's')
		{
			if ($force == TRUE)
			{
				$str .= 'es';
			}
		}
		else
		{
			$str .= 's';
		}

		return $str;
	}
}

// --------------------------------------------------------------------

/**
 * Camelize
 *
 * Takes multiple words separated by spaces or underscores and camelizes them
 *
 * @access	public
 * @param	string
 * @return	str
 */	
if ( ! function_exists('camelize'))
{	
	function camelize($str)
	{		
		$str = 'x'.strtolower(trim($str));
		$str = ucwords(preg_replace('/[\s_]+/', ' ', $str));
		return substr(str_replace(' ', '', $str), 1);
	}
}

// --------------------------------------------------------------------

/**
 * Underscore
 *
 * Takes multiple words separated by spaces and underscores them
 *
 * @access	public
 * @param	string
 * @return	str
 */	
if ( ! function_exists('underscore'))
{
	function underscore($str)
	{
		return preg_replace('/[\s]+/', '_', strtolower(trim($str)));
	}
}

// --------------------------------------------------------------------

/**
 * Humanize
 *
 * Takes multiple words separated by underscores and changes them to spaces
 *
 * @access	public
 * @param	string
 * @return	str
 */	
if ( ! function_exists('humanize'))
{	
	function humanize($str)
	{
		return ucwords(preg_replace('/[_]+/', ' ', strtolower(trim($str))));
	}
}
	

/* End of file inflector_helper.php */
/* Location: ./base/helpers/inflector_helper.php */