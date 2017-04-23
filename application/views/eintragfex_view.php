<div class="container admin">
	<div class="row well">
		<h1>Eintrag Fexpunkte!</h1>
	</div>
		<div class="row well">
		<select id="etappen" class="form-control">
			<?php
			foreach($aEtappen as $aE) {
				$sSel = ($aE['etappen_id'] == $iEtappe) ? ' selected' : '';
				?>
				<option value="<?= $aE['etappen_id'];?>" <?= $sSel;?>><?= $aE['etappen_nr'];?>.Etappe</option>
				<?php
			}	
			?>
		</select>
	</div>
	<?php
	if (count($aFex) > 0) {
		?>
		<div class="row">
			<div class="alert alert-success">
				<strong>Bisher eingetragene Punkte:<br></strong>
			<?php
			foreach($aFex as $k=>$v) {
				?>
				<?= $v['etappen_nr'];?>.Etappe: <?= $v['einsatz_creditpool'];?> Punkte!<br>
				<?php
			}	
			?>
			</div>
		</div>
		<?php
	}			
	?>
	<div class="row well">
		<label for="fex">Anzahl Punkte</label>
		<select id="punkte" class="form-control">
			<?php
			foreach($aOptions as $k=>$v) {
				?>
				<option value="<?=$v;?>"><?=$v;?></option>
				<?php
			}	
			?>
		</select>
		<input type="hidden" id="etappe" value="<?= $iEtappe;?>">
		<button class="btn btn-default" id="submit" style="margin-top:5px">Fexpunkt eintragen</button>
	</div>
</div>