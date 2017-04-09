<div class="container admin">
	<div class="row well">
		<h1>Fahrer bearbeiten</h1>
	</div>
	<div class="row well">
		<table class="table table-striped">
			<tr>
				<td style="width:20%">Name:</td>
				<td><a href="#" id="fahrername" class="fahrername" data-type="text" data-pk="<?= $aFahrer['fahrer_id'];?>" data-url="<?= base_url();?>admin/stammdaten/setFahrerDetails/" data-title="Set Name"><?= $aFahrer['fahrer_name'];?></a></td>
			</tr>
			<tr>
				<td>Vorname:</td>
				<td><a href="#" id="fahrervorname" class="fahrervorname" data-type="text" data-pk="<?= $aFahrer['fahrer_id'];?>" data-url="<?= base_url();?>admin/stammdaten/setFahrerDetails/" data-title="Set Vorname"><?= $aFahrer['fahrer_vorname'];?></a></td>
			</tr>
			<tr>
				<td>Nation:</td>
				<td><a href="#" id="fahrernation" class="fahrernation" data-type="text" data-pk="<?= $aFahrer['fahrer_id'];?>" data-url="<?= base_url();?>admin/stammdaten/setFahrerDetails/" data-title="Set Nation"><?= $aFahrer['fahrer_nation'];?></a></td>
			</tr>
			<tr>
				<td>Team:</td>
				<td>
					<select class="form-control" id="<?= $aFahrer['fahrer_id'];?>" name="team">
						<?php
							foreach($aTeams as $k=>$v) {
								$selected = ($aFahrer['fahrer_team_id'] == $v['team_id']) ? ' selected' : '';
							?>		
								<option value="<?= $v['team_id'];?>" <?= $selected;?>><?= $v['team_name'];?></option>	
							<?php
							}
						?>
					</select>
				</td>
			</tr>
			
		</table>
	</div>
</div>