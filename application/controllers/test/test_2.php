<?php
/**
*  Our second controller controllers/test/test2.php
*/
Class Test_2 extends Controller
{
    public $sample_var  = 'you can use $this->variable from view';    
    public $sample_var2 = 'you can use $this->variable from model';   

    function __construct()
    {
        parent::__construct();
        
        // top constructor for every controllers.
        parent::__user();
        
        loader::model('model_test');
        loader::library('mylibrary');
    }                               
    
    function index()
    {
        //echo 'Im the test2 controller !';
    }


    function run($param1, $param2)
    {
        parent::__header();
    
        $this->model_test->platform(); 
    
        $this->title_tag = 'Im the Test_2 Controller !';
        
        $this->body_tag = '<br />Test2 Controller Run function succesfully works.<br /><br />';
        $this->body_tag.= '<b>this is my param 1 :</b> '. $param1.'<br />';
        $this->body_tag.= '<b>this is my param 2 :</b> '. $param2.'<br />';
        
        $this->data['sample_array'] = array('1','2','3','4','5'); 
        $this->data['example_var']  = 'Hello World !!';
        
        //$data['sample_array'] = array('1','2','3','4','5');
        //$data['example_var']  = 'Hello World!';
        echo '<br />';          
                    
        $this->body_tag.= loader::view('view_test',null,true);
        loader::base_view('view_base');
        
        //fetch as string  loader::view("run",$data,true);   
    }
    
        
} //end of the class.
  
  
?>
