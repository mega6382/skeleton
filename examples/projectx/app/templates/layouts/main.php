<?php

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo "App: $title"; ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<base href="<?php echo $BASE; ?>" />
<?php echo $head; ?></head>
<body>
<div id="container">

	<div id="header">
		<h1><?php echo "App: $title"; ?></h1>
	</div>
	
	<div id="content">
		<?php echo $content; ?>
	</div>

</div>
</body>
</html>