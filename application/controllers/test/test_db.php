<?php


Class Test_db extends Controller
{

    function __construct()
    {
        parent::__construct();
        
        //loader::model('model_test_db');
    }                               
    
    function index()
    {
        loader::database();          
     
        echo '<p></p>';
     
        // Without bind value kullanacağız her zaman
        // bind Value ve bindParams sadece direct query ler için.
        /*      
        $this->db->where('article_id',1); 
        $this->db->delete('articles'); 
              
        exit;      
                         
        // Multiple Insert
        $data = array(
        'title'   => 'first title',
        'article' => 'first description',
        'link'    => 'first link',
        );
        
        //print_r(array_values($data)); exit;
        
        $data2 = array(
        'title'   => 'second title',
        'article' => 'second description',
        'link'    => 'second link',
        );
     
        $this->db->insert_values($data);
        $this->db->insert_values($data2);
        
        //$this->db->where('article_id', 1);
        //$this->db->where('article', 'sd');
        $this->db->insert('articles');       
        exit;
        */        
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
        echo $this->db->last_query(true);
        exit;
        
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
