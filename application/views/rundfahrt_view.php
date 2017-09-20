<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> <?= $aRundfahrt['bezeichnung'] . ' ' . $aRundfahrt['jahr'];?></span>
				</div>
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#uebersicht" data-toggle="tab">
						Übersicht</a>
					</li>
					<li>
						<a href="#gw" data-toggle="tab">
						Gesamtwertung</a>
					</li>
					<li>
						<a href="#ets" data-toggle="tab">
						Etappensiege</a>
					</li>
				</ul>
			</div>
			<div class="portlet-body">
				<div class="tab-content">
					<div class="tab-pane active" id="uebersicht">
						<div class="row">
							<div class="col-md-4 text-center" style="padding-top: 50px;">
								<img src="<?= base_url();?>img/pokale/silber.png" width="128px"><hr>
								<img src="<?= base_url();?>img/avatars/<?= $aData['aSecond']['avatar'];?>" width="128px"><br>
								<h4><a href="<?= base_url();?>historie/timeline/<?= $aData['aSecond']['user_id'];?>"><?= $aData['aSecond']['name'];?></a></h4>
								<h4><a href="<?= base_url();?>historie/teams/<?= $aData['aSecond']['team_id'];?>"><?= $aData['aSecond']['rzteam_name'];?></a></h4>
							</div>
							<div class="col-md-4 text-center">
								<img src="<?= base_url();?>img/pokale/gold.png" width="128px"><hr>
								<img src="<?= base_url();?>img/avatars/<?= $aData['aFirst']['avatar'];?>" width="128px"><br>
								<h4><a href="<?= base_url();?>historie/timeline/<?= $aData['aFirst']['user_id'];?>"><?= $aData['aFirst']['name'];?></a></h4>
								<h4><a href="<?= base_url();?>historie/teams/<?= $aData['aFirst']['team_id'];?>"><?= $aData['aFirst']['rzteam_name'];?></a></h4>
							</div>
							<div class="col-md-4 text-center" style="padding-top: 100px;">
								<img src="<?= base_url();?>img/pokale/bronze.png" width="128px"><hr>
								<img src="<?= base_url();?>img/avatars/<?= $aData['aThird']['avatar'];?>" width="128px"><br>
								<h4><a href="<?= base_url();?>historie/timeline/<?= $aData['aThird']['user_id'];?>"><?= $aData['aThird']['name'];?></a></h4>
								<h4><a href="<?= base_url();?>historie/teams/<?= $aData['aThird']['team_id'];?>"><?= $aData['aThird']['rzteam_name'];?></a></h4>
							</div>
						</div>
						<div class="row">
							<hr>
						</div>
						<div class="row">
							<div class="col-md-4 text-center">
								<img src="<?= base_url();?>img/pokale/green.png" width="128px"><hr>
								<img src="<?= base_url();?>img/avatars/<?= $aData['aPoints']['avatar'];?>" width="128px"><br>
								<h4><a href="<?= base_url();?>historie/timeline/<?= $aData['aPoints']['user_id'];?>"><?= $aData['aPoints']['name'];?></a></h4>
								<h4><a href="<?= base_url();?>historie/teams/<?= $aData['aPoints']['team_id'];?>"><?= $aData['aPoints']['rzteam_name'];?></a></h4>
							</div>
							<div class="col-md-4 text-center">
								<img src="<?= base_url();?>img/pokale/berg.png" width="128px"><hr>		
								<img src="<?= base_url();?>img/avatars/<?= $aData['aBerg']['avatar'];?>" width="128px"><br>
								<h4><a href="<?= base_url();?>historie/timeline/<?= $aData['aBerg']['user_id'];?>"><?= $aData['aBerg']['name'];?></a></h4>
								<h4><a href="<?= base_url();?>historie/teams/<?= $aData['aBerg']['team_id'];?>"><?= $aData['aBerg']['rzteam_name'];?></a></h4>
							</div>
							<div class="col-md-4 text-center">
								<img src="<?= base_url();?>img/pokale/team.png" width="128px"><hr>
								<div class="team_badge_small" style="background-color: <?= $aData['aTeams'][0]['color_code_zelle'];?>; color: <?= $aData['aTeams'][0]['color_code_schrift'];?>">
									<?= $aData['aTeams'][0]['rzteam_short'];?>
								</div>
								<h4><a href="<?= base_url();?>historie/teams/<?= $aData['aTeams'][0]['team_id'];?>"><?= $aData['aTeams'][0]['rzteam_name'];?></a></h4>
							</div>
							
						</div>
					</div>
					<div class="tab-pane" id="gw">
						<div class="table-responsive history_table">
							<table class="table table-striped">
								<thead>
									<th width="20%">Fahrer</th>
									<th>Rang Gw</th>
								</thead>
								<tbody>
									<?php
									foreach($aData['aUser'] as $k=>$v) {
										?>
										<tr>
											<td><a href="<?= base_url();?>historie/timeline/<?= $v['user_id'];?>"><?= $v['name'];?></a></td>
											<td><?php
												if (substr($v['rang_gw'], 0, 3) == '999') {
													echo substr($v['rang_gw'], 3);
												} else {
													 echo $v['rang_gw'];
												} ?>
												</td>
										</tr>
										<?php
									}	
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane" id="ets">
						<div class="table-responsive history_table">
							<table class="table table-striped">
								<thead>
									<th width="5%">Etappe</th>
									<th>Etappenart</th>
									<th>Etappensieger</th>
									<th>Etappensieger Team</th>
									<th>Leader</th>
									<th>Punktetrikot</th>
									<th>Bergtrikot</th>
								</thead>
								<tbody>
									<?php
									foreach($aData['aStages'] as $k=>$v) {
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
										<tr<?= $sColor;?>>
											<td><?= $v['etappen_nr'];?></td>
											<td><?= $v['klassifizierung_name'];?></td>
											<td><a href="<?= base_url();?>historie/timeline/<?= $v['aWinner']['user_id'];?>"><?= $v['aWinner']['name'];?></a></td>
											<td><a href="<?= base_url();?>historie/teams/<?= $v['aWinnerTeam']['rzteam_id'];?>"><?= $v['aWinnerTeam']['rzteam_short'];?></a></td>
											<td><a href="<?= base_url();?>historie/timeline/<?= $v['aLeader']['user_id'];?>"><?= $v['aLeader']['name'];?></a></td>
											<td><a href="<?= base_url();?>historie/timeline/<?= $v['aPoints']['user_id'];?>"><?= $v['aPoints']['name'];?></a></td>
											<td><a href="<?= base_url();?>historie/timeline/<?= $v['aBerg']['user_id'];?>"><?= $v['aBerg']['name'];?></a></td>
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