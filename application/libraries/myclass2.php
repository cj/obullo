<?php

/*
*  Example library file.
*  Your custom class.
* 
*/

Class Myclass2 extends Library
{
  
    // test for Can use db object from library or not ?
    function testDB()
    {
        $ob = ob::instance();
        
        loader::database();
        //$ob->load->database(); 
        
        $ob->db->drivers();
    }
  
} //end of the class.

?>
