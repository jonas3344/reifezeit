<div class="container admin">
	<div class="row well">
		<h1>Dein Kader</h1>
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
	<div class="row">
		<div class="alert alert-info lead">
			<?= $aEtappe['etappen_nr'];?>.Etappe: <?= $aEtappe['klassifizierung_name'] . ' - ' . $aEtappe['etappen_start_ziel'] . '(' . $aEtappe['etappen_distanz'] . ' km)';?><br><br>
			<strong>Eingabeschluss: <?= $aEtappe['etappen_datum'] . ' - ' . $aEtappe['etappen_eingabeschluss'];?></strong><br><br>
			Du hast heute <strong><?= $iCredits;?> Credits</strong> zur Verfügung!
		</div>
	</div>
	<div class="row well">
		<a href="<?= base_url();?>kader/kaderuebersicht"><button class="btn btn-default">Zur Kaderübersicht</button></a>
		<?php
		if 	($aKader['gewonnene_bonuscredits'] > 0 && $bEdit == true) {
		?>
			<a href="<?= base_url();?>kader/creditAbgabe"><button class="btn btn-default">Bonuscredit abgeben</button></a>
		<?php 
		}
		?>
		<?php
		if ($aUser['rolle_id'] == 3 || $aUser['rolle_id'] == 6) {
		?>
			<a href="<?= base_url();?>kader/eintragFex/<?= $iEtappe;?>"><button class="btn btn-default">Fexen</button></a>
		<?php 
		}
		?>
		
	</div>
	<?php
		if ($aKader['gewonnene_bonuscredits'] > 0) {
			?>
			<div class="row">
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Du hast gestern <?= $aKader['gewonnene_bonuscredits'];?> Bonuscredits gewonnen!
				</div>
			</div>
			<?php
		}
	?>
	<?php
		if (count($aAbgabe) > 0) {
			?>
			<div class="row">
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<?php
						foreach($aAbgabe as $k=>$v) {
							echo 'Du hast einen Bonuscredit an ' . $v['rzname'] . ' abgegeben!<br>';							
						}
					?>
				</div>
			</div>
			<?php
		}
	?>
		<?php
		if (count($aAnnahme) > 0) {
			?>
			<div class="row">
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<?php
						foreach($aAnnahme as $k=>$v) {
							echo 'Du hast einen Bonuscredit von ' . $v['rzname'] . ' erhalten!<br>';							
						}
					?>
				</div>
			</div>
			<?php
		}
	?>
	<div class="row well">
		<div class="table-responsive">
			<span class="etappen_id" id="<?= $aEtappe['etappen_id'];?>">
			<table class="table table-striped vertical-align">
				<?php
				$iSum = 0;
				$i = 1;
				foreach($aKader['aFahrer'] as $k=>$v) {
					$sPicture = ($v['fahrer_rundfahrt_credits'] > 0) ? $v['fahrer_rundfahrt_credits'] . '.png' : '0.png';
					$iSum += $v['fahrer_rundfahrt_credits'];
					?>
					<tr>
						<td align="left" width="10%"><img src="<?= base_url();?>img/credits/<?= $sPicture?>" class="credits_img"></td>
						<td width="5%"><?php
							if (in_array($v['fahrer_id'], $aWechsel)) {
								?><span class="glyphicon glyphicon-sort" aria-hidden="true"></span>
								<?php
							}
							?>
						</td>
						<td width="70%"> <span class="fahrer<?= $i;?>" id="<?= $v['fahrer_id'];?>">#<?= $v['fahrer_startnummer'] . ' - ' . $v['fahrer_vorname'] . ' ' . $v['fahrer_name'] . ' - ' . $v['team_name']?></span></td>
						<td><?php
							if ($bEdit == true) { ?>
							<button class="btn btn-default change" id="fahrer<?= $i;?>_1"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span></button>
							<button class="btn btn-default change" id="fahrer<?= $i;?>_2"><span class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span></button>
							<?php
							}
							?>	
						</td>
					</tr>
					<?php
					$i++;
				}	
				?>
				<tr>
					<td colspan="6" align="left"><img src="<?= base_url();?>img/credits/<?= $iSum?>.png" class="credits_img"></td>
				</tr>
			</table>
		</div>
	</div>
</div>