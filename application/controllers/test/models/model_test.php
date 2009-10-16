<?php

   
Class Model_test extends Model
{

    function __construct()
    {    
        // Call the Model constructor
        parent::__construct();
        
        // Load database connection
        
         ### WARNING database i burada ilan edince çalışmıyo !! ###
        //load another model 
        //loader::model('blog/model_blog');
        loader::database();
        
        // Using library from model
        //loader::library('myclass');
    }

    /**
    * Test Function
    * @link http://tr.php.net/manual/en/pdostatement.fetch.php  
    */
    public function test()
    {   
        //loader::database(); 
        //ob::input_set('name','mahmut');
        //echo ob::input_get('name');
        
        //ob_user::nav_level1();

       //$this->db->drivers();
        
        //echo ob::input_ip();

        //echo 'Using Model inside another model: succesfull!<br /> ';
        //$this->model_blog->test();
                    
        //---------- Prepared Query ----------//  
        
        $this->db->prep();   // tell to db class use pdo prepare
        $this->db->query("SELECT * FROM articles WHERE article_id=:id OR link=:code");
        $this->db->bval(':id', $id=1, p_int); //INTEGER 
        // alias of PDOStatement::bindValue();
        
        $this->db->bval(':code',$code='i-see-dead-people', p_str);
         
        //STRING // alias PDOStatement::bindValue();  
        //$this->db->param(':colour', $colour, PDO::PARAM_STR); 
        //alias of pdo::bindParam() 
        
        $this->db->execute();
        $a = $this->db->all(assoc);  // or obj
        print_r($a);
        
        $this->db->bval(':id', $id=2, p_int); //INTEGER
        
        $this->db->execute();
        $a = $this->db->row();  // or obj
        echo '<br />'.$a->title;
        
        //---------- Prepared Query ----------//
        
        //------- Without bindvalue ----------//
        
        echo '<br /><br /><b>Without bindvalue Query:</b> <br />';
                    
        $this->db->prep();   // tell to db class use pdo prepare
        $this->db->query("SELECT * FROM articles WHERE article_id = :id"); 

        $this->db->execute(array(':id'=>1));           
        $a = $this->db->assoc();
        print_r($a).'<br />';
        
        // change the value
        $this->db->execute(array(':id'=>2));
        $b = $this->db->row();   
        echo '<br />'.$b->article;
        
        //------- Without bindvalue ----------//
        
        
        //-- Direct Query and Next Row Example --//
        
        echo '<br /><br /><b>Direct Query:</b> <br />';
        
        $res = $this->db->query("SELECT * FROM articles");
        
        $a = $res->num_rows();  
        echo $a.'<br /><br />';
        
        $b = $res->assoc();     // NEXT ROW
        print_r($b).'<br />';
        
        $c = $res->obj(); //object  // NEXT ROW
        echo $c->article.'<br /><br />';
        
        $d = $res->all(assoc); //or obj // NEXT ROW
        '<br />'.print_r($d).'<br /><br />'; 
        
        //-- Direct Query and Next Row Example --//
        
    } //end func.

} //end class



?>
