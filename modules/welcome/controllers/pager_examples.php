<?php


        $params = array(
            'mode'         => 'Sliding',
            'per_page'     => 8,
            'delta'        => 2,
            'http_method'  => 'GET',
            'url_var'      => 'page',
            'query_string' => FALSE,
            'current_page' => $this->uri->segment(4),
            'base_url'     => '/obullo/index.php/welcome/start/index/',
        );
        
        // just dummy data
        $row = array();
        for($i=0; $i<=256; $i++)
        $row[$i] = $i.'__item';

        $params['item_data'] = $row;
        
        $pager = pager::instance()->init($params);
         
        $data  = $pager->get_page_data();
        $links = $pager->get_links();

        //$links is an ordered+associative array with 'back'/'pages'/'next'/'first'/'last'/'all' links.
        //NB: $links['all'] is the same as $pager->links;

        //echo links to other pages:
        echo $links['all'];

        //Pager can also generate <link rel="first|prev|next|last"> tags
        echo $pager->link_tags;

        //Show data for current page:
        echo 'PAGED DATA: '; print_r($data);
