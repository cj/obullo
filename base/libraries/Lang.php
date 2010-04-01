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
 
Class LangException extends CommonException {}  
 
/**
 * Obullo Language Class
 *
 * @package     Obullo
 * @subpackage  Libraries
 * @category    Language
 * @author      Ersin Güvenç
 * @link        
 */
class OB_Lang
{
	public $language	= array();
	public $is_loaded	= array();

    /**
    * Constructor
    *
    * @access	public
    */
	public function __construct()
	{
		log_message('debug', "Language Class Initialized");
	}

	// --------------------------------------------------------------------

    /**
    * Load a language file
    *
    * @access	public
    * @param	mixed	the name of the language file to be loaded. Can be an array
    * @param	string	the language (english, etc.)
    * @return	mixed
    */
    public function load($langfile = '', $idiom = '', $dir = 'base', $return = FALSE)
    {
        if (in_array($langfile, $this->is_loaded, TRUE))
        return;
        
        if ($idiom == '')
        {
            $OB = ob::instance();
            $deft_lang = $OB->config->item('language');
            $idiom = ($deft_lang == '') ? 'english' : $deft_lang;
        }
        
        switch ($dir)
        {
            case 'local':
             $folder = DIR.$GLOBALS['d'].DS.'lang'.DS.$idiom.DS;                            
             break;
            
            case 'global':
             $folder = APP.'lang'.DS.$idiom.DS;
             break;
             
            case 'base':
             $folder = BASE.'lang'.DS.$idiom.DS;  
             break;
        }

        if( ! is_dir($folder))
        return;
        
        $lang = get_static($langfile, 'lang', $folder);
        
        if ( ! isset($lang))
        {
            log_message('error', 'Language file contains no data: lang/'.$idiom.'/'.$langfile.EXT);
            return;
        }

        if ($return)
        return $lang;

        $this->is_loaded[] = $langfile;
        $this->language = array_merge($this->language, $lang);
        unset($lang);

        log_message('debug', 'Language file loaded: lang/'.$idiom.'/'.$langfile.EXT);
        return TRUE;
    }

	// --------------------------------------------------------------------

    /**
    * Fetch a single line of text from the language array
    *
    * @access	public
    * @param	string	$line 	the language line
    * @return	string
    */
	public function line($line = '')
	{
		$line = ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];
		return $line;
	}

}
// END Language Class

/* End of file Lang.php */
/* Location: ./base/libraries/Lang.php */
