<div class="container admin">
	<div class="row well">
		<h1>Teilnahme</h1>
	</div>
	<div class="row">
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
	       } else {
		       ?>
		       <div class="alert alert-danger">Du bist noch nicht für die aktuelle Rundfahrt (<?= $this->config->item('sAktuelleRundfahrt');?>) angemeldet!</div>
		       <div class="form-group">
			       <label for="rolle">Deine Rolle</label>
			       <select id="rolle" name="rolle" class="form-control">
				    <?php
						foreach($aRollen as $k=>$v) {
							?>
							<option value="<?= $v['rolle_id'];?>"><?= $v['rolle_bezeichnung'];?></option>
							<?php
						}
					?>       
			       </select>
		       </div>
			   <div class="form-group">
			       <label for="team">Dein Team</label>
			       <select id="team" name="team" class="form-control">
				       <option value="0">Teamlos</option>
				    <?php
						foreach($aTeams as $k=>$v) {
							?>
							<option value="<?= $v['rzteam_id'];?>"><?= $v['rzteam_name'];?></option>
							<?php
						}
					?>       
			       </select>
			   </div>
			   <div class="form-group">
			   		<button id="teilnehmen" class="btn btn-default">Teilnehmen</button>
			   </div>
		       <?php
	       }	
		?>
	</div>
</div>