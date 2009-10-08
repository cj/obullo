<?php
  // a clear controller scheme example
  // copy and paste it
  
  Class Example extends Controller
  {

    public $var = '';     // $this->var;
    const  example = '';  // self::example  

    function __construct()
    {
        parent::__construct();
        parent::__user();
        
        loader::helper('');
        loader::model('');
        loader::library('');
        
    }                               
    
    function index(){}
    
    function myfunction()
    {
        parent::__header();
 
 
    
    }
    
  }
  
  
?>
