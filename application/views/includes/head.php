<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=360px, initial-scale=1.0">
  <title><?php echo $title; ?></title>
  <?php foreach ($csss as $css) {
  	echo "<link rel='stylesheet' href='".css_url($css)."'> \n";
  } 
  	?>
  <script type="text/javascript">
  	var base_url = "<?php echo base_url(); ?>";
    var room = "<?php echo $room; ?>";
  	console.log(base_url);
  </script>
</head>
<body>
<header><img src="<?php echo img_url("domotik.png"); ?>" height="100"></header>
<div id="responsive">

