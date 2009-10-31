<?php

Class Test_input extends Controller
{

    function __construct()
    {
        parent::__construct();
        
        // top constructor for every controllers.
        parent::__user();
        
        loader::base_helper('form');
        loader::base('session');
        loader::database();
    }                               
    
    
    function index()
    {
        $this->title_tag = 'Input and Session Class Test !! ';
        
        //$this->session->set_userdata('test','this is a test session variable!!!');
        //$this->session->sess_destroy();
        //echo $this->session->userdata('test');
        
        ob::set_session('test','this is a test session variable!!!');
        echo ob::session('test');
        
        //$this->session->sess_destroy();
        //echo $this->session->userdata('test');
    
        $this->body_tag = form_open($this->base_url.'index.php/test/test_input');
        $this->body_tag.= form_input('username',ob::post('username'));
        $this->body_tag.= form_submit('send','Send');
        $this->body_tag.= form_close();
        
        loader::base_view('view_base');
    }

        
} //end of the class.
  
  
?>
