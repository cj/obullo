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
 * @version     0.1
 * @version     0.2 added empty $data string support
 * @link        
 */
class OB_Content
{

    public $_ob_level;
    
    public $view_folder      = '';
    public $app_view_folder  = '';
    
    /**
    * Constructor
    *
    * @access    public
    */
    public function __construct()
    {    
        $this->_ob_level  = ob_get_level();
        
        $this->view_folder     = DS. '';
        $this->app_view_folder = DS. '';
        
        log_message('debug', "Content Class Initialized");
    }
    
    /**
    * Add your custom folder
    * change all your view paths for
    * flexibility.
    * 
    * @author  Ersin Güvenç
    * @param   string $func view function
    * @param   string $folder view folder (no trailing slash)
    */
    public function set_view_folder($func = 'view', $folder = '')
    {
        switch ($func)
        {
           case 'view':
             $this->view_folder     = DS. $folder;
             break;
             
           case 'app_view':
             $this->app_view_folder = DS. $folder;  
             break;
        }
    }
    
    /**
    * Load inline script file from
    * local folder.
    * 
    * @param string $filename
    * @param array  $data
    */
    public function script($filename, $data = '')
    {   
        return $this->_script(DIR .$GLOBALS['d']. DS .'scripts'. DS, $filename, $data);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Load inline script file from
    * application folder.
    * 
    * @param string $filename
    * @param array  $data
    */
    public function app_script($filename, $data = '')
    {   
        return $this->_script(APP .'scripts'. DS, $filename, $data);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Load inline script file from
    * base folder.
    * 
    * @param string $filename
    * @param array  $data
    */
    public function base_script($filename, $data = '')
    {   
        return $this->_script(BASE .'scripts'. DS, $filename, $data);
    }
    
    // ------------------------------------------------------------------------
    /**
    * put your comment there...
    * 
    * @param mixed $filename
    * @param mixed $data
    * @param mixed $string
    * @return void
    */
    public function view($filename, $data = '', $string = TRUE)
    {               
        return $this->_view(DIR .$GLOBALS['d']. DS .'views'. $this->view_folder .DS, $filename, $data, $string);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Global view
    * 
    * @param mixed $filename
    * @param mixed $data
    * @param mixed $string
    * @return void
    */
    public function app_view($filename, $data = '', $string = FALSE)
    {
        return $this->_view(APP .'views'. $this->app_view_folder . DS, $filename, $data, $string); 
    }

    // ------------------------------------------------------------------------
    
    /**
    * Load Java script files externally
    * like fetch view files as string
    * 
    * @author   Ersin Güvenç
    * @param    string $path
    * @param    string $filename
    * @version  0.1
    * @version  0.2 added empty $data
    * @param    array  $data
    */
    private function _script($path, $filename, $data = '')
    {
        if( empty($data) ) $data = array();
        
        if ( ! file_exists($path . $filename . EXT) )
        {
            throw new ContentException('Unable locate the script file: '. $filename . EXT);
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
    * @version  0.2 added empty $data
    * @return   void
    */
    private function _view($path, $filename, $data = '', $string = FALSE)
    {   
        if( empty($data) ) $data = array();
        
        if ( ! file_exists($path . $filename . EXT) )
        {
            throw new ContentException('Unable locate the view file: '. $filename . EXT);
        } 
        
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