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
 * @version     0.3 added set_view_folder function
 * @version     0.3 added return , fail gracefully function for views.
 * @link        
 */
Class OB_Content {
    
    public $view_folder      = '';
    public $app_view_folder  = '';
    public $css_folder       = '';
    
    /**
    * Constructor
    *
    * @access    public
    */
    public function __construct()
    {    
        $this->view_folder     = DS. '';
        $this->app_view_folder = DS. '';
        $this->css_folder      = '/';
        
        log_message('debug', "Content Class Initialized");
    }
    
    /**
    * Create your custom folders and
    * change all your view paths for the
    * supporting multiple interfaces (iphone, wap, xml services
    * etc ..)
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
             
           case 'css':
             $this->css_folder      = '/'. $folder;  
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
        $return = FALSE;
        
        if(isset($this->view_folder{1})) { $return = TRUE; } // if view folder changed don't show errors ..

        return $this->_view(DIR .$GLOBALS['d']. DS .'views'. $this->view_folder .DS, $filename, $data, $string, $return);
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
        $return = FALSE;
        
        if(isset($this->app_folder{1})) { $return = TRUE; }  // if view folder changed don't show errors ..
        
        return $this->_view(APP .'views'. $this->app_view_folder . DS, $filename, $data, $string, $return); 
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
            throw new ContentException('Unable locate the script file: '. $path . $filename . EXT);
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
    * @param    string   $path file path 
    * @param    string   $filename 
    * @param    array    $data template vars
    * @param    boolean  $string 
    * @param    boolean  $return 
    * @version  0.1
    * @version  0.2 added empty $data
    * @version  0.3 added $return param
    * @return   void
    */
    private function _view($path, $filename, $data = '', $string = FALSE, $return = FALSE)
    {   
        if( empty($data) ) $data = array();
        
        if ( ! file_exists($path . $filename . EXT) )
        {
            if($return) return;  // fail gracefully for different interfaces ..
                                 // iphone, wap etc..
            
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
        
        // Set Global views inside to Output Class for caching functionality..
        base_register('Output')->append_output(ob_get_contents());
        
        @ob_end_clean();
        
        return;
        
        throw new LoaderException('Unable to locate the view: ' . $filename . EXT);
    }
    
    
}
// END Content Class

/* End of file Content.php */
/* Location: ./base/libraries/Content.php */

?>