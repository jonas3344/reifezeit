<div class="container admin">
	<div class="row well">
		<h1>Fahrer hinzufügen - <?= $aTeam['team_name'];?></h1>
		<span class="team_id" id="<?= $aTeam['team_id'];?>"></span>
	</div>
	<div class="row well">
		<table class="table">
			<?php
			foreach($aFahrer as $k=>$aF) {
				?>				
				<tr>
					<td width="10%"><a href="#"><button type="button" class="btn btn-default btn-sm add_fahrer" id="<?= $aF['fahrer_id'];?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button></a></td>
					<td><?= $aF['fahrer_vorname'] . ' ' . $aF['fahrer_name'];?></td>
				</tr>
				<?php
			}	
			?>
		</table>
	</div>
</div>