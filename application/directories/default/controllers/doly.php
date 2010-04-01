<?php

/**
* Your copy paste Controller.
*/
Class Doly extends Controller
{
    function __construct()
    {   
        parent::__construct();
        parent::__global();
    
    }                               

    public function index()
    {
        $this->title_tag = '';
    
        $this->head_tag  = '';
        $this->head_tag .= '';

        $this->body_tag  = content::view('');
    
        content::app_view('');
    }
    
} //end.

?>
