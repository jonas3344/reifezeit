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
		<a href="#"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Forumtabelle</button></a>
		<a href="#"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span> Doper eintragen</button></a>
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
						<td <?= ($v['iChanges'] > 1) ? 'class="danger"' : '';?>><?= $v['name'] . '<br>' . '<small>' . $v['rzname'] . '</small>';?></td>
						<?php
						foreach($v['kader'] as $kk=>$aKader) {
							?>
							<td <?= ($aKader['change'] == true) ? 'class="info"' : ''?>>#<?= $aKader['fahrer_startnummer'];?> <?= substr($aKader['fahrer_vorname'], 0, 1) . '.' . $aKader['fahrer_name'];?></td>
							<td style="border-right: 1px solid"><?= $aKader['fahrer_rundfahrt_credits'];?></td>
							<?php
						}	
						?>
						<td style="border-right: 1px solid"><?= $v['gewonnene_bonuscredits'];?></td>
						<td style="border-right: 1px solid"><?= $v['einsatz_creditpool'];?></td>
						<td style="border-right: 1px solid"><?= $v['ca'];?></td>
						<td <?= ($v['doped'] == true) ? 'class="danger"' : '';?>><?= $v['iUsedCredits'] . '(' . $v['iCreditBase'] . ')';?></td>
						<td><a href="#"><button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button></a></td>
					</tr>
					<?php	
					}
					?>
			</tbody>
		</table>
	</div>
	
</div>