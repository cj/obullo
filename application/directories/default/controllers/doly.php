<?php

/**
* Your copy paste Controller.
*/
Class Doly extends Controller {
    
    function __construct()
    {   
        parent::__construct();
        parent::__global();
    
    }                               

    public function index()
    {
        $this->title = '';
    
        $this->head  = '';
        $this->head .= '';

        $this->body  = content_view('');
        content_app_view('');
    }
    
} //end.

?>