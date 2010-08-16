<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC Based Minimalist Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2010.
 * @filesource
 * @license
 */ 
 
/**
 * Obullo Head Tag Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Language
 * @author      Ersin Guvenc
 * @version     0.1
 * @version     0.2 added script functions
 * @link        
 */
// --------------------------------------------------------------------

/**
* Build css files in <head> tags
* 
* @author   Ersin Guvenc
* @param    mixed   $filename array or string
* @param    string  $title
* @param    string  $media  'all' or 'print' etc..
* @version  0.1
* @version  0.2 added $path variable
* @version  0.2 added _ent->css_folder variable
* @version  0.3 deprecated $path param
* @return   string
*/
if( ! function_exists('css') ) 
{
    function css($filename, $title = '', $media = '')
    {
        return link_tag($filename, 'stylesheet', 'text/css', $title, $media)."\n";   
    }
}
// ------------------------------------------------------------------------

/**
* Build js files in <head> tags
* 
* @author   Ersin Guvenc
* @param    string $filename  it can be via a path
* @param    string $arguments
* @param    string $type
* @version  0.1
* @version  0.2 removed /js dir 
* 
*/
if( ! function_exists('js') ) 
{
    function js($src, $arguments = '', $type = 'text/javascript')
    {        
        $ob = this();

        $link = '<script type="'.$type.'" '; 
        
        if (is_array($src))
        {
            foreach ($src as $k => $v)
            {
                if ($k == 'src' AND strpos($v, '://') === FALSE)
                {
                    $link .= ' src="'.$ob->config->source_url()  . $v.'" ';
                }
                else
                {
                    $link .= "$k=\"$v\" ";
                }
            }

            $link .= "></script>";
        }
        else
        {
            if ( strpos($src, '://') !== FALSE)
            {
                $link .= ' src="'.$src.'" ';
            }
            else
            {
                $link .= ' src="'. $ob->config->source_url() . $src.'" ';
            }

            $link .= $arguments;
            $link .= "></script>";
        }
        
        return $link;
        
    }
}

// ------------------------------------------------------------------------ 

/**
* Load inline script file from
* local folder.
* 
* @param string $filename
* @param array  $data
*/
if( ! function_exists('script') ) 
{
    function script($filename = '', $data = '')
    {
        return _load_script(DIR .$GLOBALS['d']. DS .'scripts'. DS, $filename, $data);
    }
}
// ------------------------------------------------------------------------ 

/**
* Load inline script file from
* application folder.
* 
* @param string $filename
* @param array  $data
*/
if( ! function_exists('app_script') ) 
{
    function app_script($filename = '', $data = '')
    {
        return _load_script(APP .'scripts'. DS, $filename, $data);
    }
}
// ------------------------------------------------------------------------ 

/**
* Load inline script file from
* base folder.
* 
* @param string $filename
* @param array  $data
*/
if( ! function_exists('base_script') ) 
{
    function base_script($filename = '', $data = '')
    {
        return _load_script(BASE .'scripts'. DS, $filename, $data);
    }
}

// ------------------------------------------------------------------------ 

/**
* Generates meta tags from an array of key/values
*
* @access   public
* @param    array
* @return   string
*/
if( ! function_exists('meta') ) 
{
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
            $type       = ( ! isset($meta['type']) OR $meta['type'] == 'name') ? 'name' : 'http-equiv';
            $name       = ( ! isset($meta['name']))     ? ''     : $meta['name'];
            $content    = ( ! isset($meta['content']))    ? ''     : $meta['content'];
            $newline    = ( ! isset($meta['newline']))    ? "\n"    : $meta['newline'];

            $str .= '<meta '.$type.'="'.$name.'" content="'.$content.'" />'.$newline;
        }

        return $str;
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
if( ! function_exists('link_tag') ) 
{
    function link_tag($href = '', $rel = 'stylesheet', $type = 'text/css', $title = '', $media = '', $index_page = FALSE)
    {
        $ob = this();

        $link = '<link '; 

        $_cont = ssc::instance();   // obullo changes ..
        
        // When user use view_set_folder('css'); ..
        // /sources/iphone/css/welcome.css
        $path = '';
        if(isset($_cont->_ent->css_folder{1}))
        {
            $path = $_cont->_ent->css_folder .'/'; 
        }
        
        if (is_array($href))
        {
            foreach ($href as $k => $v)
            {
                if ($k == 'href' AND strpos($v, '://') === FALSE)
                {
                    if ($index_page === TRUE)
                    {
                        $link .= ' href="'.$ob->config->site_url($v).'" ';
                    }
                    else
                    {
                        $link .= ' href="'.$ob->config->source_url() . $path . $v.'" ';
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
                $link .= ' href="'. $ob->config->site_url($href) .'" ';
            }
            else
            {
                $link .= ' href="'. $ob->config->source_url() . $path . $href.'" ';
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
}

/* End of file head_tag.php */
/* Location: ./base/helpers/head_tag.php */
?>