<?php
/**
*  Our first controller controllers/test/test.php
*/
Class Test extends Controller
{

    public $sample_var  = 'you can use $this->variable from view';    
    public $sample_var2 = 'you can use $this->variable from model';    

    function __construct()
    {
        parent::__construct();
        
        // top constructor for every controllers.
        parent::__user();
        
        loader::helper('test');      // load helper from /application/ directory
        loader::base_helper('text'); // load helper from /base directory
        
        loader::library('mylibrary');
        loader::base_library('cookie'); 
    }                               
    
    function index()
    {
        $data = user::header();
    
        // change the title
        $data['title_tag'] = 'Im the Test Controller !';
        
        $data['body_content'] = "<br /><br />";
        $data['body_content'].= "<b>Directory:</b> test/ &nbsp;<b>Controller:</b> test/ &nbsp;<b>Method:</b> /run &nbsp;<br /><br />";
        $data['body_content'].= "Click and run test contoller  <a href='".$this->base_url."index.php/test/test/run'>  /test/test/run </a>";
        $data['body_content'].= "<br /><br />";
        
        $data['body_content'].= "<br /><br />";
        $data['body_content'].= "<b>Directory:</b> test/ &nbsp;<b>Controller:</b> test2/ &nbsp;<b>Method:</b> /run  &nbsp;<b>Params:</b> /hello/3 <br /><br />";
        $data['body_content'].= "Click and run test2 contoller  <a href='".$this->base_url."index.php/test/test_2/run/hello/3'>  /test/test_2/run/hello/3 </a>";
        $data['body_content'].= "<br /><br />";
        
        
        $data['body_content'].= "<br /><br />";
        $data['body_content'].= "Active Record Test:<br />";
        $data['body_content'].= "<b>Directory:</b> test/ &nbsp;<b>Controller:</b> test_db/  &nbsp;&nbsp;";        
        $data['body_content'].= "<a href='".$this->base_url."index.php/test/test_db'>Click and run test_db contoller</a>";        
        $data['body_content'].= "<br /><br />";
        
        
        loader::base_view('view_base',$data);
    }
    
    
    function run()
    {
        $data = user::header();  
        $data['title_tag'] = 'Im the Test2 Controller !';  
        
        $this->mylibrary->test_ssc();
        //$this->model_test->test();
    
        // System static function
        echo ob::ip();  

        echo '<br />Run function succesfully works.<br /><br />';
        
        $data['sample_array'] = array('1','2','3','4','5');
        $data['example_var']  = 'Hello World!';

        $data['body_content'] = loader::view('view_test',$data,true);
        loader::base_view('view_base',$data);
    }
    
        
} //end of the class.
  
  
?>
