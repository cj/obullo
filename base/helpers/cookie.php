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
 * Obullo Cookie Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Guvenc
 * @link        
 */

// ------------------------------------------------------------------------
/**
* Set cookie
*
* Accepts six parameter, or you can submit an associative
* array in the first parameter containing all the values.
*
* @access   public
* @param    mixed
* @param    string    the value of the cookie
* @param    string    the number of seconds until expiration
* @param    string    the cookie domain.  Usually:  .yourdomain.com
* @param    string    the cookie path
* @param    string    the cookie prefix
* @return   void
*/
if( ! function_exists('set_cookie') ) 
{
    function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '')
    {
        if (is_array($name))
        {        
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'name') as $item)
            {
                if (isset($name[$item]))
                {
                    $$item = $name[$item];
                }
            }
        }

        // Set the config file options
        $OB = this();

        if ($prefix == '' AND $OB->config->item('cookie_prefix') != '')
        {
            $prefix = $OB->config->item('cookie_prefix');
        }
        if ($domain == '' AND $OB->config->item('cookie_domain') != '')
        {
            $domain = $OB->config->item('cookie_domain');
        }
        if ($path   == '/' AND $OB->config->item('cookie_path') != '/')
        {
            $path   = $OB->config->item('cookie_path');
        }
        
        if ( ! is_numeric($expire))
        {
            $expire = time() - 86500;
        }
        else
        {
            if ($expire > 0)
            {
                $expire = time() + $expire;
            }
            else
            {
                $expire = 0;
            }
        }

        setcookie($prefix.$name, $value, $expire, $path, $domain, 0);
    }
}
    
// --------------------------------------------------------------------

/**
* Fetch an item from the COOKIE array
*
* @access   public
* @param    string
* @param    bool
* @return   mixed
*/
if( ! function_exists('get_cookie') ) 
{
    function get_cookie($index = '', $xss_clean = FALSE)
    {
        $OB = this();
        
        $prefix = '';
        
        if ( ! isset($_COOKIE[$index]) && config_item('cookie_prefix') != '')
        {
            $prefix = config_item('cookie_prefix');
        }
        
        return i_cookie($prefix.$index, $xss_clean);
    }
}

// --------------------------------------------------------------------

/**
* Delete a COOKIE
*
* @param    mixed
* @param    string    the cookie domain.  Usually:  .yourdomain.com
* @param    string    the cookie path
* @param    string    the cookie prefix
* @return   void
*/
if( ! function_exists('delete_cookie') ) 
{
    function delete_cookie($name = '', $domain = '', $path = '/', $prefix = '')
    {
        set_cookie($name, '', '', $domain, $path, $prefix);
    }
}

/* End of file cookie.php */
/* Location: ./base/helpers/cookie.php */