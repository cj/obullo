<?php

/*
*  Example library file.
*  Your custom class.
* 
*/

Class Myclass extends Library
{
    function __construct()
    {
        loader::database();   
    }
    
    
    function testMe($a,$b)
    {
        echo "<br /><br /><b> Myclass::testMe ".$a."-".$b."</b> 
        function succesfully works.<br />";
    }
    
    // test for Can use db object from library or not ?
    function testDB()
    {
        //$ob = ob::instance();
        echo $this->db->drivers(); 
    
        echo ob::ip();
    }
    
    function tester()
    {
        echo 'tester ok!!!!!!!!!!!!!!!!!!!!!!!!!';
    }
  
} //end of the class.

?>
