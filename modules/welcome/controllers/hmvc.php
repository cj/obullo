<?php
  
        $hmvc = hmvc_call('blog/blog/write/18282/', 0);
        $hmvc->set_post(array('test' => 'obullOyyyy'));
        echo $hmvc->exec();
            
        echo '<br /><br />';
            
        $hmvc2 = hmvc_call('blog/blog/read/4455', 0);
        echo $hmvc2->exec();
  
?>
