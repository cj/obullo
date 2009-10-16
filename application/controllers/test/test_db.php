<?php


Class Test_db extends Controller
{

    function __construct()
    {
        parent::__construct();
        
        //parent::__user();
        loader::database();
        loader::model('model_test_db'); 
        
        // i guess every Code Igniter developers sometime
        // don't understand that the 'application' and 'system'
        // library files where does come from !
        
        // In Obullo we separated library functions like this..
        /*
        loader::helper();       // load helper from application directory 
        loader::base_helper();  // load helper from base directory
        
        loader::library();      // load library from application directory 
        loader::base_library(); // load library from base directory
        
        loader::view();         // load view file from application directory
        loader::base_view();    // load view file from base directory
        */
    }                               
    
    function index()
    {
        $data = user::header();
    
        // change the title
        //$data['title_tag'] = 'Mypage Test Title';
        //$data['head_tag'].= loader::js('blog');
        //$data['head_tag'].= loader::script('calendar');
        $data['body_content'] = loader::view('view_test_db',$data, true);  
               
        loader::base_view('view_base',$data);
    
    
        echo '<p></p>';
     
        // Without bind value kullanacağız her zaman
        // bind Value ve bindParams sadece direct query ler için.
              
        $this->db->where('article_id',1); 
        $this->db->delete('articles'); 
              
        exit;      
                         
       
        $this->db->prep();   // tell to db class use pdo prepare
        $this->db->select("*");
        $this->db->join('comments','cm_article_id = article_id','left');
        $this->db->where('article_id',':id');
        $this->db->like('article',':like_both');
        //$this->db->limit(2);
        $this->db->get('articles'); 
        //$res = $this->db->all(assoc);
        //print_r($res);
        //echo $this->db->last_query();  
        
       # http://stackoverflow.com/questions/583336/how-do-i-create-a-pdo-parameterized-query-with-a-like-statement-in-php
       
        
        $exec = array(
        ':id' => '2',
        ':like_both' => 'see',
        ); 
        $this->db->execute($exec);
                     
        $a = $this->db->all(assoc);
        print_r($a).'<br />';
        
        echo 'last query: ' . $this->db->last_query(true);
        
        echo "<hr size='1' />";
        
        // change the value and execute cached sql
        $this->db->execute(array(':id'=>2));
        $b = $this->db->row();   
        echo '<br />'.$b->article.'<br /><br />';
        
        // Direct query.
        $this->db->query("SELECT * FROM articles");
        $res = $this->db->all(assoc);
        
        print_r($res);
        
        echo '<p></p>';
        
        echo '<b>Last Query:</b> '.$this->db->last_query();
        
        //echo $this->db->get();
     
    
        echo '<hr size="1">';   
        
        
        // shorcut functions for filters 
        /*
        ob::like_filter('location');
        output:
        if($this->input->post('location'))
        $this->db->like('location', $this->input->post('location'));
        
        
        ob::where_filter('location');
        output:
        if($this->input->post('location'))
        $this->db->where('location', $this->input->post('location'));
        */
        

        
    }
    
        
} //end of the class.
?>
