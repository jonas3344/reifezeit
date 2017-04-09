<div class="container admin">
	<div class="row well">
		<h1>Team bearbeiten</h1>
	</div>
	<div class="row well">
		<table class="table table-striped">
			<tr>
				<td width="20%">Teamname:</td>
				<td><a href="#" class="teamname" data-type="text" data-pk="teamname" data-url="<?= base_url();?>admin/stammdaten/setTeamName/<?= $aTeam['team_id'];?>" data-title="Set Teamname"><?= $aTeam['team_name'];?></a></td>
			</tr>
			<tr>
				<td width="20%">Teamkürzel:</td>
				<td><a href="#" class="teamshort" data-type="text" data-pk="teamshort" data-url="<?= base_url();?>admin/stammdaten/setTeamShort/<?= $aTeam['team_id'];?>" data-title="Set Teamkürzel"><?= $aTeam['team_short'];?></a></td>
			</tr>
			<tr>
				<td width="20%">Anzahl Fahrer:</td>
				<td><?= count($aRiders);?></td>
			</tr>
		</table>
	</div>
	<div class="row well">
		<table class="table table-striped">
			<thead>
				<th>Fahrername</th>
				<th>Aktion</th>	
			</thead>
			<tbody>
				<?php
				foreach($aRiders as $k=>$v) {
					?>
					<tr>
						<td><?= $v['fahrer_vorname'] . ' ' . $v['fahrer_name'];?></td>
						<td><a href="<?= base_url();?>admin/stammdaten/change_team/<?= $v['fahrer_id'];?>"><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Teamwechsel</button></a> <button type="button" class="btn btn-danger delete" id="<?= $v['fahrer_id'];?>"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Delete</button></td>
					</tr>
					<?php
				}	
				?>
			</tbody>
		</table>
	</div>
</div>