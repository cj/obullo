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
 * @license         public 
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Directory Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Ersin Güvenç
 * @link        
 */

// ------------------------------------------------------------------------

/**
 * Create a Directory Map
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	bool	whether to limit the result to the top level only
 * @return	array
 */	
function directory_map($source_dir, $top_level_only = FALSE, $hidden = FALSE)
{	
	if ($fp = @opendir($source_dir))
	{
		$source_dir = rtrim($source_dir, DS). DS;		
		$filedata = array();
		
		while (FALSE !== ($file = readdir($fp)))
		{
			if (($hidden == FALSE && strncmp($file, '.', 1) == 0) OR ($file == '.' OR $file == '..'))
			{
				continue;
			}
			
			if ($top_level_only == FALSE && @is_dir($source_dir.$file))
			{
				$temp_array = array();
			
				$temp_array = directory_map($source_dir.$file. DS, $top_level_only, $hidden);
			
				$filedata[$file] = $temp_array;
			}
			else
			{
				$filedata[] = $file;
			}
		}
		
		closedir($fp);
		return $filedata;
	}
	else
	{
		return FALSE;
	}
}

/* End of file directory.php */
/* Location: ./base/helpers/directory.php */
?>