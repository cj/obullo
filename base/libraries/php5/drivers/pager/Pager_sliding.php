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
 
require_once 'Pager_common.php';

/**
 * Obullo Pager Sliding Driver
 *
 *
 * @package       Obullo
 * @subpackage    Libraries.drivers.Pager_sliding
 * @category      Libraries
 * @author        Ersin Guvenc
 * @author        Derived from PEAR Pager package.
 * @see           Original package http://pear.php.net/package/Pager
 * @link          
 */

Class Pager_sliding extends Pager_common
{

    /**
    * Constructor
    *
    * @param array $options Associative array of option names and their values
    *
    * @access public
    */
    function __construct($options = array())
    {
        //set default Pager_Sliding options
        $this->_delta                   = 2;
        $this->_prev_img                = '&laquo;';
        $this->_next_img                = '&raquo;';
        $this->_separator               = '|';
        $this->_spaces_before_separator = 3;
        $this->_spaces_after_separator  = 3;
        $this->_cur_page_span_pre       = '<b>';
        $this->_cur_page_span_post      = '</b>';
        
        //set custom options
        $res = $this->set_options($options);
        
        if ($res !== TRUE) 
        {
            throw new PagerException('Pager Unknown Error.');
        }
        
        $this->build();
    }

    // ------------------------------------------------------------------------

    /**
    * "Overload" PEAR::Pager method. VOID. Not needed here...
    *
    * @param integer $index Offset to get pageID for
    *
    * @return void
    * @deprecated
    * @access public
    */
    function get_page_by_offset($index) {}

    // ------------------------------------------------------------------------

    /**
    * Given a PageId, it returns the limits of the range of pages displayed.
    * While getOffsetByPageId() returns the offset of the data within the
    * current page, this method returns the offsets of the page numbers interval.
    * E.g., if you have pageId=5 and delta=2, it will return (3, 7).
    * PageID of 9 would give you (4, 8).
    * If the method is called without parameter, pageID is set to currentPage#.
    *
    * @param integer $pageid PageID to get offsets for
    *
    * @return array  First and last offsets
    * @access public
    */
    function get_page_range_by_page($pageid = NULL)
    {
        $pageid = isset($pageid) ? (int)$pageid : $this->_current_page;
        
        if ( ! isset($this->_page_data)) 
        {
            $this->_generate_page_data();
        }
        
        if (isset($this->_page_data[$pageid]) OR is_null($this->_item_data)) 
        {
            if ($this->_expanded) 
            {
                $min_surplus = ($pageid <= $this->_delta) ? ($this->_delta - $pageid + 1) : 0;
                $max_surplus = ($pageid >= ($this->_total_pages - $this->_delta)) ?
                                ($pageid - ($this->_total_pages - $this->_delta)) : 0;
            } 
            else 
            {
                $min_surplus = $max_surplus = 0;
            }
            
            return array(
                max($pageid - $this->_delta - $max_surplus, 1),
                min($pageid + $this->_delta + $min_surplus, $this->_total_pages)
            );
        }
        return array(0, 0);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns back/next/first/last and page links,
    * both as ordered and associative array.
    *
    * @param integer $pageID Optional pageID. If specified, links for that page
    *                        are provided instead of current one.
    * @param string  $dummy  used to comply with parent signature (leave empty)
    *
    * @return array back/pages/next/first/last/all links
    * @access public
    */
    function get_links($pageID = NULL, $dummy = '')
    {
        if ( ! is_null($pageID)) 
        {
            $_sav = $this->_current_page;
            $this->_current_page = $pageID;

            $this->links = '';
            if ($this->_total_pages > (2 * $this->_delta + 1)) 
            {
                $this->links .= $this->_print_first_page();
            }
            
            $this->links .= $this->_get_back_link();
            $this->links .= $this->_get_page_links();
            $this->links .= $this->_get_next_link();
            
            if ($this->_total_pages > (2 * $this->_delta + 1)) 
            {
                $this->links .= $this->_print_last_page();
            }
        }

        $back        = str_replace('&nbsp;', '', $this->_get_back_link());
        $next        = str_replace('&nbsp;', '', $this->_get_next_link());
        $pages       = $this->_get_page_links();
        $first       = $this->_print_first_page();
        $last        = $this->_print_last_page();
        $all         = $this->links;
        $link_tags   = $this->link_tags;
        $link_tags_raw = $this->link_tags_raw;

        if (!is_null($pageID)) 
        {
            $this->_current_page = $_sav;
        }

        return array(
            $back,
            $pages,
            trim($next),
            $first,
            $last,
            $all,
            $link_tags,
            'back'        => $back,
            'pages'       => $pages,
            'next'        => $next,
            'first'       => $first,
            'last'        => $last,
            'all'         => $all,
            'link_tags'   => $link_tags,
            'link_tags_raw' => $link_tags_raw,
        );
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns pages link
    *
    * @param string $url URL string [deprecated]
    *
    * @return string Links
    * @access private
    */
    function _get_page_links($url = '')
    {
        //legacy setting... the preferred way to set an option now
        //is adding it to the constuctor
        if (!empty($url)) 
        {
            $this->_base_url = $url;
        }
        
        //If there's only one page, don't display links
        if ($this->_clear_if_void AND ($this->_total_pages < 2)) 
        {
            return '';
        }

        $links = '';
        if ($this->_total_pages > (2 * $this->_delta + 1)) 
        {
            if ($this->_expanded) 
            {
                if (($this->_total_pages - $this->_delta) <= $this->_current_page) 
                {
                    $expansion_before = $this->_current_page - ($this->_total_pages - $this->_delta);
                } 
                else 
                {
                    $expansion_before = 0;
                }
                
                for ($i = $this->_current_page - $this->_delta - $expansion_before; $expansion_before; $expansion_before--, $i++) 
                {
                    $print_separator_flag = ($i != $this->_current_page + $this->_delta); // && ($i != $this->_total_pages - 1)
                    
                    $this->range[$i] = FALSE;
                    
                    $this->_link_data[$this->_url_var] = $i;
                    $links .= $this->_render_link(str_replace('%d', $i, $this->_alt_page), $i)
                           . $this->_spaces_before
                           . ($print_separator_flag ? $this->_separator.$this->_spaces_after : '');
                }
            }

            $expansion_after = 0;
            for ($i = $this->_current_page - $this->_delta; ($i <= $this->_current_page + $this->_delta) && ($i <= $this->_total_pages); $i++) 
            {
                if ($i < 1) 
                {
                    ++$expansion_after;
                    continue;
                }

                // check when to print separator
                $print_separator_flag = (($i != $this->_current_page + $this->_delta) && ($i != $this->_total_pages));

                if ($i == $this->_current_page) 
                {
                    $this->range[$i] = true;
                    $links .= $this->_cur_page_span_pre . $i . $this->_cur_page_span_post;
                } 
                else 
                {
                    $this->range[$i] = false;
                    $this->_link_data[$this->_url_var] = $i;
                    $links .= $this->_render_link(str_replace('%d', $i, $this->_alt_page), $i);
                }
                
                $links .= $this->_spaces_before
                        . ($print_separator_flag ? $this->_separator.$this->_spaces_after : '');
            }

            if ($this->_expanded AND $expansion_after) 
            {
                $links .= $this->_separator . $this->_spaces_after;
                for ($i = $this->_current_page + $this->_delta +1; $expansion_after; $expansion_after--, $i++) 
                {
                    $print_separator_flag = ($expansion_after != 1);
                    $this->range[$i] = FALSE;
                    
                    $this->_link_data[$this->_url_var] = $i;
                    $links .= $this->_render_link(str_replace('%d', $i, $this->_alt_page), $i)
                      . $this->_spaces_before
                      . ($print_separator_flag ? $this->_separator.$this->_spaces_after : '');
                }
            }

        } 
        else 
        {
            // if $this->_total_pages <= (2*Delta+1) show them all
            for ($i=1; $i<=$this->_total_pages; $i++) 
            {
                if ($i != $this->_current_page) 
                {
                    $this->range[$i] = FALSE;
                    
                    $this->_link_data[$this->_url_var] = $i;
                    $links .= $this->_render_link(str_replace('%d', $i, $this->_alt_page), $i);
                } 
                else 
                {
                    $this->range[$i] = true;
                    $links .= $this->_cur_page_span_pre . $i . $this->_cur_page_span_post;
                }
                
                $links .= $this->_spaces_before
                       . (($i != $this->_total_pages) ? $this->_separator.$this->_spaces_after : '');
            }
        }
        return $links;
    }

}

// END Pager_sliding Class

/* End of file Pager_sliding.php */
/* Location: ./base/libraries/php5/drivers/pager/Pager_sliding.php */