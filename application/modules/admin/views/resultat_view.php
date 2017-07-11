		<div class="container admin">
			<div class="row well">
				<h1>Resultate</h1>
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
				<h2>Resultat Etappe</h2>
				<table class="table table-striped">
					<thead>
						<th width="5%">Rang</th>
						<th>Teilnehmer</th>
						<th>Team</th>
						<th>Status</th>
						<th>Zeit</th>
						<th width="10%"></th>
					</thead>
					<tbody>
						<?php foreach($stage_result as $k=>$v) {
							if ($teilnehmer[$k]['out'] == 1 && $teilnehmer[$k]['out_etappen_id'] == $iEtappe) {
								$bAlreadyOut = true;
								$sStyle = ' class="danger"';
							} else {
								$bAlreadyOut = false;
								$sStyle = '';
							}
							?>
							<tr<?=$sStyle;?>>
								<td><?= $v['rang'];?></td>
								<td><?= $teilnehmer[$k]['rzname'];?></td>
								<td><?= $teilnehmer[$k]['rzteam_short'];?></td>
								<td><?= $teilnehmer[$k]['rolle_bezeichnung'];?></td>
								<td><?= _convertSeconds($v['zeit']);?></td>
								<td>
									<?php
									if ($v['zeit'] > 3900 && !$bAlreadyOut) {
										?>
										<button class="btn btn-primary btn-xs otl" id="<?= $k . '_' . $iEtappe;?>">OTL</button>
										<?php
									}
									?>
								</td>
							</tr>
							<?php	
						}
						?>
					</tbody>
				</table>
				
			</div>
		</div>