<div class="container admin">
	<div class="row well">
		<h1>Dopingtest</h1>
	</div>
	<div class="row well">
		<select id="etappen" class="form-control">
			<?php
			foreach($aEtappen as $k=>$v) {
				$selected = ($v['etappen_id'] == $iAktuelleEtappe) ? ' selected' : '';
				?>
				<option value="<?= $v['etappen_id'];?>" <?= $selected;?>><?= $v['etappen_nr'];?>. Etappe</option>
				<?php
			}
			?>
		</select>
	</div>
	<div class="row well">
		<a href="<?= base_url();?>admin/dopingtest/forumtabelle/<?= $iAktuelleEtappe;?>"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Forumtabelle</button></a>
		<a href="<?= base_url();?>admin/dopingtest/doper/<?= $iAktuelleEtappe;?>"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span> Doper eintragen</button></a>
	</div>
	<div class="row well">
		<table class="table small">
			<thead>
				<th>User</th>
				<th>Fahrer 1</th>
				<th></th>
				<th>Fahrer 2</th>
				<th></th>
				<th>Fahrer 3</th>
				<th></th>
				<th>Fahrer 4</th>
				<th></th>
				<th>Fahrer 5</th>
				<th></th>
				<th>BC</th>
				<th>Fex</th>
				<th>CA</th>
				<th>Summe</th>
				<th></th>
			</thead>
			<tbody>
				<?php
					foreach($aDopingtest['teilnehmer'] as $k=>$v) {
					?>
					<tr>
						<td <?= ($v['iChanges'] > 1) ? 'class="red"' : '';?>><?= $v['name'] . '<br>' . '<small>' . $v['rzname'] . '</small>';?></td>
						<?php
						foreach($v['kader'] as $kk=>$aKader) {
							?>
							<td <?= ($aKader['change'] == true) ? 'class="info"' : ''?>><?= substr($aKader['fahrer_vorname'], 0, 1) . '.' . $aKader['fahrer_name'];?><br><small><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> <?= substr($v['kaderOld'][$kk]['fahrer_vorname'], 0, 1) . '.' . $v['kaderOld'][$kk]['fahrer_name'];?></small></td>
							<td style="border-right: 1px solid"><?= $aKader['fahrer_rundfahrt_credits'];?><br><small><?= $v['kaderOld'][$kk]['fahrer_rundfahrt_credits'];?></small></td>
							<?php
						}	
						?>
						<td style="border-right: 1px solid"><?= $v['gewonnene_bonuscredits'];?></td>
						<td style="border-right: 1px solid"><?= $v['einsatz_creditpool'];?></td>
						<td style="border-right: 1px solid"><?= $v['ca'];?></td>
						<td <?= ($v['doped'] == true) ? 'class="red"' : '';?>><?= $v['iUsedCredits'] . '(' . $v['iCreditBase'] . ')';?></td>
						<td width="8%">
							<div class="dropdown">
							  <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							    <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
							    <span class="caret"></span>
							  </button>
							  <ul class="dropdown-menu" aria-labelledby="dLabel">
							    <li><a href="<?= base_url();?>admin/dopingtest/freeChange/<?=$v['id'];?>/<?= $iAktuelleEtappe;?>">Freies Verändern</a></li>
							    <li><a href="#" class="setKaderLastStage" id="<?= $v['id'] . '_' . $iAktuelleEtappe;?>">Auf gestrigen Kader zurücksetzen</a></li>
							  </ul>
							</td>
					</tr>
					<?php	
					}
					?>
			</tbody>
		</table>
	</div>
	
</div>