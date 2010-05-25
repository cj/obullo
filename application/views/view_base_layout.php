<?php echo config_item('html4-trans', 'doctypes')."\n"; ?>
<html>
<head>
<?php echo this()->meta; ?>

<title><?php echo this()->title; ?></title>

<base href="<?php echo this()->base; ?>" />

<?php 
this()->head .= css('welcome.css');
echo this()->head;
?>

</head>

<body <?php echo this()->body_attributes; ?>>

<?php echo this()->body; ?>

</body>

</html>