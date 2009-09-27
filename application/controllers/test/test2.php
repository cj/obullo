<?php
/**
*  Our second controller controllers/test/test2.php
*/
Class Test2 extends Controller
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
        echo 'Im the test2 controller !';
    }

    /**
    * @param string $param1 - hello (my string param)
    * @param int $param2  - 3 (my integer param)
    */
    function run($param1, $param2)
    {
        parent::__header();
    
        echo '<br />Test2 Controller Run function succesfully works.<br /><br />';
        
        echo '<b>this is my param 1 :</b> '. $param1.'<br />';
        echo '<b>this is my param 2 :</b> '. $param2.'<br />';
        
        $data['sample_array'] = array('1','2','3','4','5');
        $data['example_var']  = 'Hello World!';
     
        echo '<br />';
                                    
        loader::view('view_test',$data);
        //fetch as string  loader::view("run",$data,true);   
    }
    
        
} //end of the class.
  
  
?>
