<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> Resultate <?= $aEtappen[$iEtappe]['etappen_nr'];?>.Etappe</span>
				</div>
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#resultat" data-toggle="tab">
						Resultat</a>
					</li>
					<li>
						<a href="#punkte" data-toggle="tab">
						Punkte</a>
					</li>
					<li>
						<a href="#berg" data-toggle="tab">
						Bergpunkte</a>
					</li>
				</ul>
				<div class="actions">
					<?php
						if ($aEtappen[$iEtappe]['etappen_nr'] > 1) {
							?>
							<a class="btn prev" id="<?= $aEtappen[$iEtappe]['etappen_nr'] - 1;?>">
								<i class="glyphicon glyphicon-menu-left"></i>
							</a>
							<?php
						}
						if ($aEtappen[$iEtappe]['etappen_nr'] < 21) {
						?>
							<a class="btn next" id="<?= $aEtappen[$iEtappe]['etappen_nr'] + 1;?>">
								<i class="glyphicon glyphicon-menu-right"></i>
							</a>
					<?php
						}
						?>
				</div>
			</div>
			<div class="portlet-body">
				<div class="tab-content">
					<div class="tab-pane active" id="resultat">
						<div class="table-responsive history_table">
							<table class="table table-striped">
								<thead>
									<th width="5%">Rang</th>
									<th width="30%">Fahrer</th>
									<th width="30%">Team</th>
									<th width="5%">Nation</th>
									<th width="15%">Rückstand</th>
									<th width="15%">Rückstand RZ</th>
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

					<div class="tab-pane" id="punkte">
						<div class="table-responsive history_table">
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
					<div class="tab-pane" id="berg">
						<div class="table-responsive history_table">
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
	</div>
</div>