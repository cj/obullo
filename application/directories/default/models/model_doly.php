<?php

/**
* Your copy paste Model.
*/
Class Model_doly extends Model {
    
    function __construct()
    {    
        loader::database();
        parent::__construct();
        
    }
    
    public function doly()
    {
        // ...
        // $this->db->query( ... );
    }
        

} 

/* End of file model_doly.php */
/* Location: ./application/default/models/model_doly.php */
