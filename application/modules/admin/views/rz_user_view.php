<div class="container admin">
			<div class="row well">
				<h1>Teams</h1>
			</div>
			<div class="row well">
				<table class="table">
					<tr><td colspan="3" align="right"><a href="<?= base_url();?>admin/stammdaten/edit_fahrer/-1"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Neuer User</button></a></td>
					</tr>	
				</table>
				<table 	data-toggle="table"
						data-pagination="true" 
						data-search="true"
						class="table">
				<thead>
					<tr>
						<th data-sortable="true">User-ID</th>
						<th data-sortable="true">Benutzername</th>
						<th data-sortable="true">RZ-Name</th>
						<th>Aktion</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($aRzuser as $k=>$v) {
					?>
					<tr>
						<td><?= $v['id'];?></td>
						<td><?= $v['name'];?></td>
						<td><?= $v['rzname'];?></td>
						<td><a href="<?= base_url();?>admin/stammdaten/edit_user/<?= $v['id'];?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> </a> </td>
					</tr>
					<?php
						}
					?>
				</tbody>
				</table>
			</div>
</div>