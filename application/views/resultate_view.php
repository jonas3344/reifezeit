<div class="container admin">
	<div class="row well">
		<h1>Resultate</h1>
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
	<div class="row well">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#resultat" aria-controls="home" role="tab" data-toggle="tab">Resultat</a></li>
		    <li role="presentation"><a href="#punkte" aria-controls="profile" role="tab" data-toggle="tab">Punkte</a></li>
		    <li role="presentation"><a href="#berg" aria-controls="messages" role="tab" data-toggle="tab">Berg</a></li>
		</ul>
		  
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="resultat">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<th width="5%">Rang</th>
							<th>Fahrer</th>
							<th>Team</th>
							<th>Nation</th>
							<th>Rückstand</th>
							<th>Rückstand RZ</th>
						</thead>
						<tbody>
							<?php
							foreach($aResultate['aEtappe'] as $r) {
							?>
								<tr>
									<td><?= $r['rang'];?></td>
									<td><?= $r['fahrer_name'] . " " . $r['fahrer_vorname'];?></td>
									<td><?= $r['team_name'];?></td>
									<td><img src="<?= base_url();?>img/flags/<?=strtolower($r['fahrer_nation'])?>.png" width="20"></td>
									<td><?= _convertSeconds($r['rueckstandOhneBS'])?></td>
									<td><?= _convertSeconds($r['rueckstand'])?></td>
								</tr>
							<?php
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="punkte">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<th width="5%">Rang</th>
							<th>Fahrer</th>
							<th>Team</th>
							<th>Nation</th>
							<th>Punkte</th>
						</thead>
						<tbody>
							<?php
								$i=1;
							foreach($aResultate['aPunkte'] as $r) {
							?>
								<tr>
									<td><?= $i;?></td>
									<td><?= $r['fahrer_name'] . " " . $r['fahrer_vorname'];?></td>
									<td><?= $r['team_name'];?></td>
									<td><img src="<?= base_url();?>img/flags/<?=strtolower($r['fahrer_nation'])?>.png" width="20"></td>
									<td><?= $r['punkte']?></td>
								</tr>
							<?php
								$i++;
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="berg">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<th width="5%">Rang</th>
							<th>Fahrer</th>
							<th>Team</th>
							<th>Nation</th>
							<th>Punkte</th>
						</thead>
						<tbody>
							<?php
								$i=1;
							foreach($aResultate['aBerg'] as $r) {
							?>
								<tr>
									<td><?= $i;?></td>
									<td><?= $r['fahrer_name'] . " " . $r['fahrer_vorname'];?></td>
									<td><?= $r['team_name'];?></td>
									<td><img src="<?= base_url();?>img/flags/<?=strtolower($r['fahrer_nation'])?>.png" width="20"></td>
									<td><?= $r['bergpunkte']?></td>
								</tr>
							<?php
								$i++;
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>