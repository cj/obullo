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
 * @filesource
 * @license
 */
 
// ------------------------------------------------------------------------

/**
 * Obullo Pager Class
 *
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Ersin Guvenc
 * @author        Derived from PEAR pager package.
 * @see           Original package http://pear.php.net/package/Pager
 * @link          
 */
Class pager_CORE implements PHP5_Library
{
    
    public static function instance()
    {
        return new self();
    }
    
    /**
     * Return a pager based on $mode and $options
     *
     * @param array $options Optional parameters for the storage class
     *
     * @return object Storage object
     * @static
     * @access public
     */
    public static function init($options = array())
    {
        $mode = (isset($options['mode']) ? strtolower($options['mode']) : 'jumping');
        $classname = 'Pager_'.$mode;
        $classfile = 'drivers'. DS .'pager'. DS .'Pager_'. $mode. EXT;

        if ( ! class_exists($classname)) 
        {
            include_once $classfile;
        }

        // If the class exists, return a new instance of it.
        if (class_exists($classname)) 
        {
            $pager = new $classname($options);
            return $pager;
        }

        return NULL;
    }

}

// END Pager Class

/* End of file Pager.php */
/* Location: ./base/libraries/php5/Pager.php */