<?php


Class Test_db extends Controller
{

    function __construct()
    {
        parent::__construct();
        
        parent::__user();
        loader::database();
        loader::model('model_test_db'); 
        
        loader::library('myclass');
        //loader::base_library('session');
    }                                                      
    
    function index()
    {
        parent::__header();
    
        // change the title
        $this->title_tag = 'Mypage Test Title';  
        $this->body_tag  = loader::view('view_test_db',null,true);  
        loader::base_view('view_base');  // base view comes from  /base/views directory
    
        echo '<p><b>Prepared Query:</b></p>';
        /*
        $data = array(
                    'title'       => 'article title..',
                    'description' => 'article description ..',
                    'link'        => 'article-link',
                    );       
        
        $affected_rows = $this->db->insert('articles',$data); 
        echo 'Affected Rows: ' . $affected_rows;
        */  
        // output: 1
        
        //print_r($data);
    
        $this->db->prep();   // tell to db class use pdo prepare
        $this->db->select("*");
        $this->db->join('comments','cm_article_id = article_id','left');
        $this->db->where('article_id',':id');
        $this->db->like('article',':like');
        //$this->db->limit(2);
        $this->db->get('articles'); 

        $exec = array(
        ':id' => 1,
        ':like' => 'both|nal',
        ); 
        $this->db->exec($exec);

        $a = $this->db->all(assoc);
        print_r($a).'<br />';
        
        echo '<p></p>';
        echo '<b>last query:</b> ' . $this->db->last_query(true); // false
        
        echo "<hr size='1' />";
        
        echo '<b>Change the value:</b><br />';
        
        // change the value and execute cached sql
        $this->db->exec(array(':id'=>2,':like'=>'both|see'));
        
        if($this->db->num_rows() > 0)
        {
            $b = $this->db->row();   
            echo '<br />'.$b->article.'<br /><br />';
        }
        
        echo '<p></p>';
        echo '<b>last query:</b> ' . $this->db->last_query(true); // false
        
        echo "<hr size='1' />"; 
        echo '<br /><b>Direct Query:</b><br /><br />';
        
        // Direct query.
        $this->db->query("SELECT * FROM articles");
        $res = $this->db->all(assoc);
        
        print_r($res);
        
        echo '<p></p>';
        echo '<b>last query:</b> '.$this->db->last_query();
        
        //echo $this->db->get();
        echo '<hr size="1">';   
          
    }
    
        
} //end of the class.
?>
