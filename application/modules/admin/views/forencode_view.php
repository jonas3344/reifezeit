<div class="container admin">
	<div class="row well">
		<h1>Forencode</h1>
	</div>
	<div class="row well">
		<select class="form-control" id="etappen">
			<?php foreach($aAlleEtappen as $k=>$v) {
				$sSel = ($iEtappe == $v['etappen_id']) ? ' selected' : '';
				?>
				<option value="<?= $v['etappen_id'];?>"<?= $sSel;?>><?= $v['etappen_nr'];?>.Etappe</option>
				<?php	
			}
			?>
		</select>
	</div>
	<div class="row well">
		<button class="btn btn-default" data-clipboard-target="#tw"><span class="glyphicon glyphicon-copy" aria-hidden="true"></span> Tageswertung</button>
		<button class="btn btn-default" data-clipboard-target="#gw"><span class="glyphicon glyphicon-copy" aria-hidden="true"></span> Gesamtwertung</button>	
	</div>
	<div class="row well">
		<h2 class="sub-header">Tageswertung</h2>
		<textarea cols="150" rows="5" class="form-control" id="tw"><?= $sOutputDay;?></textarea>
		<h2 class="sub-header">Gesamtwertung</h2>
		<textarea cols="150" rows="5" class="form-control" id="gw"><?= $sOutputGesamt;?></textarea>
	</div>
</div>