<div class="container admin">

	<div class="row">
		<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-info-sign"></i>
						<span class="caption-subject text-uppercase"> Alle Daten von <?= $aUser['name'];?> in Tabellenform</span>
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
							<a href="#leadertrikots" data-toggle="tab">
							Leadertrikots</a>
						</li>
					</ul>
					<div class="actions">
						<a href="<?= base_url();?>historie/timeline/<?= $iUser;?>" class="btn">
							<i class="glyphicon glyphicon-menu-left"></i>
							Zurück zur Timeline 
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
										<th>Team</th>
										<th>Gesamtklassierung</th>
										<th>Bergwertung</th>
										<th>Punktewertung</th>
										<th>Teamklassierung</th>
					
									</thead>
									<tbody>
									<?php
										foreach($aHistory as $k=>$v) {
											?>
											<tr>
												<td><?= $v['bezeichnung'] . ' ' . $v['jahr'];?></td>
												<td><?= $v['rzteam_name'];?></td>
												<td><?= $v['rang_gw'];?></td>
												<td><?= $v['rang_berg'];?></td>
												<td><?= $v['rang_punkte'];?></td>
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
								<table class="table table-striped">
									<thead>
										<th>Etappe</th>				
									</thead>
									<tbody>
									<?php
										foreach($aEs as $k=>$v) {
											?>
											<tr>
												<td><?= $v['etappen_nr'] . '.Etappe ' . $v['bezeichnung'] . ' ' . $v['jahr'];?></td>
											</tr>
											<?php
										}	
									?>
									</tbody>
								</table>
							</div>

						</div>
						<div class="tab-pane" id="leadertrikots">
							<div class="table-responsive history_table">
								<table class="table table-striped">
									<thead>
										<th>Trikot</th>
										<th>Etappe</th>			
									</thead>
									<tbody>
									<?php
										foreach($aLeader as $k=>$v) {
											if ($v['type'] == 1) {
												$sColor = 'warning';
												$sType = 'Leadertrikot';
											} else if ($v['type'] == 2) {
												$sColor = 'success';
												$sType = 'Punktetrikot';
											}
											else if ($v['type'] == 3) {
												$sColor = 'danger';
												$sType = 'Bergtrikot';
											}
											?>
											<tr class="<?= $sColor;?>">
												<td><?= $sType;?></td>
												<td><?= $v['etappen_nr'] . '.Etappe ' . $v['bezeichnung'] . ' ' . $v['jahr'];?></td>
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