<style type="text/css">
#error_content {
font-family: verdana;
font-size:12;
width:50%;
padding:5px;
background-color: #F0F0F0;
}
</style>

<div id="error_content">

<b>[Obullo][<?php echo ucwords(strtolower($type)); ?>]:</b> <?php echo $errstr; ?> <br />
<b>File:</b> <?php echo $errfile; ?><br />
<b>Line:</b> <?php echo $errline; ?>

</div>