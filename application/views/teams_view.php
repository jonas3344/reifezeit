<div class="container admin">

	<div class="row">
		<div class="col-md-12">
        	<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-info-sign"></i>
						<span class="caption-subject text-uppercase"> Team: <?= $aTeam['rzteam_name'];?></span>
					</div>
					<div class="actions">
						<a href="<?= base_url();?>historie/teamFull/<?= $aTeam['rzteam_id'];?>" class="btn">
							<i class="glyphicon glyphicon-eye-open"></i>
							Alle Daten 
						</a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="text-center">
						<div class="team_badge" style="background-color: <?= $aTeam['color_code_zelle'];?>; color: <?= $aTeam['color_code_schrift'];?>">
							<?= $aTeam['rzteam_short'];?>
						</div>
					</div>
					<table class="table">
						<tr>
							<td width="25%">Erste Teilnahme:</td>
							<td><?= $aHistory[0]['bezeichnung'] . ' ' . $aHistory[0]['jahr'];?></td>
						</tr>
						<tr>
							<td>Anzahl Teilnahmen:</td>
							<td><?= count($aHistory);?></td>
						</tr>
						<tr>
							<td>Gesamtsiege Team:</td>
							<td><?= count($aSuccess['aTeamGesamt']);?></td>
						</tr>
						<tr>
							<td>Etappensiege Team:</td>
							<td><?= count($aEs);?></td>
						</tr>
						<tr>
							<td>Gesamtsiege Einzel:</td>
							<td><?= count($aSuccess['aEinzelGesamt']);?></td>
						</tr>
						<tr>
							<td>Punktwertung Einzel:</td>
							<td><?= count($aSuccess['aEinzelPunkte']);?></td>
						</tr>
						<tr>
							<td>Bergwertung Einzel:</td>
							<td><?= count($aSuccess['aEinzelBerg']);?></td>
						</tr>
						<tr>
							<td>Etappensiege Einzel:</td>
							<td><?= count($aEsEinzeln);?></td>
						</tr>
						<tr>
							<td>Teamfahrer:</td>
							<td><ul class="teamlist" style="padding-left: 14px;">
								<?php 
								foreach($aFahrer as $k=>$v) {
									?>
									<li><a href="<?= base_url();?>historie/timeline/<?= $v['id'];?>"><?= $v['name'];?></a> (<?= ($v['anzahl'] == 1) ? '1 Einsatz' : $v['anzahl'] . ' Einsätze';?>)</li>
									<?php
								}?>

							</ul></td>
						</tr>
				</div>
			</div>

				
		</div>

		<div class="col-md-6">

			
        </div>
	</div>
</div>