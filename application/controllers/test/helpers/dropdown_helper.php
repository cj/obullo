<?php

// dropdowns here...

    function test_dropdown()
    {
        $ob = ob::instance();
        $ob->load->database();
        //$ob->load->base('session');
        
        $ob->db->select('article_id,title');
        $ob->db->order_by('title');
        
        $query = $ob->db->get('articles');
        
        $options = array();
        foreach($query->all(assoc) as $k=>$v)
        {
            $options[$v['article_id']] = $v['title'];
        }
        
        return $options;
    }


?>