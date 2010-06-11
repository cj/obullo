<?php echo config_item('html4-trans', 'doctypes')."\n"; ?>
<html>
<head>
<?php
echo meta('Content-type', 'text/html; charset=utf-8', 'equiv');
echo meta('description', '');
echo meta('author', '');
?>

<?php echo this()->meta; ?>

<title><?php echo this()->title; ?></title>

<base href="<?php echo this()->base; ?>" />

<?php 
echo css('welcome.css');
echo this()->head;
?>
</head>

<body <?php echo this()->body_attributes; ?>>

<?php echo this()->body; ?>

</body>
</html>