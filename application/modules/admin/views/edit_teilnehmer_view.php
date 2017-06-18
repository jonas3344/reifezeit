		<div class="container admin">
			<div class="row well">
				<h1>Teilnehmer bearbeiten</h1>
			</div>
			<div class="row well">
				<input type="hidden" id="user_id" value="<?= $aTeilnehmer['user_id'];?>">
				<table class="table">
					<tr>
						<th width="20%">User</th>
						<td><?= $aTeilnehmer['name'];?></td>
					</tr>
					<tr>
						<th width="20%">RZ-Name</th>
						<td><?= $aTeilnehmer['rzname'];?></td>
					</tr>
					<tr>
						<th width="20%">Team</th>
						<td><select id="team" class="form-control">
							<?php
							foreach($aTeams as $k=>$v) {
								$sSel = ($aTeilnehmer['rz_team_id'] == $v['rzteam_id']) ? ' selected' : '';
								?>
								<option value="<?=$v['rzteam_id'];?>"<?= $sSel;?>><?=$v['rzteam_name'];?></option>
								<?php
							}
							?>
						</select></td>
					</tr>
					<tr>
						<th width="20%">Rolle</th>
						<td><select id="rolle" class="form-control">
							<?php
							foreach($aRollen as $k=>$v) {
								$sSel = ($aTeilnehmer['rolle_id'] == $v['rolle_id']) ? ' selected' : '';
								?>
								<option value="<?=$v['rolle_id'];?>"<?= $sSel;?>><?=$v['rolle_bezeichnung'];?></option>
								<?php
							}
							?>
						</select></td>
					</tr>
				</table>
			</div>
		</div>
