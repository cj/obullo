<style type="text/css">
#exception_content {
font-family: verdana;
font-size:12;
width:50%;
padding:5px;
background-color: #F0F0F0;
}
</style>

<div id="exception_content">

<b>[<?php echo $type; ?> Error]: </b> <?php echo $e->getMessage(); ?> <br />
<?php if($e->getCode() != 0)  { ?>

<b>Code:</b> <?php echo $e->getCode(); ?> <br />

<?php } ?>
<b>File:</b> <?php echo $e->getFile(); ?> <br />
<b>Line:</b> <?php echo $e->getLine(); ?>

<?php if(! empty($sql)) { ?>

<br /><b>SQL: </b> <?php echo $sql; ?>

<?php } ?>

</div>