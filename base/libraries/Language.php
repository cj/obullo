<?php
if( !defined('BASE') ) exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 MVC-min Framework software for PHP 5.2.4 or newer
 * Derived from Code Igniter 
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
 * Derived from CodeIgniter
 *
 * @package     Obullo
 * @subpackage  Libraries
 * @category    Language
 * @author      ExpressionEngine Dev Team
 * @author      Ersin Güvenç
 * @link        
 */
class OB_Language
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
		//log_message('debug', "Language Class Initialized");
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
	public function load($langfile = '', $idiom = '', $return = FALSE)
	{
		$langfile = str_replace(EXT, '', str_replace('_lang.', '', $langfile)).'_lang'.EXT;

		if (in_array($langfile, $this->is_loaded, TRUE))
		return;
		
		if ($idiom == '')
		{
			$OB = ob::instance();
			$deft_lang = $OB->config->item('language');
			$idiom = ($deft_lang == '') ? 'english' : $deft_lang;
		}

		// Determine where the language file is and load it
		if (file_exists(APP.'language/'.$idiom.'/'.$langfile))
		{
			include(APP.'language/'.$idiom.'/'.$langfile);
		}
		else
		{
			if (file_exists(BASE.'language/'.$idiom.'/'.$langfile))
			{
				include(BASE.'language/'.$idiom.'/'.$langfile);
			}
			else
			{
				throw new LangException('Unable to load the requested language file: language/'.$idiom.'/'.$langfile);
			}
		}

		if ( ! isset($lang))
		{
			//log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);
			return;
		}

		if ($return == TRUE)
		return $lang;

		$this->is_loaded[] = $langfile;
		$this->language = array_merge($this->language, $lang);
		unset($lang);

		//log_message('debug', 'Language file loaded: language/'.$idiom.'/'.$langfile);
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

/* End of file Language.php */
/* Location: ./base/libraries/Language.php */