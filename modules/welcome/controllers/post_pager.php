<?php      

Class Post_pager extends Controller {
    
    function __construct()
    {   
        parent::__construct();
        parent::__global();
        
        loader::database();
        loader::base_helper('form');
    }           
    
    public function index()
    {       
        // http://devzone.zend.com/article/2418
        $query = $this->db->query('SELECT * FROM articles');
        $num_rows = $query->row_count();
        
        echo 'PER PAGE: '.i_get_post('set_per_page').'<br />';
        
        $per_page = (i_get_post('set_per_page')) ? i_get_post('set_per_page') : '5';
        
        $params = array(
            'mode'         => 'jumping',  // jumping
            'per_page'     => $per_page,
            'delta'        => 2,
            'http_method'  => 'GET',
            'url_var'      => 'page',
            'query_string' => TRUE,
            'current_page' => $this->uri->segment(4),
            'base_url'     => '/obullo/index.php/welcome/post_pager/index',
            'total_items'  => $num_rows,
            'extra_vars'   => array('set_per_page' => $per_page),
        );
        
        $pager = pager::instance()->init($params);
         
        list($from, $to) = $pager->get_offset_by_page();
         
        echo 'from:'.$from.'<br />';
        echo 'to:'.$to.'<br />';
         
        $this->db->get('articles', $params['per_page'], $from - 1);
        $data = $this->db->fetch_all(assoc);
         
        // $data  = $pager->get_page_data();
        $links = $pager->get_links();

        //$links is an ordered+associative array with 'back'/'pages'/'next'/'first'/'last'/'all' links.
        //NB: $links['all'] is the same as $pager->links
        
        // print_r($links); exit;
        
        //echo links to other pages:
        // echo $links['all'];
        
        echo form_open('/welcome/post_pager/index', array('method' => $params['http_method']));
        echo $links['first'].$links['back'].'&nbsp;&nbsp;'.$pager->get_page_select_box().'&nbsp;&nbsp;'.$links['next'].'&nbsp;'.$links['last'];
        echo '&nbsp;&nbsp; Per Page &nbsp;'.$pager->get_per_page_select_box().'&nbsp';
        echo form_submit('_send', 'Send', "");
        echo form_close();
        
        
        //Pager can also generate <link rel="first|prev|next|last"> tags
        // echo 'tags:'. $pager->linkTags.'<br /><br /><br />';

        //Show data for current page:
        print 'PAGED DATA: '.print_r($data).'<br /><br /><br />';
        
        //Results from methods:
        echo 'get_current_page()...: '; var_dump($pager->get_current_page());
        echo 'get_next_page()......: '; var_dump($pager->get_next_page());
        echo 'get_prev_page()......: '; var_dump($pager->get_prev_page());
        echo 'num_items()..........: '; var_dump($pager->num_items());
        echo 'num_pages()..........: '; var_dump($pager->num_pages());
        echo 'is_first_page()......: '; var_dump($pager->is_first_page());
        echo 'is_last_page().......: '; var_dump($pager->is_last_page());
        echo 'is_last_page_end()...: '; var_dump($pager->is_last_page_end());
        echo '$pager->range........: '; var_dump($pager->range);
        
        /*
        //Results from methods:
        echo 'get_current_page()...: '.var_dump($pager->get_current_page()).'<br />';
        echo 'get_next_page()......: '.var_dump($pager->get_next_page()).'<br />';
        echo 'get_prev_page()..: '.var_dump($pager->get_prev_page()).'<br />';
        echo 'num_items()...........: '.var_dump($pager->num_items()).'<br />';
        echo 'num_pages()...........: '.var_dump($pager->num_pages()).'<br />';
        echo 'is_first_page()........: '.var_dump($pager->is_first_page()).'<br />';
        echo 'is_last_page().........: '.var_dump($pager->is_last_page()).'<br />';
        echo 'is_last_page_end().: '.var_dump($pager->is_last_page_end()).'<br />';
        echo '$pager->range........: '.var_dump($pager->range).'<br />';
        */
        /*
        view_var('title', 'Welcome to Obullo Framework !');
        
        $data['var'] = 'This page generated by Obullo.';
        
        view_var('body', view('view_welcome', $data)); 
        view_app('view_base_layout'); 
        */

    }
    
}

/* End of file start.php */
/* Location: .application/welcome/controllers/start.php */