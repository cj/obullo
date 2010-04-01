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
 
Class ContentException extends CommonException {}  
 
/**
 * Obullo Content Builder Class
 *
 * @package     Obullo
 * @subpackage  Libraries
 * @category    Language
 * @author      Ersin Güvenç
 * @link        
 */
class OB_Content
{

    public $_ob_level;
    
    /**
    * Constructor
    *
    * @access    public
    */
    public function __construct()
    {    
        $this->_ob_level  = ob_get_level();
        
        log_message('debug', "Content Class Initialized");
    }
        
    public function script($filename, $data = array())
    {   
        return $this->_script(DIR .$GLOBALS['d']. DS .'scripts'. DS, $filename, $data);
    }
    
    // ------------------------------------------------------------------------
    
    public function app_script($filename, $data = array())
    {   
        return $this->_script(APP .'scripts'. DS, $filename, $data);
    }
    
    // ------------------------------------------------------------------------
    
    public function base_script($filename, $data = array())
    {   
        return $this->_script(BASE .'scripts'. DS, $filename, $data);
    }
    
    // ------------------------------------------------------------------------
    
    public function view($filename, $data = array(), $string = TRUE)
    {               
        return $this->_view(DIR .$GLOBALS['d']. DS .'views'. DS, $filename, $data, $string);
    }
    
    // ------------------------------------------------------------------------
    
    public function app_view($filename, $data = array(), $string = FALSE)
    {
        return $this->_view(APP .'views'. DS, $filename, $data, $string); 
    }

    // ------------------------------------------------------------------------
    
    /**
    * Load Java script files externally
    * like fetch view files as string
    * 
    * @author   Ersin Güvenç
    * @param    string $path
    * @param    string $filename
    * @param    array  $data
    */
    private function _script($path, $filename, $data = array())
    {
        if ( ! file_exists($path . $filename . EXT) )
        {
            throw new LoaderException('Unable locate the script file: '. $filename . EXT);
        } 
        
        if(sizeof($data) > 0) { extract($data, EXTR_SKIP); }
        
        ob_start();
        
        include($path . $filename . EXT);
        $content = ob_get_contents();
        
        ob_end_clean();

        return "\n".$content; 
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Main view function
    * 
    * @author   Ersin Güvenç          
    * @param    string $path file path 
    * @param    string $filename 
    * @param    array $data template vars
    * @param    boolen $string 
    * @version  0.1
    * @return   void
    */
    private function _view($path, $filename, $data = array(), $string = FALSE)
    {   
        if(sizeof($data) > 0) { extract($data, EXTR_SKIP); }
        
        ob_start();
        
        include($path . $filename . EXT);
        
        log_message('debug', 'Content file loaded: '.$path . $filename . EXT);
   
        if($string === TRUE)
        {
            $content = ob_get_contents();
            @ob_end_clean();
            return $content;
        }
        
        if (ob_get_level() > $this->_ob_level + 1)
        {
            ob_end_flush();
            return; // it prevents exceptional error
        }
        else
        {
            // Set Global views inside to Output Class for caching functionality..
            base_register('Output')->append_output(ob_get_contents());
            
            @ob_end_clean();
            return;
        }
    
        throw new LoaderException('Unable to locate the view: ' . $filename . EXT);
    }
    
    
}
// END Content Class

/* End of file Content.php */
/* Location: ./base/libraries/Content.php */

?>