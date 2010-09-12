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
 * Obullo Array Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */

// ------------------------------------------------------------------------

/**
 * Element
 *
 * Lets you determine whether an array index is set and whether it has a value.
 * If the element is empty it returns FALSE (or whatever you specify as the default value.)
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */	
if( ! function_exists('element') ) 
{
    function element($item, $array, $default = FALSE)
    {
	    if ( ! isset($array[$item]) OR $array[$item] == "")
	    {
		    return $default;
	    }

	    return $array[$item];
    }
}	


// ------------------------------------------------------------------------

/**
 * Random Element - Takes an array as input and returns a random element
 *
 * @access	public
 * @param	array
 * @return	mixed	depends on what the array contains
 */	
if( ! function_exists('random_element') ) 
{
    function random_element($array)
    {
	    if ( ! is_array($array))
	    {
		    return $array;
	    }
	    return $array[array_rand($array)];
    }
}	


/* End of file array.php */
/* Location: ./base/helpers/array.php */