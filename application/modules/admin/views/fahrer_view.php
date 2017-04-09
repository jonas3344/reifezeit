<div class="container admin">
			<div class="row well">
				<h1>Teams</h1>
			</div>
			<div class="row well">
				<table class="table">
					<tr><td colspan="3" align="right"><a href="<?= base_url();?>admin/stammdaten/edit_fahrer/-1"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Neuer Fahrer</button></a></td>
					</tr>	
				</table>
				<table 	data-toggle="table"
						data-pagination="true" 
						data-search="true"
						class="table">
				<thead>
					<tr>
						<th data-sortable="true">Team-ID</th>
						<th>Team</th>
						<th data-formatter="operateFormatter"
							data-events="operateEvents">Aktion</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($aRiders as $k=>$v) {
					?>
					<tr>
						<td><?= $v['fahrer_id'];?></td>
						<td><?= $v['fahrer_vorname'] . ' ' . $v['fahrer_name'];?></td>
						<td><a href="<?= base_url();?>admin/stammdaten/edit_fahrer/<?= $v['fahrer_id'];?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> </a> </td>
					</tr>
					<?php
						}
					?>
				</tbody>
				</table>
			</div>
</div>