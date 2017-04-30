<div class="container admin">
	<div class="row well">
		<h1>Resultate löschen</h1>
	</div>
	<div class="row well">
		<select id="etappen" class="form-control">
		<?php
		foreach	($aEtappen as $k=>$v) {
			$sSelected = ($v['etappen_id'] == $iEtappe) ? ' selected' : '';
			?>
			<option value="<?= $v['etappen_id'];?>" <?= $sSelected;?>><?= $v['etappen_nr'] . '.Etappe';?></option>
			<?php	
		}
		?>
		</select>
	</div>
	<div class="row">
		<div class="alert alert-info">
			Das Script löscht alle eingetragenen Resultate einer Etappe aus der Datenbank und erlaubt diese anschliessend neu zu parsen.
		</div>
	</div>
	<div class="row well">
		<button class="btn btn-default" id="delete">Resultat löschen</button>
	</div>
</div>