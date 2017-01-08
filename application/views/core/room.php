<section>
	<?php 
	  	$atleastoneon = false;
	  	$htmldevice = "";
	    foreach ($devices as $device) {
		    $htmldevice .= "<div class='".$device->state." device' id='dev".$device->id."'>";
		  	$htmldevice .= "<p>".$device->name."<br />";
		  	$htmldevice .= img("offwhite.png", "off", "off");
		  	$htmldevice .= img("onwhite.png", "on","on");
		  	$htmldevice .= ico($device->path_ico,$device->alt_ico)."<br />";

		  	//echo $device->description."</p>";

		  	$htmldevice .= "</div>";
		  	if($device->state=="on"){
		  		$atleastoneon = true;
		  	}
	  	}
	?>
  	
	
	<div id="switchall" class="<?php if($atleastoneon){echo 'on';}else{echo "off";}?>">
  		<h3><?php if($room!=""){echo ucfirst($room);}else{echo "Tout";}?></h3>
  		<?php echo img("offblue.png", "off", "off");
  			echo img("onblue.png", "on","on");
  			?>
	</div>
	<div class="rooms">
		<?php echo $htmldevice; ?>
	</div>
</section>