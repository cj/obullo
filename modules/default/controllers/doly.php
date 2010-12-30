<?php

/**
* Your copy paste Controller.
*/
Class Doly extends Controller {
    
    function __construct()
    {   
        parent::__construct();
    }                               

    public function index()
    {
        view_var('title', '');

        view_var('body', view(''));
        view_app('');
    }
    
}

/* End of file start.php */
/* Location: .application/default/controllers/start.php */
