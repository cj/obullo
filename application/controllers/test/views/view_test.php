<?php

// load script and js files
$this->head_tag.= loader::base_js('jquery');
$this->head_tag.= loader::script('test');

loader::base_helper('form');
?>

<!-- body_tag start -->

<h3> <?php echo ob::directory(); ?>/views/view_test.php </h3>

<div>

<?php //echo $this->myclass->testMe(1,2); ?>

<?php echo "<h1>".$example_var."</h1>"; ?>

<?php echo "<b>Var: </b>".$this->sample_var."<br /><br />"; ?>

<?php foreach($sample_array as $val) { ?>

<?php echo $val."<br />"; ?>

<?php } ?>


<b>Test loader::script('test');  </b><br /> 
<?php echo form_button('js_test_button','Alert Me!'," onclick='alertMe();'") ?>

<p></p>
</div>

<!-- body_tag end -->