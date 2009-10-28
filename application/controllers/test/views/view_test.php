<?php

// head tagları view de oluşturmak daha mantıklı..
//$this->head_tag.= loader::script('autosearch');
//$this->head_tag.= loader::script('calendar',$data);

?>



<h3> <?php echo $GLOBALS['d']?>/views/view_test.php </h3>

<div>

<?php //echo $this->myclass->testMe(1,2); ?>

<?php echo "<h1>".$example_var."</h1>"; ?>

<?php echo "<b>Var: </b>".$this->sample_var."<br /><br />"; ?>

<?php foreach($sample_array as $val) { ?>

<?php echo $val."<br />"; ?>

<?php } ?>

</div>
