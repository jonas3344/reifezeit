<div class="container admin">
	<div class="row well">
		<h1>Etappe bearbeiten</h1>
	</div>
	<div class="row well">
		<table class="table table-striped">
			<tr>
				<td style="width:20%">Etappen-Nr:</td>
				<td><a href="#" id="etappen_nr" class="etappen_nr" data-type="text" data-pk="<?= $aEtappe['etappen_id'];?>" data-url="<?= base_url();?>admin/stammdaten/setEtappenDetails/" data-title="Set Nummer"><?= $aEtappe['etappen_nr'];?></a></td>
			</tr>
			<tr>
				<td style="width:20%">Etappen-Datum:</td>
				<td><a href="#" id="etappen_datum" class="etappen_datum" data-type="text" data-pk="<?= $aEtappe['etappen_id'];?>" data-url="<?= base_url();?>admin/stammdaten/setEtappenDetails/" data-title="Set Datum"><?= $aEtappe['etappen_datum'];?></a></td>
			</tr>
			<tr>
				<td style="width:20%">Etappen-Start-Ziel:</td>
				<td><a href="#" id="etappen_start_ziel" class="etappen_start_ziel" data-type="text" data-pk="<?= $aEtappe['etappen_id'];?>" data-url="<?= base_url();?>admin/stammdaten/setEtappenDetails/" data-title="Set Start-Ziel"><?= $aEtappe['etappen_start_ziel'];?></a></td>
			</tr>
			<tr>
				<td style="width:20%">Etappen-Distanz:</td>
				<td><a href="#" id="etappen_distanz" class="etappen_distanz" data-type="text" data-pk="<?= $aEtappe['etappen_id'];?>" data-url="<?= base_url();?>admin/stammdaten/setEtappenDetails/" data-title="Set Etappen-Distanz"><?= $aEtappe['etappen_distanz'];?></a></td>
			</tr>
			<tr>
				<td style="width:20%">Etappen-Profil:</td>
				<td><a href="#" id="etappen_profil" class="etappen_profil" data-type="text" data-pk="<?= $aEtappe['etappen_id'];?>" data-url="<?= base_url();?>admin/stammdaten/setEtappenDetails/" data-title="Set Etappen-Profil"><?= $aEtappe['etappen_profil'];?></a></td>
			</tr>
			<tr>
				<td style="width:20%">Etappen-Eingabeschluss:</td>
				<td><a href="#" id="etappen_eingabeschluss" class="etappen_eingabeschluss" data-type="text" data-pk="<?= $aEtappe['etappen_id'];?>" data-url="<?= base_url();?>admin/stammdaten/setEtappenDetails/" data-title="Set Etappen-Eingabeschluss"><?= $aEtappe['etappen_eingabeschluss'];?></a></td>
			</tr>
			<tr>
				<td style="width:20%">Etappen-Klassifizierung:</td>
				<td>
					<select class="form-control" id="<?= $aEtappe['etappen_id'];?>" name="ek">
						<?php
							foreach($aEk as $k=>$v) {
								$selected = ($aEtappe['etappen_klassifizierung'] == $k) ? ' selected' : '';
							?>		
								<option value="<?= $k;?>" <?= $selected;?>><?= $v;?></option>	
							<?php
							}
						?>

					</select>
				</td>
			</tr>
			
		</table>
	</div>
	
</div>