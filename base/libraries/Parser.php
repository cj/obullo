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
 * @filesource
 * @license
 */

Class ParserException extends CommonException {}  

// ------------------------------------------------------------------------

/**
 * Parser Class
 * 
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Ersin Güvenç
 * @link          
 */
Class OB_Parser {

    public $l_delim = '{';
    public $r_delim = '}';
    public $object;
        
    /**
     *  Parse a template
     *
     * Parses pseudo-variables contained in the specified template,
     * replacing them with the data in the second param
     *
     * @access   public
     * @param    string
     * @param    array
     * @param    bool
     * @return   string
     */
    public function parse($template, $data, $return = FALSE)
    {
        $OB = ob::instance();
        
        base_register('Content');
        
        $template = $this->content->view($template, $data);
        
        if ($template == '')
        {
            return FALSE;
        }
        
        foreach ($data as $key => $val)
        {
            if (is_array($val))
            {
                $template = $this->_parse_pair($key, $val, $template);        
            }
            else
            {
                $template = $this->_parse_single($key, (string)$val, $template);
            }
        }
        
        if ($return == FALSE)
        {
            $OB->output->append_output($template);
        }
        
        return $template;
    }
    
    // --------------------------------------------------------------------
    
    /**
     *  Set the left/right variable delimiters
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   void
     */
    public function set_delimiters($l = '{', $r = '}')
    {
        $this->l_delim = $l;
        $this->r_delim = $r;
    }
    
    // --------------------------------------------------------------------
    
    /**
     *  Parse a single key/value
     *
     * @access   private
     * @param    string
     * @param    string
     * @param    string
     * @return   string
     */
    private function _parse_single($key, $val, $string)
    {
        return str_replace($this->l_delim.$key.$this->r_delim, $val, $string);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Parse a tag pair
     *
     * Parses tag pairs:  {some_tag} string... {/some_tag}
     *
     * @access   private
     * @param    string
     * @param    array
     * @param    string
     * @return   string
     */
    private function _parse_pair($variable, $data, $string)
    {    
        if (FALSE === ($match = $this->_match_pair($string, $variable)))
        {
            return $string;
        }

        $str = '';
        foreach ($data as $row)
        {
            $temp = $match['1'];
            foreach ($row as $key => $val)
            {
                if ( ! is_array($val))
                {
                    $temp = $this->_parse_single($key, $val, $temp);
                }
                else
                {
                    $temp = $this->_parse_pair($key, $val, $temp);
                }
            }
            
            $str .= $temp;
        }
        
        return str_replace($match['0'], $str, $string);
    }
    
    // --------------------------------------------------------------------
    
    /**
     *  Matches a variable pair
     *
     * @access   private
     * @param    string
     * @param    string
     * @return   mixed
     */
    private function _match_pair($string, $variable)
    {
        if ( ! preg_match("|".$this->l_delim . $variable . $this->r_delim."(.+?)".$this->l_delim . '/' . $variable . $this->r_delim."|s", $string, $match))
        {
            return FALSE;
        }
        
        return $match;
    }

}
// END Parser Class

/* End of file Parser.php */
/* Location: ./base/libraries/Parser.php */
?>