<div class="container admin">
	<div class="row well">
		<h1>Teilnehmer</h1>
	</div>
	<div class="row well">
		<table 	data-toggle="table"
						data-pagination="true" 
						data-search="true"
						class="table">
			<thead>
				<tr>
					<th data-sortable="true">User-id</th>
					<th data-sortable="true">Benutzer</th>
					<th data-sortable="true">RZ-Name</th>
					<th data-sortable="true">Rolle</th>
					<th data-sortable="true">Team</th>
					<th>Aktion</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($aTeilnehmer as $k=>$v) {
					if ($v['rolle_id'] == 1) {
			            $s_color = "active";
		            } else if ($v['rolle_id'] == 2) {
			            $s_color = "success";
		            } else if ($v['rolle_id'] == 3) {
			            $s_color = "info";
		            } else if ($v['rolle_id'] == 4) {
			            $s_color = "warning";
		            } else if ($v['rolle_id'] == 5) {
			            $s_color = "danger";
		            } else if ($v['rolle_id'] == 6) {
			            $s_color = "bergfex";
		            } else if ($v['rolle_id'] == 7) {
			            $s_color = "sprinter";
		            } else if ($v['rolle_id'] == 8) {
			            $s_color = "zeitfahrer";
		            }
              ?>
				<tr class="<?= $s_color;?>">
					<td><?= $v['user_id'];?></td>
					<td><?= $v['name'];?></td>
					<td><?= $v['rzname'];?></td>
					<td><?= $v['rolle_bezeichnung'];?></td>
					<td><?= $v['team']['rzteam_name'];?></td>
					<td><a href="<?= base_url();?>admin/administration/edit_teilnehmer/<?=$v['user_id'];?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>		
	</div>
	
</div>