<?php

/*
*  Example library file.
*  Your custom class.
* 
*/

Class Myclass2
{
  
    // test for Can use db object from library or not ?
    function testDB()
    {
        $ob = OB_instance();
        
        load::database();
        //$ob->load->database(); 
        
        $ob->db->drivers();
    }
  
} //end of the class.

?>
