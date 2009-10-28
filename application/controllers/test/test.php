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
        
        loader::base('input');
        
       // loader::helper('test');      // load helper from /application/ directory
        loader::base_helper('text'); // load helper from /base directory
        
        //loader::library('mylibrary');
        loader::base('cookie');
       
    }                               
    
    function index()
    {                        
        parent::__index();
        parent::__header();
    
        $this->title_tag = 'Im the Test Controller !';
    
        // change the title
        //$data['title_tag'] = 'Im the Test Controller !';
        
        $this->body_tag = "<br /><br />";
        $this->body_tag.= "<b>Directory:</b> test/ &nbsp;<b>Controller:</b> test/ &nbsp;<b>Method:</b> /run &nbsp;<br /><br />";
        $this->body_tag.= "Click and run test contoller  <a href='".$this->base_url."index.php/test/test/run'>  /test/test/run </a>";
        $this->body_tag.= "<br /><br />";
        
        $this->body_tag.= "<br /><br />";
        $this->body_tag.= "<b>Directory:</b> test/ &nbsp;<b>Controller:</b> test2/ &nbsp;<b>Method:</b> /run  &nbsp;<b>Params:</b> /hello/3 <br /><br />";
        $this->body_tag.= "Click and run test2 contoller  <a href='".$this->base_url."index.php/test/test_2/run/hello/3'>  /test/test_2/run/hello/3 </a>";
        $this->body_tag.= "<br /><br />";
        
        
        $this->body_tag.= "<br /><br />";
        $this->body_tag.= "Active Record Test:<br />";
        $this->body_tag.= "<b>Directory:</b> test/ &nbsp;<b>Controller:</b> test_db/  &nbsp;&nbsp;";        
        $this->body_tag.= "<a href='".$this->base_url."index.php/test/test_db'>Click and run test_db contoller</a>";        
        $this->body_tag.= "<br /><br />";
        
        loader::base_view('view_base',$data);
    }
    
    
    function run()
    {
        parent::__header();
          
        $this->title_tag = 'Im the Test2 Controller !';  
        //$this->head_tag  = loader::base_js('jquery');  head tagları view da oluşturmak daha mantıklı..
        
        loader::library('myclass'); 
        $this->myclass->testDB();
        
        //$this->mylibrary->test_ssc();
        //$this->model_test->test();
    
        // System static function
        //echo ob::ip();  

        echo '<br />Run function succesfully works.<br /><br />';
        
        $data['sample_array'] = array('1','2','3','4','5');
        $data['example_var']  = 'Hello World!';

        $this->body_tag = loader::view('view_test',true);
        loader::base_view('view_base');
    }
    
        
} //end of the class.
  
  
?>
