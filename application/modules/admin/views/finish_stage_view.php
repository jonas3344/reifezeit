<div class="container admin">
	<div class="row well">
		<h1>Etappe abschliessen</h1>
	</div>
	<div class="row">
	<div class="alert alert-info lead">Damit wir die morgige Etappe starten können musst du hier die aktuelle Etappe ändern sowie die ersten 3 Startnummern der aktuellen Etappe eintragen!<br><br><strong>Bei MZF, bitte jeweils den ersten Fahrer der ersten 3 Teams eintragen!</strong></div>
	</div>
	<div class="row well">
		<label for="etappen">Nächste Etappe:</label>
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
	<div class="row well">
		<div class="form-group">
			<label for="erster">Erster der heutigen Etappe (Startnummer)</label>
			<input type="text" class="form-control" name="erster" id="erster">
			<span id="erster_result" class="lead"></span>
		</div>
			<div class="form-group">
			<label for="zweiter">Zweiter der heutigen Etappe (Startnummer)</label>
			<input type="text" class="form-control" name="zweiter" id="zweiter">
			<span id="zweiter_result" class="lead"></span>
		</div>
		<div class="form-group">
			<label for="erster">Dritter der heutigen Etappe (Startnummer)</label>
			<input type="text" class="form-control" name="dritter" id="dritter">
			<span id="dritter_result" class="lead"></span>
		</div>
			<button type="submit" class="btn btn-primary">Absenden</button>
		</form>
		
	</div>
</div>