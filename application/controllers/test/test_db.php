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
     
        /* when finish active record we we will implement 
        a short mode class when config['short_mode'] = TRUE
        it will load active_record_short_mode class else long_mode
        class.
        //--------- SHORT MODE -------------//
        $this->db->p();   // tell to db class use pdo prepare
        $this->db->s("*");
        $this->db->j('comments','cm_article_id = article_id','left');
        $this->db->w('article_id',':id');
        $this->db->t('articles');
        
        $this->db->e(array(':id' => 1));
        $a = $this->db->assoc();
        print_r($a).'<br />';
        //--------- SHORT MODE -------------//
        */ 
     
     
        // Without bind value kullanacağız her zaman
        // bind Value ve bindParams sadece direct query ler için.
                
        $this->db->prep();   // tell to db class use pdo prepare
        $this->db->select("*");
        $this->db->join('comments','cm_article_id = article_id','left');
        $this->db->where('article_id',':id');
        $this->db->table('articles'); 
        
        //echo $this->db->output(); exit;
         
        
        //$this->db->output();
        
        $this->db->exec(array(':id' => 1));           
        $a = $this->db->assoc();
        print_r($a).'<br />';
        
        // change the value
        $this->db->exec(array(':id' => 2));
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
    }
    
        
} //end of the class.
?>
