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
 * Obullo Benchmark Class
 *
 * This class enables you to mark points and calculate the time difference
 * between them.  Memory consumption can also be displayed.
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Ersin Guvenc
 * @link          
 */
Class OB_Benchmark {

    public $marker = array();

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
    public function mark($name)
    {
        $this->marker[$name] = microtime();
    }

    // --------------------------------------------------------------------

    /**
     * Calculates the time difference between two marked points.
     *
     * @access    public
     * @param    string    a particular marked point
     * @param    string    a particular marked point
     * @param    integer    the number of decimal places
     * @return    mixed
     */
    public function elapsed_time($point1 = '', $point2 = '', $decimals = 4)
    {
        if ($point1 == '')
        {
            return '{elapsed_time}';
        }

        if ( ! isset($this->marker[$point1]))
        {
            return '';
        }

        if ( ! isset($this->marker[$point2]))
        {
            $this->marker[$point2] = microtime();
        }
    
        list($sm, $ss) = explode(' ', $this->marker[$point1]);
        list($em, $es) = explode(' ', $this->marker[$point2]);

        return number_format(($em + $es) - ($sm + $ss), $decimals);
    }
     
    // --------------------------------------------------------------------

    /**
     * Memory Usage
     *
     * @access    public
     * @return    string
     */
    public function memory_usage()
    {
        return '{memory_usage}';
    }

}

// END OB_Benchmark class

/* End of file Benchmark.php */
/* Location: ./base/libraries/Benchmark.php */
?>