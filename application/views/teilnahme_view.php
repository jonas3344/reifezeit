<div class="container admin">
	<div class="row well">
		<h1>Teilnahme</h1>
	</div>
	<div class="row well">
		<?php
		if ($mTeilnahme != false) {
		       ?>
		       <div class="alert alert-success lead">Du bist als <strong><?= $mTeilnahme['rolle_bezeichnung'];?></strong> für die <?= $this->config->item('sAktuelleRundfahrt');;?> angemeldet!
		       <?php
			       if (isset($mTeilnahme['team'])) {
			       ?>
			       	<br><br>
				   	Dein Team: <strong><?= $mTeilnahme['team']['rzteam_name'];?></strong></div>
				   	<?php
			       }
	       }	
			
			
			
		?>
	</div>
</div>