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
        if( ! is_array($filename))
        $filename = array($filename);
        
        $_cont = ssc::instance();
        
        // When user use content_set_folder('css');
        // this will not effect other packages
        // because of each package should use different Global Controller file.
        $path = '';
        if(isset($_cont->_ent->css_folder{1}))
        {
            $path = $_cont->_ent->css_folder.'/'; 
        }

        $url = ob::instance()->config->slash_item('source_url'). $path;
        
        $style = '';
        foreach($filename as $key => $css)
        {    
            $style .= link_tag($url . $css, 'stylesheet', 'text/css', $title, $media)."\n";
        }
        
        return $style;   
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
* @version  0.2 removed /js dir added path support 
* 
*/
if( ! function_exists('js') ) 
{
    function js($filename, $arguments = '', $type = 'text/javascript')
    {
        if( ! is_array($filename))
        $filename = array($filename);
        
        $url = ob::instance()->config->slash_item('source_url');

        $js = '';
        foreach($filename as $key => $file)
        {
            $js.= "\n".'<script type="'.$type.'" src="'.$url . $file.'" '.$arguments.'></script>';  
        }
        
        return $js;
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
}

/* End of file head_tag.php */
/* Location: ./base/helpers/head_tag.php */
?>