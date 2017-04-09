<div class="container admin">
	<div class="row well">
		<h1>Team wechsel</h1> <a href="<?= base_url();?>admin/stammdaten/edit_team/<?= $aRider['fahrer_team_id'];?>" class="btn btn-default">Zurück zum Team</a>
	</div>
	<div class="row well">
		<table class="table table-striped">
			<tr>
				<td style="width:20%">Fahrer:</td>
				<td><?= $aRider['fahrer_vorname'] . ' ' . $aRider['fahrer_name'];?></td>
			</tr>
			<tr>
				<td>Bisheriges Team:</td>
				<td><?= $aTeams[$aRider['fahrer_team_id']]['team_name'];?></td>
			</tr>
			<tr>
				<td>Neues Team:</td>
				<td><select name="team" id="<?= $aRider['fahrer_id'];?>" class="form-control">
					<?php
					foreach($aTeams as $k=>$v) {
						$selected = ($aRider['fahrer_team_id'] == $v['team_id']) ? ' selected' : '';
						?>
						<option value="<?= $k;?>" <?= $selected;?>><?= $v['team_name'];?></option>
						<?php
					}
						?>
				</select></td>
			</tr>
		</table>
		
	</div>
</div>