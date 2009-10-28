<?php

// dropdowns here...

    function test_my_combobox()
    {
        $ob = ob::instance();
        
        $ob->db->select('clt_id,clt_name');
        $ob->db->order_by('clt_name');
        
        $query = $ob->db->get('_clients');
        $rows  = $query->all(assoc);
        
        $options = array();
        foreach($rows as $k=>$v)
        {
            $options[$v['clt_id']] = $v['clt_name'];
        }
        
        return $options;
    }


?>