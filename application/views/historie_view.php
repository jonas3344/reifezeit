<div class="container admin">
	<div class="row well">
		<h1>Deine Geschichte in der Reifenzeit!</h1>
	</div>
	<div class="row well">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<th>Rundfahrt</th>
					<th>Rolle</th>
					<th>Team</th>
					<th>Gesamtklassierung</th>
					<th>Bergwertung</th>
					<th>Punktewertung</th>
				</thead>
				<tbody>
				<?php
					foreach($aHistory as $k=>$v) {
						?>
						<tr>
							<td><?= $v['rundfahrt_bezeichnung'];?></td>
							<td><?= $v['aTeilnahme']['rolle_bezeichnung'];?></td>
							<td><?= $v['aTeilnahme']['rzteam_name'];?></td>
							<td><?= $v['aTeilnahme']['endklassierung_gesamt'];?></td>
							<td><?= $v['aTeilnahme']['endklassierung_berg'];?></td>
							<td><?= $v['aTeilnahme']['endklassierung_punkte'];?></td>
						</tr>
						<?php
					}	
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>