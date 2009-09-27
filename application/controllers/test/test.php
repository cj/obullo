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
        
        // load helper file
        loader::helper('helper_test');
        // or you can load another controller's helper
        // like this loader::helper('blog/helper_blog');
        
        // show directory list of current controller
        //loader::dir();
    
        // model from another folder
        loader::model('blog/model_blog');
        
        // model from current folder                       
        loader::model('model_test');  
        loader::library('mylibrary');
        //loader::library('input'); 
    }                               
    
    function index()
    {
        echo "<br /><br />";
        echo "<b>Directory:</b> test/ &nbsp;<b>Controller:</b> test/ &nbsp;<b>Method:</b> /run &nbsp;<br /><br />";
        echo "Click and run test contoller  <a href='http://localhost/obullo/index.php/test/test/run'>  /test/test/run </a>";
        echo "<br /><br />";
        
        echo "<br /><br />";
        echo "<b>Directory:</b> test/ &nbsp;<b>Controller:</b> test2/ &nbsp;<b>Method:</b> /run  &nbsp;<b>Params:</b> /hello/3 <br /><br />";
        echo "Click and run test2 contoller  <a href='http://localhost/obullo/index.php/test/test2/run/hello/3'>  /test/test2/run/hello/3 </a>";
        echo "<br /><br />";
    }
    
    
    function run()
    {
        // Run our static functions
        $this->mylibrary->test_ssc();
    
        // System static function
        echo ob::ip();  

        
        echo '<br />Run function succesfully works.<br /><br />';
        
        $data['sample_array'] = array('1','2','3','4','5');
        $data['example_var']  = 'Hello World!';

        //$this->model_blog->test();
        //$this->model_test->test();

     
        echo '<br />';
                                    
        loader::view('view_test',$data);
        //fetch as string  loader::view("run",$data,true);   
    }
    
        
} //end of the class.
  
  
?>
