<div class="container admin">
	<div class="row well">
		<h1>Dein Kader</h1>
	</div>
	<div class="row well">
		<div class="col-md-1 text-center">
			<button class="btn btn-default" id="backwards"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span></button>
		</div>
		<div class="col-md-10">
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
		<div class="col-md-1 text-center">
			<button class="btn btn-default" id="forward"><span class="glyphicon glyphicon-forward" aria-hidden="true"></span></button>
		</div>
	</div>
	<div class="row">
		<div class="alert alert-info lead">
            <?php
            if (substr($aEtappe['etappen_profil'], 0, 4) === 'http') {
                $etappenprofil = $aEtappe['etappen_profil'];
            } else {
                $etappenprofil = base_url() . 'img/' . $aEtappe['etappen_profil'];
            }
            ?>
			<?= $aEtappe['etappen_nr'];?>.Etappe: <?= $aEtappe['klassifizierung_name'] . ' - ' . $aEtappe['etappen_start_ziel'] . '(' . $aEtappe['etappen_distanz'] . ' km)';?> <a rel="popover" class="btn btn-default" data-img="<?= $etappenprofil;?>"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></a><br><br>
			<strong>Eingabeschluss: <?= $aEtappe['etappen_datum'] . ' - ' . $aEtappe['etappen_eingabeschluss'];?></strong><br><br>
			Du hast heute <strong><?= $iCredits;?> Credits</strong> zur Verfügung!
		</div>
	</div>
	<div class="row well">
		<a href="<?= base_url();?>kader/kaderuebersicht"><button class="btn btn-default">Zur Kaderübersicht</button></a>
		<?php
		if ($bEdit == true) {
			?>
			<button class="btn btn-default resetKader" id="<?=$aEtappe['etappen_id'];?>">Auf Kader des Vortages zurücksetzen</button>
			<?php
		}
		?>
		<?php
		if 	($aKader['gewonnene_bonuscredits'] > 0 && $bEdit == true && ($aEtappe['klassifizierung_id'] != 3 && $aEtappe['klassifizierung_id'] != 5)) {
		?>
			<a href="<?= base_url();?>kader/creditAbgabe/<?= $iEtappe;?>"><button class="btn btn-default">Bonuscredit abgeben</button></a>
		<?php 
		}
		?>
		<?php
		if (($aUser['rolle_id'] == 3 || $aUser['rolle_id'] == 6) && ($bEdit == true)) {
		?>
			<a href="<?= base_url();?>kader/eintragFex/<?= $iEtappe;?>"><button class="btn btn-default">Fexen</button></a>
		<?php 
		}
		if ($iBase >= 31 && $aUser['creditmoves'] > 0 && $bEdit == true) {
		?>
			<a href="<?= base_url();?>kader/moveCredit/<?= $iEtappe;?>"><button class="btn btn-default">Schonen</button></a>
		<?php 
		}
		if ($aUser['rolle_id'] == 1 && ($aEtappe['etappen_klassifizierung'] == 2 || $aEtappe['etappen_klassifizierung'] == 4) && $bEdit == true) {
		?>
			<a href="<?= base_url();?>kader/machtwechsel/<?= $iEtappe;?>"><button class="btn btn-default">Machtwechsel</button></a>
		<?php 
		}
		if ($aUser['rolle_id'] == 7 && $aEtappe['etappen_klassifizierung'] == 1  && $bEdit == true) {
		?>
			<a href="<?= base_url();?>kader/anziehen/<?= $iEtappe;?>"><button class="btn btn-default">Anziehen</button></a>
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
							if ($bEdit == true) {
								?>
								<button class="btn btn-default removeBc" id="<?= $v['creditabgabe_id'];?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
								<?php
							}
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
	if ($aKader['einsatz_creditpool'] > 0) {
		?>
		<div class="row">
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<?php
					if ($bEdit == true) {
					?>
						<button class="btn btn-default removeFex" id="<?= $aEtappe['etappen_id'];?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
						<?php
					}
					?>
					Du hast auf dieser Etappe <?= $aKader['einsatz_creditpool'];?> Fexpunkte eingesetzt!
				</div>
			</div>

		<?php
	}
		
	if ($aKader['creditmoves'] == -1) {
		?>
			<div class="row">
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Du hast einen Machtwechsel vorgenommen. Du kannst heute einen Credit weniger verwenden.
				</div>
			</div>
			<?php
		
	}	
	if ($aKader['creditmoves'] == -2) {
		?>
			<div class="row">
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Du hast Credits von dieser auf die nächste Etappe verschoben. Das heisst du kannst heute 2 Credits weniger einsetzen.
				</div>
			</div>
			<?php
		}
		if ($aKader['creditmoves'] > 0) {
		?>
			<div class="row">
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					Du hast Credits von der letzten auf diese Etappe verschoben. Das heisst du kannst heute 1 Credit mehr einsetzen.
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
					$sColor = ($v['ausgeschieden'] == 1) ? ' class="danger"' : '';
					$sTextOut = ($v['ausgeschieden'] == 1) ? '<span style="color:red; font-weight: bold">OUT</span>' : '';
					$sPicture = ($v['fahrer_rundfahrt_credits'] > 0) ? $v['fahrer_rundfahrt_credits'] . '.png' : '0.png';
					$iSum += $v['fahrer_rundfahrt_credits'];
					?>
					<tr<?= $sColor;?>>
						<td align="left" width="10%"><img src="<?= base_url();?>img/credits/<?= $sPicture?>" class="credits_img"></td>
						<td width="5%"><?php
							if (in_array($v['fahrer_id'], $aWechsel)) {
								?><span class="glyphicon glyphicon-sort" aria-hidden="true"></span>
								<?php
							}
							?>
						</td>
						<td width="70%"> <span class="fahrer<?= $i;?>" id="<?= $v['fahrer_id'];?>">#<?= $v['fahrer_startnummer'] . ' - ' . $v['fahrer_vorname'] . ' ' . $v['fahrer_name'] . ' - ' . $v['team_name'] . ' ' . $sTextOut;?></span></td>
						<td><?php
							if ($bEdit == true) { ?>
							<div class="dropdown">
							<button class="btn btn-default change" id="fahrer<?= $i;?>_1_0"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span></button>
							<button class="btn btn-default change" id="fahrer<?= $i;?>_2_0"><span class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span></button>
							
								<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default">
								     <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
								</button>
								  <ul class="dropdown-menu" aria-labelledby="dLabel">
								  		<?php
										foreach($aShortlists as $k=>$v) {
											?>
												<li><a href="#" class="change" id="fahrer<?= $i . '_3_' . $v['id'];?>"><?= $v['name'];?></a></li>
										  <?php
										}
										?>	
								  </ul>
							</div>
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