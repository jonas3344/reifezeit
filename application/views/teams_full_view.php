<div class="container admin">
	<div class="row">
		<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-info-sign"></i>
						<span class="caption-subject text-uppercase"> Alle Daten von <?= $aTeam['rzteam_name'];?> in Tabellenform</span>
					</div>
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#teilnahmen" data-toggle="tab">
							Teilnahmen</a>
						</li>
						<li>
							<a href="#etappensiege" data-toggle="tab">
							Etappensiege</a>
						</li>
						<li>
							<a href="#etappensiege_einzeln" data-toggle="tab">
							Etappensiege Einzeln</a>
						</li>
					</ul>
					<div class="actions">
						<a href="<?= base_url();?>historie/teams/<?= $iTeam;?>" class="btn">
							<i class="glyphicon glyphicon-menu-left"></i>
							Zurück zum Team 
						</a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="tab-content">
						<div class="tab-pane active" id="teilnahmen">
							<div class="table-responsive history_table">
								<table class="table table-striped">
									<thead>
										<th>Rundfahrt</th>
										<th>Fahrer</th>
										<th>Gesamtwertung</th>
					
									</thead>
									<tbody>
									<?php
										foreach($aHistory as $k=>$v) {
											?>
											<tr>
												<td><a href="<?= base_url();?>historie/rundfahrt/<?= $v['rundfahrt_id'];?>"><?= $v['bezeichnung'] . ' ' . $v['jahr'];?></a></td>
												<td><div class="team_fahrer"><ul><?php
													foreach($v['aFahrer'] as $kF=>$vF) {
														?>
														<li><a href="<?= base_url();?>historie/timeline/<?= $vF['id'];?>"><?= $vF['name'];?></a></li>
														<?php
													}
													
													?></ul></div></td>
												<td><?= $v['rang'];?></td>
											</tr>
											<?php
										}	
									?>
									</tbody>
								</table>

							</div>
						</div>
						<div class="tab-pane" id="etappensiege">
							<div class="table-responsive history_table">
								<table class="table">
									<thead>
										<th>Etappe</th>				
									</thead>
									<tbody>
									<?php
										foreach($aEs as $k=>$v) {
											$sColor = '';
											switch($v['klassifizierung_id']) {
												case 1:
													$sColor = ' class = success';
													break;
												case 2:
													break;
												case 3:
													$sColor = ' class = warning'; 
													break;
												case 4:
													$sColor = ' class = danger';
													break;
												case 5:
													$sColor = ' class = warning';
													break;
												case 6:
													$sColor = ' class = warning';
													break;
												case 7:
													$sColor = ' class = primary';
													break;
											}
											?>
											<tr<?= $sColor?>>
												<td><?= $v['etappen_nr'] . '.Etappe ' . $v['bezeichnung'] . ' ' . $v['jahr'] . ' - ' . $v['klassifizierung_name'];?></td>
											</tr>
											<?php
										}	
									?>
									</tbody>
								</table>
							</div>

						</div>
						<div class="tab-pane" id="etappensiege_einzeln">
							<div class="table-responsive history_table">
								<table class="table">
									<thead>
										<th width="20%">Fahrer</th>	
										<th>Etappe</th>
													
									</thead>
									<tbody>
									<?php
										foreach($aEsEinzeln as $k=>$v) {
											$sColor = '';
											switch($v['klassifizierung_id']) {
												case 1:
													$sColor = ' class = success';
													break;
												case 2:
													break;
												case 3:
													$sColor = ' class = warning'; 
													break;
												case 4:
													$sColor = ' class = danger';
													break;
												case 5:
													$sColor = ' class = warning';
													break;
												case 6:
													$sColor = ' class = warning';
													break;
												case 7:
													$sColor = ' class = primary';
													break;
											}
											?>
											<tr<?= $sColor?>>
												<td><a href="<?= base_url();?>historie/timeline/<?= $v['id'];?>"><?= $v['name'];?></a></td>
												<td><?= $v['etappen_nr'] . '.Etappe ' . $v['bezeichnung'] . ' ' . $v['jahr'] . ' - ' . $v['klassifizierung_name'];?></td>
												
											</tr>
											<?php
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