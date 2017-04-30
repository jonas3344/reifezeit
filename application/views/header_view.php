<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Jonas Bay">
		<link rel="icon" href="../../favicon.ico">

		<title>Reifezeit</title>

	    <!-- CSS -->
	    <?php
		foreach($aCss as $k=>$v) { ?>
			<link href="<?= base_url(),'css/',$v ?>" rel="stylesheet">
	<?php }
	?>
	<script>
	  var base_url = "<?= base_url();?>";
	 </script>
</head>
 