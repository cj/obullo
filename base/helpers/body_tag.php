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
 * Obullo Body Tag Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */
// --------------------------------------------------------------------

/**
 * Heading
 *
 * Generates an HTML heading tag.  First param is the data.
 * Second param is the size of the heading tag.
 *
 * @access   public
 * @param    string
 * @param    integer
 * @return   string
 */
function heading($data = '', $h = '1')
{
    return "<h".$h.">".$data."</h".$h.">";
}

// ------------------------------------------------------------------------

/**
 * Unordered List
 *
 * Generates an HTML unordered list from an single or multi-dimensional array.
 *
 * @access   public
 * @param    array
 * @param    mixed
 * @return   string
 */
function ul($list, $attributes = '')
{
    return _list('ul', $list, $attributes);
}
 
// ------------------------------------------------------------------------

/**
 * Ordered List
 *
 * Generates an HTML ordered list from an single or multi-dimensional array.
 *
 * @access   public
 * @param    array
 * @param    mixed
 * @return   string
 */
function ol($list, $attributes = '')
{
    return _list('ol', $list, $attributes);
}

// ------------------------------------------------------------------------

/**
 * Generates the list
 *
 * Generates an HTML ordered list from an single or multi-dimensional array.
 *
 * @access   private
 * @param    string
 * @param    mixed
 * @param    mixed
 * @param    intiger
 * @return   string
 */
function _list($type = 'ul', $list, $attributes = '', $depth = 0)
{
    // If an array wasn't submitted there's nothing to do...
    if ( ! is_array($list))
    {
        return $list;
    }

    // Set the indentation based on the depth
    $out = str_repeat(" ", $depth);

    // Were any attributes submitted?  If so generate a string
    if (is_array($attributes))
    {
        $atts = '';
        foreach ($attributes as $key => $val)
        {
            $atts .= ' ' . $key . '="' . $val . '"';
        }
        $attributes = $atts;
    }

    // Write the opening list tag
    $out .= "<".$type.$attributes.">\n";

    // Cycle through the list elements.  If an array is
    // encountered we will recursively call _list()

    static $_last_list_item = '';
    foreach ($list as $key => $val)
    {
        $_last_list_item = $key;

        $out .= str_repeat(" ", $depth + 2);
        $out .= "<li>";

        if ( ! is_array($val))
        {
            $out .= $val;
        }
        else
        {
            $out .= $_last_list_item."\n";
            $out .= _list($type, $val, '', $depth + 4);
            $out .= str_repeat(" ", $depth + 2);
        }

        $out .= "</li>\n";
    }

    // Set the indentation for the closing tag
    $out .= str_repeat(" ", $depth);

    // Write the closing list tag
    $out .= "</".$type.">\n";

    return $out;
}

// ------------------------------------------------------------------------

/**
 * Generates HTML BR tags based on number supplied
 *
 * @access   public
 * @param    integer
 * @return   string
 */
function br($num = 1)
{
    return str_repeat("<br />", $num);
}

// ------------------------------------------------------------------------

/**
 * Image
 *
 * Generates an <img /> element
 *
 * @access   public
 * @param    mixed
 * @return   string
 */
function img($src = '', $index_page = FALSE)
{
    if ( ! is_array($src) )
    {
        $src = array('src' => $src);
    }

    $img = '<img';

    foreach ($src as $k=>$v)
    {

        if ($k == 'src' AND strpos($v, '://') === FALSE)
        {
            $OB = ob::instance();

            if ($index_page === TRUE)
            {
                $img .= ' src="'.$OB->config->site_url($v).'" ';
            }
            else
            {
                $img .= ' src="'.$OB->config->slash_item('source_url').$v.'" ';   // Obullo changes..
            }
        }
        else
        {
            $img .= " $k=\"$v\" ";
        }
    }

    $img .= '/>';

    return $img;
}

// ------------------------------------------------------------------------

/**
 * Generates non-breaking space entities based on number supplied
 *
 * @access   public
 * @param    integer
 * @return   string
 */
function nbs($num = 1)
{
    return str_repeat("&nbsp;", $num);
}


/* End of file html.php */
/* Location: ./base/helpers/html.php */
?>