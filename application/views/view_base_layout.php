<?php echo doctype('html4-trans')."\n"; ?>
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