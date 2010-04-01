<?php

/**
* Your copy paste Model.
*/
Class Model_doly extends Model
{
    function __construct()
    {    
        parent::__construct();
        loader::database();
        
    }
    
    function doly()
    {
        // ...
        // $this->db->query( ... );
        
        // calling library
        
        // loader::app_lib('doly');
        // $ob = ob::instance();
        // $ob->doly->test();
        
        // calling base library
        // lodaer::base_lib('session');
        
        // session::set('key', 'val');
        // session::get('key');
    }
        

} //end.



?>
