<div class="container admin">
	<div class="row well">
		<h1>Team updaten - Fahrer parsen</h1>
		<h2><?= $aTeam['team_name'];?>
	</div>
	
	<div class="row well">
		<input type="hidden" id="team" value="<?= $aTeam['team_id'];?>">
		<table class="table table-striped">
			<thead>
				<th>Vorname</th>
				<th>Name</th>
				<th>Nation</th>
				<th>Aktion</th>
			</thead>
			<tbody>
				<?php
				foreach($aFahrer as $k=>$v) {
					$sColor = ($v['inDb'] == 1) ? 'success' : 'danger';
					?>
					<tr class="<?= $sColor;?>" id="<?=$k;?>_row">
						<td id="<?= $k;?>_vorname"><?= $v['fahrer_vorname'];?></td>
						<td id="<?= $k;?>_name"><?=  $v['fahrer_name'];?></td>
						<td id="<?= $k;?>_nation"><?= $v['fahrer_nation'];?></td>
						<td><?php
							if ($v['inDb'] == 0) {
							?>
								<button type="button" class="btn btn-default plus" id="<?=$k;?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
							<?php	
							}
							?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>