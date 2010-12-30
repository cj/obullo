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
if( ! function_exists('heading') ) 
{
    function heading($data = '', $h = '1')
    {
        return "<h".$h.">".$data."</h".$h.">";
    }
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
if( ! function_exists('ul') ) 
{
    function ul($list, $attributes = '')
    {
        return _list('ul', $list, $attributes);
    }
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
if( ! function_exists('ol') ) 
{
    function ol($list, $attributes = '')
    {
        return _list('ol', $list, $attributes);
    }
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
if( ! function_exists('_list') ) 
{
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
}

// ------------------------------------------------------------------------

/**
* Generates HTML BR tags based on number supplied
*
* @access   public
* @param    integer
* @return   string
*/
if( ! function_exists('br') ) 
{
    function br($num = 1)
    {
        return str_repeat("<br />", $num);
    }
}

// ------------------------------------------------------------------------

/**
* Image
*
* Generates an <img /> element
*
* @access   public
* @param    mixed    $src  sources folder image path via filename
* @param    boolean  $index_page
* @param    string   $attributes
* @version  0.1
* @version  0.2      added view_set_folder('img'); support
* @version  0.2      added $attributes variable
* @return   string
*/
if( ! function_exists('img') ) 
{
    function img($src = '', $attributes = '', $index_page = FALSE)
    {
        if ( ! is_array($src) )
        {
            $src = array('src' => $src);
        }

        $vi = Ssc::instance();       // obullo changes ..
                
        // When user use view_set_folder('img');
        $path = '';
        if(isset($vi->_ew->img_folder{1}))
        {
            $path = $vi->_ew->img_folder . '/'; 
        }
        
        $img = '<img';

        foreach ($src as $k => $v)
        {
            $v = ltrim($v, '/');   // remove first slash
            
            if ($k == 'src' AND strpos($v, '://') === FALSE)
            {
                $ob = this();

                if ($index_page === TRUE)
                {
                    $img .= ' src="'.$ob->config->site_url($v).'" ';
                }
                else
                {
                    $img .= ' src="'.$ob->config->public_url(). $path . $v .'" ';   // Obullo changes..
                }
            }
            else
            {
                $img .= " $k=\"$v\" ";   // for http://
            }
        }

        $img .= $attributes . ' />';

        return $img;
    }
}
// ------------------------------------------------------------------------

/**
 * Generates non-breaking space entities based on number supplied
 *
 * @access   public
 * @param    integer
 * @return   string
 */
if( ! function_exists('nbs') ) 
{
    function nbs($num = 1)
    {
        return str_repeat("&nbsp;", $num);
    }
}

/* End of file body_tag.php */
/* Location: ./base/helpers/body_tag.php */