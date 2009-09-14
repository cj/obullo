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
        
        // load the database
        loader::database();
        
        // load helper file
        loader::helper('helper_test');
        // or you can load another controller's helper
        // like this loader::helper('blog/helper_blog');
        
        // show directory list of current controller
        loader::dir();

        // i want to use blog's model from blog/ folder
        loader::model('blog/model_blog');
        
        // model_test from current folder
        loader::model('model_test');
        
    }                               
    
    function run()
    {
        /*
        ob::session_set_userdata(array());
        ob::session_userdata('key');
        ob::session_flashdata('key');
        */
        
        echo "<br />Run function succesfully works.<br /><br />";
        
        $data['sample_array'] = array('1','2','3','4','5');
        $data['example_var'] = "Hello World!";

        $this->model_test->test();
        
        ob::input_set('name','ersin');
        echo ob::input_get('name');
        

        ob_user::nav_level1();
        ob_user::nav_level2();
     
        echo "<br />";
        
        loader::view("view_test",$data);
        //fetch as string  loader::view("run",$data,true);   
    }
    
        
} //end of the class.
  
  
?>
