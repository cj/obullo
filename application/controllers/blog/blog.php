<?php
  
/*
*  When the finish obullo framework we will do a sample blog application
* 
*/

Class Test extends Controller
{

public $sample_var = "you can use variable from view";    

    function __construct()
    {
        parent::__construct();
        parent::ob_user();
        
        //load your class.
        loader::library('myclass');
        
        //next version implemantations..
        //$this->load->helper();  //default blog_helper
        //$this->load->lang('blog');
        //$this->load->form('blog');

    }                               
    
    function run()
    {
        echo "Run function succesfully works.<br /><br />";
        
        $data['sample_array'] = array('1','2','3','4','5');
        $data['example_var'] = "Hello World!";
        
        //example library class.
        $this->myclass->testMe(1,2); 
        
        echo "<br />";
        
        //example a system/library class: session.
        //$this->session->set("test","Session variable succesfully works!");
        //echo $this->session->get("test");
        
        //example view.
        laoder::view("view_blog",$data);
        //fetch as string  $this->view("run",$data,true);   
    }
    
        
} //end of the class.
  
  
?>
