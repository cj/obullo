<?php

/**
* Your copy paste Model.
*/
Class Model_doly extends Model {
    
    function __construct()
    {    
        parent::__construct();
        loader::database();
        
    }
    
    public function doly()
    {
        // ...
        // $this->db->query( ... );
    
    }
        

} //end.

?>