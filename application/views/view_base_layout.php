<?php echo doctype('html4-trans')."\n"; ?>
<html>
<head>

<?php echo this()->meta_tag; ?>

<title><?php echo this()->title_tag; ?></title>

<base href="<?php echo this()->base; ?>" />

<?php 
this()->head_tag .= css('welcome.css');
echo this()->head_tag;
?>

</head>

<body <?php echo this()->body_attributes; ?>>

<?php echo this()->body_tag; ?>

</body>

</html>