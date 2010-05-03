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
 * Obullo Html Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Güvenç
 * @link        
 */
// ------------------------------------------------------------------------

/**
* Load Global Css File
* 
* @author Ersin Güvenç
* @param  string $filename
* @param  string $arguments
* @param  string $path
* @param  string $type
* @return _css
*/
function css($filename, $arguments = '', $path = 'css', $type = ' type="text/css" ')
{   
    return _css($filename, $arguments, $dir = 'source', $path, $type);
}

// --------------------------------------------------------------------

/**
* Load Local Css File from
* current controller directory.
* 
* @author Ersin Güvenç
* @param  string $filename
* @param  string $arguments
* @param  string $path
* @param  string $type
* @return _css
*/
function local_css($filename, $arguments = '', $path = 'css', $type = ' type="text/css" ')
{   
    return _css($filename, $arguments, $dir = 'local', $path, $type);
}

// --------------------------------------------------------------------

/**
* Build css files in <head> tags
* 
* @author   Ersin Güvenç
* @param    mixed   $filename array or string
* @param    string  $media  'all' or 'print' etc..
* @param    string  $arguments add argument
* @param    string  $dir application or directory css
* @version  0.1
* @version  0.2 added $path variable
* @version  0.2 added ob::instance()->content->css_folder variable
* @return   string
*/
function _css($filename, $arguments = ' media="all" ', $dir = 'source', $path = '', $type = ' type="text/css" ')
{
    if( ! is_array($filename))
    $filename = array($filename);
    
    if(empty($arguments))
    {
        $arguments = ' media="all" ';
    }
    
    $path = 'css';
    
    if(isset(ob::instance()->content->css_folder{1}))
    {
        $path = 'css' . ob::instance()->content->css_folder; 
    }
    
    $style = "<style $type $arguments>\n";

    switch ($dir)
    {
       case 'local':
       $url = config_item('base_url').'application/directories/'.$GLOBALS['d'].'/views/'.$path;
         break;
         
       case 'source':
       $url = config_item('source_url').$path;
         break;
    }
    
    foreach($filename as $key => $css)
    {
        $style.= "@import url(\"". $url .'/'. $css .'.css'. "\");\n";
    }
    
    $style.= '</style>';

    return $style;   
}

// --------------------------------------------------------------------

/**
* Load global js file.
* 
* @author Ersin Güvenç
* @param  string $filename
* @param  string $arguments
* @param  string $type
*/
function js($filename, $arguments = '', $type = ' type="text/javascript" ')
{   
    return _js($filename, $arguments, 'source'); 
}

// --------------------------------------------------------------------

/**
* Load local js file from
* current controller directory.
* 
* @author Ersin Güvenç
* @param  string $filename
* @param  string $arguments
* @param  string $type
*/
function local_js($filename, $arguments = '', $type = ' type="text/javascript" ')
{   
    return _js($filename, $arguments, 'local'); 
}

// --------------------------------------------------------------------

/**
* Build js files in <head> tags
* 
* @author   Ersin Güvenç
* @param    string $filename
* @param    string $arguments
* @param    string $dir
* @param    string $type
*/
function _js($filename, $arguments = '', $dir = 'source', $type = ' type="text/javascript" ')
{
    switch ($dir)
    {
       case 'local':
       $url = config_item('base_url').'application/directories/'.$GLOBALS['d'].'/views/js/';
         break;
         
       case 'source':
       $url = config_item('source_url').'js/';
         break;
    }

   return "\n".'<script '.$type.' src="'.$url.$filename.'.js'.$arguments.'"></script>'; 
}

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
                $img .= ' src="'.$OB->config->slash_item('base_url').$v.'" ';
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
 * Doctype
 *
 * Generates a page document type declaration
 *
 * Valid options are xhtml-11, xhtml-strict, xhtml-trans, xhtml-frame,
 * html4-strict, html4-trans, and html4-frame.  Values are saved in the
 * doctypes config file.
 *
 * @access   public
 * @param    string    type    The doctype to be generated
 * @return   string
 */
function doctype($type = 'xhtml1-strict')
{
    global $_doctypes;

    if ( ! is_array($_doctypes))
    {
        if ( ! require_once(APP.'config'.DS.'doctypes.php'))
        {
            return FALSE;
        }
    }

    if (isset($_doctypes[$type]))
    {
        return $_doctypes[$type];
    }
    else
    {
        return FALSE;
    }
}

// ------------------------------------------------------------------------

/**
 * Link
 *
 * Generates link to a CSS file
 *
 * @access   public
 * @param    mixed    stylesheet hrefs or an array
 * @param    string   rel
 * @param    string   type
 * @param    string   title
 * @param    string   media
 * @param    boolean  should index_page be added to the css path
 * @return   string
 */
function link_tag($href = '', $rel = 'stylesheet', $type = 'text/css', $title = '', $media = '', $index_page = FALSE)
{
    $OB = ob::instance();

    $link = '<link ';

    if (is_array($href))
    {
        foreach ($href as $k=>$v)
        {
            if ($k == 'href' AND strpos($v, '://') === FALSE)
            {
                if ($index_page === TRUE)
                {
                    $link .= ' href="'.$OB->config->site_url($v).'" ';
                }
                else
                {
                    $link .= ' href="'.$OB->config->slash_item('base_url').$v.'" ';
                }
            }
            else
            {
                $link .= "$k=\"$v\" ";
            }
        }

        $link .= "/>";
    }
    else
    {
        if ( strpos($href, '://') !== FALSE)
        {
            $link .= ' href="'.$href.'" ';
        }
        elseif ($index_page === TRUE)
        {
            $link .= ' href="'.$OB->config->site_url($href).'" ';
        }
        else
        {
            $link .= ' href="'.$OB->config->slash_item('base_url').$href.'" ';
        }

        $link .= 'rel="'.$rel.'" type="'.$type.'" ';

        if ($media    != '')
        {
            $link .= 'media="'.$media.'" ';
        }

        if ($title    != '')
        {
            $link .= 'title="'.$title.'" ';
        }

        $link .= '/>';
    }


    return $link;
}

// ------------------------------------------------------------------------

/**
 * Generates meta tags from an array of key/values
 *
 * @access    public
 * @param    array
 * @return    string
 */
function meta($name = '', $content = '', $type = 'name', $newline = "\n")
{
    // Since we allow the data to be passes as a string, a simple array
    // or a multidimensional one, we need to do a little prepping.
    if ( ! is_array($name))
    {
        $name = array(array('name' => $name, 'content' => $content, 'type' => $type, 'newline' => $newline));
    }
    else
    {
        // Turn single array into multidimensional
        if (isset($name['name']))
        {
            $name = array($name);
        }
    }

    $str = '';
    foreach ($name as $meta)
    {
        $type         = ( ! isset($meta['type']) OR $meta['type'] == 'name') ? 'name' : 'http-equiv';
        $name         = ( ! isset($meta['name']))     ? ''     : $meta['name'];
        $content    = ( ! isset($meta['content']))    ? ''     : $meta['content'];
        $newline    = ( ! isset($meta['newline']))    ? "\n"    : $meta['newline'];

        $str .= '<meta '.$type.'="'.$name.'" content="'.$content.'" />'.$newline;
    }

    return $str;
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