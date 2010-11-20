<?php

Class PagerException extends CommonException {}

/**
 * Two constants used to guess the path- and file-name of the page
 * when the user doesn't set any other value
 */

/**
 * Error codes
 */
define('PAGER_OK',                         0);
define('ERROR_PAGER',                     -1);
define('ERROR_PAGER_INVALID',             -2);
define('ERROR_PAGER_INVALID_PLACEHOLDER', -3);
define('ERROR_PAGER_INVALID_USAGE',       -4);
define('ERROR_PAGER_NOT_IMPLEMENTED',     -5);

class Pager_Common
{
    public $_total_items;             // integer number of items
    public $_per_page     = 10;       // integer number of items per page
    public $_delta        = 10;       // integer number of page links for each window
    public $_currentPage  = 1;        // integer current page number
    public $_totalPages   = 1;        // integer total pages number
    public $_link_class   = '';       // string CSS class for links
    public $_classString  = '';       // string wrapper for CSS class name
    public $_base_url     = '';       // string base_url  // pear pager $_path / PAGER_CURRENT_PATHNAME
    public $_fileName     = '';       // string file name
    public $_fixFileName  = TRUE;     // boolean If false, don't override the fileName option. Use at your own risk.
    public $_append       = TRUE;     // boolean you have to use FALSE with mod_rewrite
    public $_httpMethod   = 'GET';    // string specifies which HTTP method to use
    public $_formID       = '';       // string specifies which HTML form to use
    public $_importQuery  = TRUE;     // boolean whether or not to import submitted data
    public $_query_string = TRUE;     // boolean whether or not to import submitted data
    public $_urlVar       = 'page';   // string name of the querystring var for page
    public $_linkData     = array();  // array data to pass through the link
    public $_extraVars    = array();  // array additional URL vars
    public $_excludeVars  = array();  // array URL vars to ignore
    public $_expanded     = TRUE;     // boolean TRUE => expanded mode (for Pager_Sliding)
    public $_accesskey    = FALSE;    // boolean TRUE => show accesskey attribute on <a> tags
    public $_attributes   = '';       // string extra attributes for the <a> tag
    public $_onclick      = '';       // string onclick 
    
    public $_altFirst     = 'first page';       // string alt text for "first page" (use "%d" placeholder for page number)
    public $_altPrev      = 'previous page';    // string alt text for "previous page"
    public $_altNext      = 'next page';        // string alt text for "next page"
    public $_altLast      = 'last page';        // string alt text for "last page" (use "%d" placeholder for page number)
    public $_altPage      = 'page';             // string alt text for "page" (use optional "%d" placeholder for page number)
    public $_prevImg      = '&lt;&lt; Back';    // string image/text to use as "prev" link
    public $_prevImgEmpty = NULL;               // image/text to use as "prev" link when no prev link is needed  (e.g. on the first page)
                                                // NULL deactivates it
    public $_nextImg      = 'Next &gt;&gt;';    // string image/text to use as "next" link
    public $_nextImgEmpty = NULL;               // image/text to use as "next" link when no next link is needed (e.g. on the last page)
                                                // NULL deactivates it
                                                
    public $_separator    = '';                 // string link separator
    public $_spacesBeforeSeparator = 0;         // integer number of spaces before separator
    public $_spacesAfterSeparator  = 1;         // integer number of spaces after separator
    public $_curPageLinkClassName  = '';        // string CSS class name for current page link
    public $_curPageSpanPre        = '';        // string Text before current page link
    public $_curPageSpanPost       = '';        // string Text after current page link
    public $_firstPagePre          = '[';       // string Text before first page link
    public $_firstPageText         = '';        // string Text to be used for first page link
    public $_firstPagePost         = ']';       // string Text after first page link
    public $_lastPagePre           = '[';       // string Text before last page link
    public $_lastPageText          = '';        // string Text to be used for last page link
    public $_lastPagePost          = ']';       // string Text after last page link
    public $_spacesBefore          = '';        // string Will contain the HTML code for the spaces
    public $_spacesAfter           = '';        // string Will contain the HTML code for the spaces
    
    
    public $_firstLinkTitle        = 'first page';      // string String used as title in <link rel="first"> tag
    public $_nextLinkTitle         = 'next page';       // string String used as title in <link rel="next"> tag
    public $_prevLinkTitle         = 'previous page';   //  string String used as title in <link rel="previous"> tag
    public $_lastLinkTitle         = 'last page';       // string String used as title in <link rel="last"> tag


    public $_showAllText           = '';        // string Text to be used for the 'show all' option in the select box
    public $_itemData              = NULL;      // array data to be paged
    public $_clearIfVoid           = TRUE;      // boolean If TRUE and there's only one page, links aren't shown
    public $_useSessions           = FALSE;     // boolean Use session for storing the number of items per page
    public $_closeSession          = FALSE;     // boolean Close the session when finished reading/writing data
    public $_sessionVar            = 'setPerPage';  // string name of the session var for number of items per page
    
    public $_pearErrorMode         = NULL;
    public $links                  = '';        // string Complete set of links
    public $linkTags               = '';        // string Complete set of link tags
    public $linkTagsRaw            = array();   // array Complete set of raw link tags
    public $range                  = array();   // array Array with a key => value pair representing
                                                // page# => bool value (true if key==currentPageNumber).
                                                // can be used for extreme customization.

    public $_allowed_options = array(           // array list of available options (safety check)
        'total_items',
        'per_page',
        'delta',
        'link_class',
        'base_url',      // 'path',  ( Obullo changes .. )
        'fileName',
        'fixFileName',
        'append',
        'httpMethod',
        'query_string',  // ( Obullo changes .. )
        'formID',
        'importQuery',
        'urlVar',
        'altFirst',
        'altPrev',
        'altNext',
        'altLast',
        'altPage',
        'prevImg',
        'prevImgEmpty',
        'nextImg',
        'nextImgEmpty',
        'expanded',
        'accesskey',
        'attributes',
        'onclick',
        'separator',
        'spacesBeforeSeparator',
        'spacesAfterSeparator',
        'curPageLinkClassName',
        'curPageSpanPre',
        'curPageSpanPost',
        'firstPagePre',
        'firstPageText',
        'firstPagePost',
        'lastPagePre',
        'lastPageText',
        'lastPagePost',
        'firstLinkTitle',
        'nextLinkTitle',
        'prevLinkTitle',
        'lastLinkTitle',
        'showAllText',
        'itemData',
        'clearIfVoid',
        'useSessions',
        'closeSession',
        'sessionVar',
        'pearErrorMode',
        'extraVars',
        'excludeVars',
        'currentPage',
    );

    // ------------------------------------------------------------------------
    
    /**
     * Generate or refresh the links and paged data after a call to setOptions()
     *
     * @return void
     * @access public
     */
    function build()
    {
        $this->_pageData   = array();   //reset
        $this->links       = '';
        $this->linkTags    = '';
        $this->linkTagsRaw = array();

        $this->_generatePageData();
        $this->_setFirstLastText();

        if ($this->_totalPages > (2 * $this->_delta + 1)) 
        {
            $this->links .= $this->_printFirstPage();
        }

        $this->links .= $this->_getBackLink();
        $this->links .= $this->_getPageLinks();
        $this->links .= $this->_getNextLink();

        $this->linkTags .= $this->_getFirstLinkTag();
        $this->linkTags .= $this->_getPrevLinkTag();
        $this->linkTags .= $this->_getNextLinkTag();
        $this->linkTags .= $this->_getLastLinkTag();
        
        $this->linkTagsRaw['first'] = $this->_getFirstLinkTag(true);
        $this->linkTagsRaw['prev']  = $this->_getPrevLinkTag(true);
        $this->linkTagsRaw['next']  = $this->_getNextLinkTag(true);
        $this->linkTagsRaw['last']  = $this->_getLastLinkTag(true);

        if ($this->_totalPages > (2 * $this->_delta + 1)) 
        {
            $this->links .= $this->_printLastPage();
        }
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns an array of current pages data
     *
     * @param integer $pageID Desired page ID (optional)
     *
     * @return array Page data
     * @access public
     */
    function getPageData($pageID = null)
    {
        $pageID = empty($pageID) ? $this->_currentPage : $pageID;

        if ( ! isset($this->_pageData)) 
        {
            $this->_generatePageData();
        }
        
        if ( ! empty($this->_pageData[$pageID])) 
        {
            return $this->_pageData[$pageID];
        }
        
        return array();
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns pageID for given offset
     *
     * @param integer $index Offset to get pageID for
     *
     * @return integer PageID for given offset
     * @access public
     */
    function getPageIdByOffset($index)
    {
        throw new PagerException('function '.__FUNCTION__.' not implemented.');
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns offsets for given pageID. Eg, if you
     * pass it pageID one and your perPage limit is 10
     * it will return (1, 10). PageID of 2 would
     * give you (11, 20).
     *
     * @param integer $pageID PageID to get offsets for
     *
     * @return array  First and last offsets
     * @access public
     */
    function get_offset_by_page($pageID = NULL)
    {
        $pageID = isset($pageID) ? $pageID : $this->_currentPage;
        
        if ( ! isset($this->_pageData)) 
        {
            $this->_generatePageData();
        }

        if (isset($this->_pageData[$pageID]) || is_null($this->_itemData)) 
        {
            return array(
                        max(($this->_per_page * ($pageID - 1)) + 1, 1),
                        min($this->_total_items, $this->_per_page * $pageID)
                   );
        }
        return array(0, 0);
    }
    
    // ------------------------------------------------------------------------

    /**
     * Given a PageId, it returns the limits of the range of pages displayed.
     *
     * @param integer $pageID PageID to get offsets for
     *
     * @return array First and last offsets
     * @access public
     */
    function getPageRangeByPageId($pageID = NULL)
    {
        $msg = 'function "getPageRangeByPageId()" not implemented.';
        return $this->raiseError($msg, ERROR_PAGER_NOT_IMPLEMENTED);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns back/next/first/last and page links,
     * both as ordered and associative array.
     *
     * NB: in original PEAR::Pager this method accepted two parameters,
     * $back_html and $next_html. Now the only parameter accepted is
     * an integer ($pageID), since the html text for prev/next links can
     * be set in the factory. If a second parameter is provided, then
     * the method act as it previously did. This hack was done to mantain
     * backward compatibility only.
     *
     * @param integer $pageID    Optional pageID. If specified, links for that
     *                           page are provided instead of current one.
     *                           [ADDED IN NEW PAGER VERSION]
     * @param string  $next_html HTML to put inside the next link
     *                           [deprecated: use the factory instead]
     *
     * @return array back/next/first/last and page links
     * @access public
     */
    function get_links($pageID = NULL, $next_html = '')
    {
        return $this->raiseError('function "get_links()" not implemented.', ERROR_PAGER_NOT_IMPLEMENTED);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns ID of current page
     *
     * @return integer ID of current page
     * @access public
     */
    function get_current_page()
    {
        return $this->_currentPage;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns next page ID. If current page is last page
     * this function returns FALSE
     *
     * @return mixed Next page ID or false
     * @access public
     */
    function get_next_page()
    {
        return ($this->get_current_page() == $this->num_pages() ? false : $this->get_current_page() + 1);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns previous page ID. If current page is first page
     * this function returns FALSE
     *
     * @return mixed Previous page ID or false
     * @access public
     */
    function get_prev_page()
    {
        return $this->is_first_page() ? false : $this->get_current_page() - 1;
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns number of items
     *
     * @return integer Number of items
     * @access public
     */
    function num_items()
    {
        return $this->_total_items;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns number of pages
     *
     * @return integer Number of pages
     * @access public
     */
    function num_pages()
    {
        return (int)$this->_totalPages;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns whether current page is first page
     *
     * @return bool First page or not
     * @access public
     */
    function is_first_page()
    {
        return ($this->_currentPage < 2);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns whether current page is last page
     *
     * @return bool Last page or not
     * @access public
     */
    function is_last_page()
    {
        return ($this->_currentPage == $this->_totalPages);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns whether last page is complete
     *
     * @return bool Last page complete or not
     * @access public
     */
    function is_last_page_end()
    {
        return !($this->_total_items % $this->_per_page);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Calculates all page data
     *
     * @return void
     * @access private
     */
    function _generatePageData()
    {
        // Been supplied an array of data?
        if (!is_null($this->_itemData)) 
        {
            $this->_total_items = count($this->_itemData);
        }
        
        $this->_totalPages = ceil((float)$this->_total_items / (float)$this->_per_page);
        
        $i = 1;
        if (!empty($this->_itemData)) 
        {
            foreach ($this->_itemData as $key => $value) 
            {
                $this->_pageData[$i][$key] = $value;
                if (count($this->_pageData[$i]) >= $this->_per_page) 
                {
                    $i++;
                }
            }
        } 
        else 
        {
            $this->_pageData = array();
        }

        //prevent URL modification
        $this->_currentPage = min($this->_currentPage, $this->_totalPages);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Renders a link using the appropriate method
     *
     * @param string $altText  Alternative text for this link (title property)
     * @param string $linkText Text contained by this link
     *
     * @return string The link in string form
     * @access private
     */
    function _renderLink($altText, $linkText)
    {
        $OB = Obullo::instance();
        
        if ($this->_httpMethod == 'GET') 
        {
            if ($this->_append) 
            {
                $href = '?' . $this->_http_build_query_wrapper($this->_linkData);

                // Is query string true,  ( Obullo Changes )
                if($OB->config->item('enable_query_strings') === FALSE AND $this->_query_string == FALSE)
                {   
                     $href = $this->_linkData[$this->_urlVar]; 
                }
            } 
            else 
            {
                $href = str_replace('%d', $this->_linkData[$this->_urlVar], $this->_fileName);
            }
            
            $onclick = '';
            if (array_key_exists($this->_urlVar, $this->_linkData)) 
            {
                $onclick = str_replace('%d', $this->_linkData[$this->_urlVar], $this->_onclick);
            }
            
            return sprintf('<a href="%s"%s%s%s%s title="%s">%s</a>',
                           htmlentities($this->_url . $href, ENT_COMPAT, 'UTF-8'),
                           empty($this->_classString) ? '' : ' '.$this->_classString,
                           empty($this->_attributes)  ? '' : ' '.$this->_attributes,
                           empty($this->_accesskey)   ? '' : ' accesskey="'.$this->_linkData[$this->_urlVar].'"',
                           empty($onclick)            ? '' : ' onclick="'.$onclick.'"',
                           $altText,
                           $linkText
            );
        } 
        elseif ($this->_httpMethod == 'POST') 
        {
            $href = $this->_url;
            if ( ! empty($_GET)) 
            {
                $href .= '?' . $this->_http_build_query_wrapper($_GET);
            }
            
            return sprintf("<a href='javascript:void(0)' onclick='%s'%s%s%s title='%s'>%s</a>",
                           $this->_generateFormOnClick($href, $this->_linkData),
                           empty($this->_classString) ? '' : ' '.$this->_classString,
                           empty($this->_attributes)  ? '' : ' '.$this->_attributes,
                           empty($this->_accesskey)   ? '' : ' accesskey=\''.$this->_linkData[$this->_urlVar].'\'',
                           $altText,
                           $linkText
            );
        }
        return '';
    }

    // ------------------------------------------------------------------------
    
    /**
     * Mimics http_build_query() behavior in the way the data
     * in $data will appear when it makes it back to the server.
     *  For example:
     * $arr =  array('array' => array(array('hello', 'world'),
     *                                'things' => array('stuff', 'junk'));
     * http_build_query($arr)
     * and _generateFormOnClick('foo.php', $arr)
     * will yield
     * $_REQUEST['array'][0][0] === 'hello'
     * $_REQUEST['array'][0][1] === 'world'
     * $_REQUEST['array']['things'][0] === 'stuff'
     * $_REQUEST['array']['things'][1] === 'junk'
     *
     * However, instead of  generating a query string, it generates
     * Javascript to create and submit a form.
     *
     * @param string $formAction where the form should be submitted
     * @param array  $data       the associative array of names and values
     *
     * @return string A string of javascript that generates a form and submits it
     * @access private
     */
    function _generateFormOnClick($formAction, $data)
    {
        // Check we have an array to work with
        if (!is_array($data)) {
            trigger_error(
                '_generateForm() Parameter 1 expected to be Array or Object. Incorrect value given.',
                E_USER_WARNING
            );
            return false;
        }

        if (!empty($this->_formID)) {
            $str = 'var form = document.getElementById("'.$this->_formID.'"); var input = ""; ';
        } else {
            $str = 'var form = document.createElement("form"); var input = ""; ';
        }

        // We /shouldn't/ need to escape the URL ...
        $str .= sprintf('form.action = "%s"; ', htmlentities($formAction, ENT_COMPAT, 'UTF-8'));
        $str .= sprintf('form.method = "%s"; ', $this->_httpMethod);
        foreach ($data as $key => $val) {
            $str .= $this->_generateFormOnClickHelper($val, $key);
        }

        if (empty($this->_formID)) {
            $str .= 'document.getElementsByTagName("body")[0].appendChild(form);';
        }

        $str .= 'form.submit(); return false;';
        return $str;
    }

    // ------------------------------------------------------------------------

    /**
     * This is used by _generateFormOnClick().
     * Recursively processes the arrays, objects, and literal values.
     *
     * @param mixed  $data Data that should be rendered
     * @param string $prev The name so far
     *
     * @return string A string of Javascript that creates form inputs
     *                representing the data
     * @access private
     */
    function _generateFormOnClickHelper($data, $prev = '')
    {
        $str = '';
        if (is_array($data) || is_object($data)) 
        {
            // foreach key/visible member
            foreach ((array)$data as $key => $val) 
            {
                // append [$key] to prev
                $tempKey = sprintf('%s[%s]', $prev, $key);
                $str .= $this->_generateFormOnClickHelper($val, $tempKey);
            }
        } 
        else 
        {  // must be a literal value
            // escape newlines and carriage returns
            $search  = array("\n", "\r");
            $replace = array('\n', '\n');
            $escapedData = str_replace($search, $replace, $data);
            // am I forgetting any dangerous whitespace?
            // would a regex be faster?
            // if it's already encoded, don't encode it again
            if (!$this->_isEncoded($escapedData)) 
            {
                $escapedData = urlencode($escapedData);
            }
            
            $escapedData = htmlentities($escapedData, ENT_QUOTES, 'UTF-8');

            $str .= 'input = document.createElement("input"); ';
            $str .= 'input.type = "hidden"; ';
            $str .= sprintf('input.name = "%s"; ', $prev);
            $str .= sprintf('input.value = "%s"; ', $escapedData);
            $str .= 'form.appendChild(input); ';
        }
        return $str;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns true if the string is a regexp pattern
     *
     * @param string $string the pattern to check
     *
     * @return boolean
     * @access private
     */
    function _isRegexp($string) 
    {
        return preg_match('/^\/.*\/([Uims]+)?$/', $string);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns the correct link for the back/pages/next links
     *
     * @return array Data
     * @access private
     */
    function _getLinksData()
    {
        $qs = array();
        if ($this->_importQuery) 
        {
            if ($this->_httpMethod == 'POST') 
            {
                $qs = $_POST;
            } 
            elseif ($this->_httpMethod == 'GET') 
            {
                $qs = $_GET;
            }
        }
        
        foreach ($this->_excludeVars as $exclude) 
        {
            $use_preg = $this->_isRegexp($exclude);
            
            foreach (array_keys($qs) as $qs_item) 
            {
                if ($use_preg) {
                    if (preg_match($exclude, $qs_item, $matches)) 
                    {
                        foreach ($matches as $m) 
                        {
                            unset($qs[$m]);
                        }
                    }
                } 
                elseif ($qs_item == $exclude) 
                {
                    unset($qs[$qs_item]);
                    break;
                }
            }
        }
        
        
        if (count($this->_extraVars)) 
        {
            $this->_recursive_urldecode($this->_extraVars);
            $qs = array_merge($qs, $this->_extraVars);
        }
        
        if (count($qs)
            && function_exists('get_magic_quotes_gpc')
            && -1 == version_compare(PHP_VERSION, '5.2.99')
            && get_magic_quotes_gpc()
        ) 
        {
            $this->_recursive_stripslashes($qs);
        }
        
        return $qs;
    }

    // ------------------------------------------------------------------------

    /**
     * Helper method
     *
     * @param string|array &$var variable to clean
     *
     * @return void
     * @access private
     */
    function _recursive_stripslashes(&$var)
    {
        if (is_array($var)) {
            foreach (array_keys($var) as $k) {
                $this->_recursive_stripslashes($var[$k]);
            }
        } else {
            $var = stripslashes($var);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Helper method
     *
     * @param string|array &$var variable to decode
     *
     * @return void
     * @access private
     */
    function _recursive_urldecode(&$var)
    {
        if (is_array($var)) 
        {
            foreach (array_keys($var) as $k) 
            {
                $this->_recursive_urldecode($var[$k]);
            }
            
        } 
        else 
        {
            $trans_tbl = array_flip(get_html_translation_table(HTML_ENTITIES));
            $var = strtr($var, $trans_tbl);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Returns back link
     *
     * @param string $url  URL to use in the link  [deprecated: use the factory instead]
     * @param string $link HTML to use as the link [deprecated: use the factory instead]
     *
     * @return string The link
     * @access private
     */
    function _getBackLink($url='', $link='')
    {
        //legacy settings... the preferred way to set an option
        //now is passing it to the factory
        if ( ! empty($url)) 
        {
            $this->_base_url = $url;
        }
        if ( ! empty($link)) 
        {
            $this->_prevImg = $link;
        }
        
        $back = '';
        if ($this->_currentPage > 1) 
        {
            $this->_linkData[$this->_urlVar] = $this->get_prev_page();
            
            $back = $this->_renderLink($this->_altPrev, $this->_prevImg)
                  . $this->_spacesBefore . $this->_spacesAfter;
        } 
        else if ($this->_prevImgEmpty !== null && $this->_totalPages > 1) 
        {
            $back = $this->_prevImgEmpty
                  . $this->_spacesBefore . $this->_spacesAfter;
        }
        
        return $back;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns pages link
     *
     * @param string $url URL to use in the link [deprecated: use the factory instead]
     *
     * @return string Links
     * @access private
     */
    function _getPageLinks($url = '')
    {
        $msg = 'function "_getPageLinks()" not implemented.';
        return $this->raiseError($msg, ERROR_PAGER_NOT_IMPLEMENTED);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns next link
     *
     * @param string $url  URL to use in the link  [deprecated: use the factory instead]
     * @param string $link HTML to use as the link [deprecated: use the factory instead]
     *
     * @return string The link
     * @access private
     */
    function _getNextLink($url='', $link='')
    {
        //legacy settings... the preferred way to set an option
        //now is passing it to the factory
        if ( ! empty($url)) 
        {
            $this->_base_url = $url;
        }
        
        if (!empty($link)) 
        {
            $this->_nextImg = $link;
        }
        
        $next = '';
        if ($this->_currentPage < $this->_totalPages) 
        {
            $this->_linkData[$this->_urlVar] = $this->get_next_page();
            $next = $this->_spacesAfter
                  . $this->_renderLink($this->_altNext, $this->_nextImg)
                  . $this->_spacesBefore . $this->_spacesAfter;
        } 
        else if ($this->_nextImgEmpty !== null && $this->_totalPages > 1) 
        {
            $next = $this->_spacesAfter
                  . $this->_nextImgEmpty
                  . $this->_spacesBefore . $this->_spacesAfter;
        }
        
        return $next;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns first link tag
     *
     * @param bool $raw should tag returned as array
     *
     * @return mixed string with html link tag or separated as array
     * @access private
     */
    function _getFirstLinkTag($raw = false)
    {
        if ($this->is_first_page() || ($this->_httpMethod != 'GET')) 
        {
            return $raw ? array() : '';
        }
        
        if ($raw) 
        {
            return array(
                'url'   => $this->_getLinkTagUrl(1),
                'title' => $this->_firstLinkTitle
            );
        }
        
        return sprintf('<link rel="first" href="%s" title="%s" />'."\n",
            $this->_getLinkTagUrl(1),
            $this->_firstLinkTitle
        );
    }

    // ------------------------------------------------------------------------

    /**
     * Returns previous link tag
     *
     * @param bool $raw should tag returned as array
     *
     * @return mixed string with html link tag or separated as array
     * @access private
     */
    function _getPrevLinkTag($raw = false)
    {
        if ($this->is_first_page() || ($this->_httpMethod != 'GET')) 
        {
            return $raw ? array() : '';
        }
        
        if ($raw) 
        {
            return array(
                'url'   => $this->_getLinkTagUrl($this->get_prev_page()),
                'title' => $this->_prevLinkTitle
            );
        }
        
        return sprintf('<link rel="previous" href="%s" title="%s" />'."\n",
            $this->_getLinkTagUrl($this->get_prev_page()),
            $this->_prevLinkTitle
        );
    }

    // ------------------------------------------------------------------------

    /**
     * Returns next link tag
     *
     * @param bool $raw should tag returned as array
     *
     * @return mixed string with html link tag or separated as array
     * @access private
     */
    function _getNextLinkTag($raw = false)
    {
        if ($this->is_last_page() || ($this->_httpMethod != 'GET')) 
        {
            return $raw ? array() : '';
        }
        
        if ($raw) 
        {
            return array(
                'url'   => $this->_getLinkTagUrl($this->get_next_page()),
                'title' => $this->_nextLinkTitle
            );
        }
        
        return sprintf('<link rel="next" href="%s" title="%s" />'."\n",
            $this->_getLinkTagUrl($this->get_next_page()),
            $this->_nextLinkTitle
        );
    }

    // ------------------------------------------------------------------------

    /**
     * Returns last link tag
     *
     * @param bool $raw should tag returned as array
     *
     * @return mixed string with html link tag or separated as array
     * @access private
     */
    function _getLastLinkTag($raw = false)
    {
        if ($this->is_last_page() || ($this->_httpMethod != 'GET')) 
        {
            return $raw ? array() : '';
        }
        if ($raw) 
        {
            return array(
                'url'   => $this->_getLinkTagUrl($this->_totalPages),
                'title' => $this->_lastLinkTitle
            );
        }
        return sprintf('<link rel="last" href="%s" title="%s" />'."\n",
            $this->_getLinkTagUrl($this->_totalPages),
            $this->_lastLinkTitle
        );
    }

    // ------------------------------------------------------------------------

    /**
     * Helper method
     *
     * @param integer $pageID page ID
     *
     * @return string the link tag url
     * @access private
     */
    function _getLinkTagUrl($pageID)
    {
        $this->_linkData[$this->_urlVar] = $pageID;
        
        if ($this->_append) 
        {
            $href = '?' . $this->_http_build_query_wrapper($this->_linkData);
        } 
        else 
        {
            $href = str_replace('%d', $this->_linkData[$this->_urlVar], $this->_fileName);
        }
        
        return htmlentities($this->_url . $href, ENT_COMPAT, 'UTF-8');
    }

    // ------------------------------------------------------------------------

    /**
     * Returns a string with a XHTML SELECT menu,
     * useful for letting the user choose how many items per page should be
     * displayed. If parameter useSessions is TRUE, this value is stored in
     * a session var. The string isn't echoed right now so you can use it
     * with template engines.
     *
     * @param integer $start       starting value for the select menu
     * @param integer $end         ending value for the select menu
     * @param integer $step        step between values in the select menu
     * @param boolean $showAllData If true, perPage is set equal to totalItems.
     * @param array   $extraParams (or string $optionText for BC reasons)
     *                - 'optionText': text to show in each option.
     *                  Use '%d' where you want to see the number of pages selected.
     *                - 'attributes': (html attributes) Tag attributes or
     *                  HTML attributes (id="foo" pairs), will be inserted in the
     *                  <select> tag
     *
     * @return string xhtml select box
     * @access public
     */
    function getPerPageSelectBox($start=5, $end=30, $step=5, $showAllData=false, $extraParams=array())
    {
        include_once 'Pager/HtmlWidgets.php';
        $widget = new Pager_HtmlWidgets($this);
        return $widget->getPerPageSelectBox($start, $end, $step, $showAllData, $extraParams);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns a string with a XHTML SELECT menu with the page numbers,
     * useful as an alternative to the links
     *
     * @param array  $params          - 'optionText': text to show in each option.
     *                                  Use '%d' where you want to see the number
     *                                  of pages selected.
     *                                - 'autoSubmit': if TRUE, add some js code
     *                                  to submit the form on the onChange event
     * @param string $extraAttributes (html attributes) Tag attributes or
     *                                HTML attributes (id="foo" pairs), will be
     *                                inserted in the <select> tag
     *
     * @return string xhtml select box
     * @access public
     */
    function getPageSelectBox($params = array(), $extraAttributes = '')
    {
        include_once 'Pager/HtmlWidgets.php';
        $widget = new Pager_HtmlWidgets($this);
        return $widget->getPageSelectBox($params, $extraAttributes);
    }

    // ------------------------------------------------------------------------

    /**
     * Print [1]
     *
     * @return string String with link to 1st page,
     *                or empty string if this is the 1st page.
     * @access private
     */
    function _printFirstPage()
    {
        if ($this->is_first_page()) {
            return '';
        }
        $this->_linkData[$this->_urlVar] = 1;
        return $this->_renderLink(
                str_replace('%d', 1, $this->_altFirst),
                $this->_firstPagePre . $this->_firstPageText . $this->_firstPagePost
        ) . $this->_spacesBefore . $this->_spacesAfter;
    }

    // ------------------------------------------------------------------------

    /**
     * Print [num_pages()]
     *
     * @return string String with link to last page,
     *                or empty string if this is the 1st page.
     * @access private
     */
    function _printLastPage()
    {
        if ($this->is_last_page()) {
            return '';
        }
        $this->_linkData[$this->_urlVar] = $this->_totalPages;
        return $this->_renderLink(
                str_replace('%d', $this->_totalPages, $this->_altLast),
                $this->_lastPagePre . $this->_lastPageText . $this->_lastPagePost
        );
    }

    // ------------------------------------------------------------------------

    /**
     * sets the private _firstPageText, _lastPageText variables
     * based on whether they were set in the options
     *
     * @return void
     * @access private
     */
    function _setFirstLastText()
    {
        if ($this->_firstPageText == '') {
            $this->_firstPageText = '1';
        }
        if ($this->_lastPageText == '') {
            $this->_lastPageText = $this->_totalPages;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * This is a slightly modified version of the http_build_query() function;
     * it heavily borrows code from PHP_Compat's http_build_query().
     * The main change is the usage of htmlentities instead of urlencode,
     * since it's too aggressive
     *
     * @param array $data array of querystring values
     *
     * @return string
     * @access private
     */
    function _http_build_query_wrapper($data)
    {
        $data = (array)$data;
        if (empty($data)) 
        {
            return '';
        }
        
        $separator = ini_get('arg_separator.output');
        if ($separator == '&amp;') 
        {
            $separator = '&'; //the string is escaped by htmlentities anyway...
        }
        
        $tmp = array ();
        foreach ($data as $key => $val) 
        {
            if (is_scalar($val)) 
            {
                //array_push($tmp, $key.'='.$val);
                $val = urlencode($val);
                array_push($tmp, $key .'='. str_replace('%2F', '/', $val));
                continue;
            }
            // If the value is an array, recursively parse it
            if (is_array($val)) 
            {
                array_push($tmp, $this->__http_build_query($val, urlencode($key)));
                continue;
            }
        }
        return implode($separator, $tmp);
    }

    // ------------------------------------------------------------------------

    /**
     * Helper function
     *
     * @param array  $array array of querystring values
     * @param string $name  key
     *
     * @return string
     * @access private
     */
    function __http_build_query($array, $name)
    {
        $tmp = array ();
        $separator = ini_get('arg_separator.output');
        if ($separator == '&amp;') 
        {
            $separator = '&'; //the string is escaped by htmlentities anyway...
        }
        
        foreach ($array as $key => $value) 
        {
            if (is_array($value)) 
            {
                //array_push($tmp, $this->__http_build_query($value, sprintf('%s[%s]', $name, $key)));
                array_push($tmp, $this->__http_build_query($value, $name.'%5B'.$key.'%5D'));
            } 
            elseif (is_scalar($value)) 
            {
                //array_push($tmp, sprintf('%s[%s]=%s', $name, htmlentities($key), htmlentities($value)));
                array_push($tmp, $name.'%5B'.urlencode($key).'%5D='.urlencode($value));
            } 
            elseif (is_object($value)) 
            {
                //array_push($tmp, $this->__http_build_query(get_object_vars($value), sprintf('%s[%s]', $name, $key)));
                array_push($tmp, $this->__http_build_query(get_object_vars($value), $name.'%5B'.$key.'%5D'));
            }
        }
        return implode($separator, $tmp);
    }

    // ------------------------------------------------------------------------

    /**
     * Helper function
     * Check if a string is an encoded multibyte string
     *
     * @param string $string string to check
     *
     * @return boolean
     * @access private
     */

    function _isEncoded($string)
    {
        $hexchar = '&#[\dA-Fx]{2,};';
        return preg_match("/^(\s|($hexchar))*$/Uims", $string) ? true : false;
    }

    // ------------------------------------------------------------------------

    /**
     * conditionally includes PEAR base class and raise an error
     *
     * @param string  $msg  Error message
     * @param integer $code Error code
     *
     * @return PEAR_Error
     * @access private
     */
    function raiseError($msg, $code)
    {
        include_once 'PEAR.php';
        if (empty($this->_pearErrorMode)) {
            $this->_pearErrorMode = PEAR_ERROR_RETURN;
        }
        return PEAR::raiseError($msg, $code, $this->_pearErrorMode);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Set and sanitize options
     *
     * @param mixed $options An associative array of option names and their values
     *
     * @return integer error code (PAGER_OK on success)
     * @access public
     */
    function setOptions($options)
    {
        foreach ($options as $key => $value) 
        {
            if (in_array($key, $this->_allowed_options) AND ( ! is_null($value))) 
            {
                $this->{'_' . $key} = $value;
            }
        }
        
        // autodetect http method
        if ( ! isset($options['httpMethod']) AND ! isset($_GET[$this->_urlVar]) AND isset($_POST[$this->_urlVar])) 
        {
            $this->_httpMethod = 'POST';
        } 
        else 
        {
            $this->_httpMethod = strtoupper($this->_httpMethod);
        }

        if (substr($this->_base_url, -1, 1) == '/') 
        {
            $this->_fileName = ltrim($this->_fileName, '/');  // strip leading slash
        }

        if ($this->_append) 
        {
            if ($this->_fixFileName) 
            {
                $this->_fileName = ''; // PAGER_CURRENT_FILENAME avoid possible user error;
            }
            
            $this->_url = $this->_base_url . $this->_fileName;
            
        } else 
        {
            $this->_url = $this->_base_url;
            
            if (0 != strncasecmp($this->_fileName, 'javascript', 10)) 
            {
                $this->_url .= $this->_base_url;
            }
            
            if (false === strpos($this->_fileName, '%d')) 
            {
                trigger_error($this->errorMessage(ERROR_PAGER_INVALID_USAGE), E_USER_WARNING);
            }
        }
        
        if (substr($this->_url, 0, 2) == '//') 
        {
            $this->_url = substr($this->_url, 1);
        }
        
        if (false === strpos($this->_altPage, '%d')) 
        {
            //by default, append page number at the end
            $this->_altPage .= ' %d';
        }

        $this->_classString = '';
        if (strlen($this->_link_class)) 
        {
            $this->_classString = 'class="'.$this->_link_class.'"';
        }

        if (strlen($this->_curPageLinkClassName)) 
        {
            $this->_curPageSpanPre  .= '<span class="'.$this->_curPageLinkClassName.'">';
            $this->_curPageSpanPost = '</span>' . $this->_curPageSpanPost;
        }

        $this->_per_page = max($this->_per_page, 1); //avoid possible user errors

        if ($this->_useSessions AND ! isset($_SESSION)) 
        {
            session_start();
        }
        
        if ( ! empty($_REQUEST[$this->_sessionVar])) 
        {
            $this->_per_page = max(1, (int)$_REQUEST[$this->_sessionVar]);
            if ($this->_useSessions) 
            {
                $_SESSION[$this->_sessionVar] = $this->_per_page;
            }
        }

        if ( ! empty($_SESSION[$this->_sessionVar]) AND $this->_useSessions) 
        {
             $this->_per_page = $_SESSION[$this->_sessionVar];
        }

        if ($this->_closeSession) 
        {
            session_write_close();
        }

        $this->_spacesBefore = str_repeat('&nbsp;', $this->_spacesBeforeSeparator);
        $this->_spacesAfter  = str_repeat('&nbsp;', $this->_spacesAfterSeparator);

        if (isset($_REQUEST[$this->_urlVar]) AND empty($options['currentPage'])) 
        {
            $this->_currentPage = (int)$_REQUEST[$this->_urlVar];
        }
        
        $this->_currentPage = max($this->_currentPage, 1);
        $this->_linkData = $this->_getLinksData();

        return PAGER_OK;
    }

    // ------------------------------------------------------------------------

    /**
     * Return the current value of a given option
     *
     * @param string $name option name
     *
     * @return mixed option value
     * @access public
     */
    function getOption($name)
    {
        if (!in_array($name, $this->_allowed_options)) 
        {
            $msg = 'invalid option: '.$name;
            return $this->raiseError($msg, ERROR_PAGER_INVALID);
        }
        
        return $this->{'_' . $name};
    }

    // ------------------------------------------------------------------------

    /**
     * Return an array with all the current pager options
     *
     * @return array list of all the pager options
     * @access public
     */
    function getOptions()
    {
        $options = array();
        foreach ($this->_allowed_options as $option) 
        {
            $options[$option] = $this->{'_' . $option};
        }
        
        return $options;
    }

    // ------------------------------------------------------------------------

    /**
     * Return a textual error message for a PAGER error code
     *
     * @param integer $code error code
     *
     * @return string error message
     * @access public
     */
    function errorMessage($code)
    {
        static $errorMessages;
        if (!isset($errorMessages)) {
            $errorMessages = array(
                ERROR_PAGER                     => 'unknown error',
                ERROR_PAGER_INVALID             => 'invalid',
                ERROR_PAGER_INVALID_PLACEHOLDER => 'invalid format - use "%d" as placeholder.',
                ERROR_PAGER_INVALID_USAGE       => 'if $options[\'append\'] is set to false, '
                                                  .' $options[\'fileName\'] MUST contain the "%d" placeholder.',
                ERROR_PAGER_NOT_IMPLEMENTED     => 'not implemented'
            );
        }

        return (isset($errorMessages[$code]) ?
            $errorMessages[$code] : $errorMessages[ERROR_PAGER]);
    }


}