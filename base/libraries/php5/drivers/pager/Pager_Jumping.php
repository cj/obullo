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
 
require_once 'Pager_Common.php';

/**
 * Obullo Pager Jumping Driver
 *
 *
 * @package       Obullo
 * @subpackage    Libraries.drivers.pager_jumping
 * @category      Libraries
 * @author        Ersin Guvenc
 * @author        Derived from PEAR Pager package.
 * @link          
 */

class Pager_Jumping extends Pager_Common
{
    /**
    * Constructor
    *
    * @param array $options Associative array of option 
    *                       names and their values
    * @access public
    */
    function __construct($options = array())
    {
        $res = $this->setOptions($options);
        
        if ($res !== TRUE) 
        {
            throw new PagerException('Pager Unknown Error.');
        }
    
        $this->build();
    }
    
    // ------------------------------------------------------------------------

    /**
    * Returns pageID for given offset
    *
    * @param integer $index Offset to get pageID for
    *
    * @return int PageID for given offset
    */
    function get_page_by_offset($index)
    {
        if (!isset($this->_pageData)) 
        {
            $this->_generatePageData();
        }

        if (($index % $this->_per_page) > 0) 
        {
            $pageID = ceil((float)$index / (float)$this->_per_page);
        } 
        else 
        {
            $pageID = $index / $this->_per_page;
        }
        
        return $pageID;
    }

    // ------------------------------------------------------------------------
    
    /**
     * Given a PageId, it returns the limits of the range of pages displayed.
     * While getOffsetByPageId() returns the offset of the data within the
     * current page, this method returns the offsets of the page numbers interval.
     * E.g., if you have pageId=3 and delta=10, it will return (1, 10).
     * PageID of 8 would give you (1, 10) as well, because 1 <= 8 <= 10.
     * PageID of 11 would give you (11, 20).
     * If the method is called without parameter, pageID is set to currentPage#.
     *
     * @param integer $pageid PageID to get offsets for
     *
     * @return array  First and last offsets
     * @access public
     */
    function get_page_range_by_page($pageid = null)
    {
        $pageid = isset($pageid) ? (int)$pageid : $this->_currentPage;
        
        if (isset($this->_pageData[$pageid]) || is_null($this->_itemData)) 
        {
            // I'm sure I'm missing something here, but this formula works
            // so I'm using it until I find something simpler.
            $start = ((($pageid + (($this->_delta - ($pageid % $this->_delta))) % $this->_delta) / $this->_delta) - 1) * $this->_delta +1;
            
            return array(
                max($start, 1),
                min($start+$this->_delta-1, $this->_totalPages)
            );
            
        } 
        else 
        {
            return array(0, 0);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Returns back/next/first/last and page links,
     * both as ordered and associative array.
     *
     * NB: in original PEAR::Pager this method accepted two parameters,
     * $back_html and $next_html. Now the only parameter accepted is
     * an integer ($pageID), since the html text for prev/next links can
     * be set in the constructor. If a second parameter is provided, then
     * the method act as it previously did. This hack's only purpose is to
     * mantain backward compatibility.
     *
     * @param integer $pageID    Optional pageID. If specified, links for that 
     *                           page are provided instead of current one.
     *                           [ADDED IN NEW PAGER VERSION]
     * @param string  $next_html HTML to put inside the next link
     *                           [deprecated: use the factory instead]
     *
     * @return array Back/pages/next links
     */
    function get_links($pageID = NULL, $next_html = '')
    {
        //BC hack
        if ( ! empty($next_html)) 
        {
            $back_html = $pageID;
            $pageID    = NULL;
        } 
        else 
        {
            $back_html = '';
        }

        if (!is_null($pageID)) 
        {
            $this->links = '';
            if ($this->_totalPages > $this->_delta) 
            {
                $this->links .= $this->_printFirstPage();
            }

            $_sav = $this->_currentPage;
            $this->_currentPage = $pageID;

            $this->links .= $this->_getBackLink('', $back_html);
            $this->links .= $this->_getPageLinks();
            $this->links .= $this->_getNextLink('', $next_html);
            
            if ($this->_totalPages > $this->_delta) 
            {
                $this->links .= $this->_printLastPage();
            }
        }

        $back        = str_replace('&nbsp;', '', $this->_getBackLink());
        $next        = str_replace('&nbsp;', '', $this->_getNextLink());
        $pages       = $this->_getPageLinks();
        $first       = $this->_printFirstPage();
        $last        = $this->_printLastPage();
        $all         = $this->links;
        $linkTags    = $this->linkTags;
        $linkTagsRaw = $this->linkTagsRaw;

        if ( ! is_null($pageID)) 
        {
            $this->_currentPage = $_sav;
        }

        return array(
            $back,
            $pages,
            trim($next),
            $first,
            $last,
            $all,
            $linkTags,
            'back'        => $back,
            'pages'       => $pages,
            'next'        => $next,
            'first'       => $first,
            'last'        => $last,
            'all'         => $all,
            'linktags'    => $linkTags,
            'linkTagsRaw' => $linkTagsRaw,
        );
    }

    // ------------------------------------------------------------------------

    /**
    * Returns pages link
    *
    * @param string $url URL to use in the link
    *                    [deprecated: use the constructor instead]
    *
    * @return string Links
    * @access private
    */
    function _getPageLinks($url = '')
    {
        //legacy setting... the preferred way to set an option now
        //is adding it to the constuctor
        if ( ! empty($url)) 
        {
            $this->_base_url = $url;
        }

        //If there's only one page, don't display links
        if ($this->_clearIfVoid AND ($this->_totalPages < 2)) 
        {
            return '';
        }

        $links = '';
        $limits = $this->get_page_range_by_page($this->_currentPage);

        for ($i=$limits[0]; $i<=min($limits[1], $this->_totalPages); $i++) 
        {
            if ($i != $this->_currentPage) 
            {
                $this->range[$i] = false;
                $this->_linkData[$this->_urlVar] = $i;
                $links .= $this->_renderLink(str_replace('%d', $i, $this->_altPage), $i);
            } 
            else 
            {
                $this->range[$i] = true;
                $links .= $this->_curPageSpanPre . $i . $this->_curPageSpanPost;
            }
            
            $links .= $this->_spacesBefore
                   . (($i != $this->_totalPages) ? $this->_separator.$this->_spacesAfter : '');
        }
        return $links;
    }


}