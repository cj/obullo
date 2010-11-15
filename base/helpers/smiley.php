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
 * Obullo Smiley Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Smiley Javascript
*
* Returns the javascript required for the smiley insertion.  Optionally takes
* an array of aliases to loosely couple the smiley array to the view.
*
* @access	public
* @param	mixed	alias name or array of alias->field_id pairs
* @param	string	field_id if alias name was passed in
* @return	array
*/
if( ! function_exists('smiley_js') ) 
{
    function smiley_js($alias = '', $field_id = '')
    {
	    static $do_setup = TRUE;

	    $r = '';

	    if ($alias != '' && ! is_array($alias))
	    {
		    $alias = array($alias => $field_id);
	    }

	    if ($do_setup === TRUE)
	    {
			    $do_setup = FALSE;
		    
			    $m = array();
		    
			    if (is_array($alias))
			    {
				    foreach($alias as $name => $id)
				    {
					    $m[] = '"'.$name.'" : "'.$id.'"';
				    }
			    }
		    
			    $data['m'] = '{'.implode(',', $m).'}';      
                
                $r .= _load_script(BASE .'scripts'. DS, 'smiley', $data);    // Obullo Changes ...
	    }
	    else
	    {
		    if (is_array($alias))
		    {
                $r .= '<script type="text/javascript" charset="utf-8">'; 
                
			    foreach($alias as $name => $id)
			    {
                    $r .= 'smiley_map["'.$name.'"] = "'.$id.'";'."\n";
			    }
                
                $r .= '</script>'; 
		    }
	    }

	    return $r;
    }
}
// ------------------------------------------------------------------------

/**
* Get Clickable Smileys
*
* Returns an array of image tag links that can be clicked to be inserted 
* into a form field.  
*
* @access	public
* @param	string	the URL to the folder containing the smiley images
* @return	array
*/
if( ! function_exists('get_smileys') ) 
{
    function get_smileys($image_url, $alias = '', $smileys = NULL)
    {
	    // For backward compatibility with js_insert_smiley
	    
	    if (is_array($alias))
	    {
		    $smileys = $alias;
	    }
	    
	    if ( ! is_array($smileys))
	    {
		    if (FALSE === ($smileys = _get_smiley_array()))
		    {
			    return $smileys;
		    }
	    }

	    // Add a trailing slash to the file path if needed
	    $image_url = rtrim($image_url, '/').'/';

	    $used = array();
	    foreach ($smileys as $key => $val)
	    {
		    // Keep duplicates from being used, which can happen if the
		    // mapping array contains multiple identical replacements.  For example:
		    // :-) and :) might be replaced with the same image so both smileys
		    // will be in the array.
		    if (isset($used[$smileys[$key][0]]))
		    {
			    continue;
		    }
		    
		    $link[] = "<a href=\"javascript:void(0);\" onClick=\"insert_smiley('".$key."', '".$alias."')\"><img src=\"".$image_url.$smileys[$key][0]."\" width=\"".$smileys[$key][1]."\" height=\"".$smileys[$key][2]."\" alt=\"".$smileys[$key][3]."\" style=\"border:0;\" /></a>";	

		    $used[$smileys[$key][0]] = TRUE;
	    }

	    return $link;
    }
}
// ------------------------------------------------------------------------

/**
* Parse Smileys
*
* Takes a string as input and swaps any contained smileys for the actual image
*
* @access	public
* @param	string	the text to be parsed
* @param	string	the URL to the folder containing the smiley images
* @return	string
*/
if( ! function_exists('parse_smileys') ) 
{
    function parse_smileys($str = '', $image_url = '', $smileys = NULL)
    {
	    if ($image_url == '')
	    {
		    return $str;
	    }

	    if ( ! is_array($smileys))
	    {
		    if (FALSE === ($smileys = _get_smiley_array()))
		    {
			    return $str;
		    }
	    }

	    // Add a trailing slash to the file path if needed
	    $image_url = preg_replace("/(.+?)\/*$/", "\\1/",  $image_url);

	    foreach ($smileys as $key => $val)
	    {
		    $str = str_replace($key, "<img src=\"".$image_url.$smileys[$key][0]."\" width=\"".$smileys[$key][1]."\" height=\"".$smileys[$key][2]."\" alt=\"".$smileys[$key][3]."\" style=\"border:0;\" />", $str);
	    }

	    return $str;
    }
}
// ------------------------------------------------------------------------

/**
* Get Smiley Array
*
* Fetches the config/smiley.php file
*
* @access	private
* @return	mixed
*/
if( ! function_exists('_get_smiley_array') ) 
{
    function _get_smiley_array()
    {
	    if ( ! file_exists(APP .'config'. DS .'smileys'. EXT))
	    {
		    return FALSE;
	    }

	    $smileys = get_config('smileys');   // Obullo Changes ...

	    if ( ! isset($smileys) OR ! is_array($smileys))
	    return FALSE;

	    return $smileys;
    }
}

/* End of file smiley.php */
/* Location: ./base/helpers/smiley.php */