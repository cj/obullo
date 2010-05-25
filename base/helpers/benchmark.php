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
 * Obullo Benchmark Helper
 *
 * This helper enables you to mark points and calculate the time difference
 * between them.  Memory consumption can also be displayed.
 *
 * @package       Obullo
 * @subpackage    Helpers
 * @category      Test
 * @author        Ersin Guvenc
 * @link          
 */
 
$_bench = ssc::instance();
$_bench->_mark = new stdClass();
$_bench->_mark->marker = array();

log_message('debug', "Benchmark Helper Initialized");

// --------------------------------------------------------------------

/**
 * Set a benchmark marker
 *
 * Multiple calls to this function can be made so that several
 * execution points can be timed
 *
 * @access    public
 * @param     string    $name    name of the marker
 * @return    void
 */
function benchmark_mark($name)
{
    global $_bench;
    
    $_bench->_mark->marker[$name] = microtime();
}

// -------------------------------------------------------------------- 

/**
* Calculates the time difference between two marked points.
*
* @access   public
* @param    string    a particular marked point
* @param    string    a particular marked point
* @param    integer   the number of decimal places
* @return   mixed
*/
function benchmark_elapsed_time($point1 = '', $point2 = '', $decimals = 4)
{
    global $_bench;
    
    if ($point1 == '')
    {
        return '{elapsed_time}';
    }

    if ( ! isset($_bench->_mark->marker[$point1]))
    {
        return '';
    }

    if ( ! isset($_bench->_mark->marker[$point2]))
    {
        $_bench->_mark->marker[$point2] = microtime();
    }

    list($sm, $ss) = explode(' ', $_bench->_mark->marker[$point1]);
    list($em, $es) = explode(' ', $_bench->_mark->marker[$point2]);

    return number_format(($em + $es) - ($sm + $ss), $decimals);
}

// -------------------------------------------------------------------- 

/**
* Memory Usage
*
* @access    public
* @return    string
*/
function benchmark_memory_usage()
{
    return '{memory_usage}';
}

// END OB_Benchmark class

/* End of file Benchmark.php */
/* Location: ./base/libraries/Benchmark.php */
?>