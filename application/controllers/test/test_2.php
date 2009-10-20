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
    }                               
    
    function index()
    {
        //echo 'Im the test2 controller !';
    }


    function run($param1, $param2)
    {
        $data = user::header();
    
        $data['title_tag'] = 'Im the Test_2 Controller !';
        
        $data['body_content'] = '<br />Test2 Controller Run function succesfully works.<br /><br />';
        $data['body_content'].= '<b>this is my param 1 :</b> '. $param1.'<br />';
        $data['body_content'].= '<b>this is my param 2 :</b> '. $param2.'<br />';
        
        $data['sample_array'] = array('1','2','3','4','5');
        $data['example_var']  = 'Hello World!';
     
        echo '<br />';          
        
                                    
        $data['body_content'].= loader::view('view_test',$data, true);
        loader::base_view('view_base',$data);
        
        //fetch as string  loader::view("run",$data,true);   
    }
    
        
} //end of the class.
  
  
?>
