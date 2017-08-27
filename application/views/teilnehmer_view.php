<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> Teilnehmer <?= $this->config->item('sAktuelleRundfahrt');?></span>
				</div>
			</div>
			<div class="portlet-body">
				<table 	data-toggle="table"
				data-pagination="true" 
				data-detail-view="true"
				data-detail-formatter="detailFormatter"
				data-search="true"
				class="table">
				<thead>
					<tr>
						<th data-field="id" data-width="5%">ID</th>
						<th data-sortable="true" data-width="25%">Benutzer</th>
						<th data-sortable="true" data-width="25%">RZ-Name</th>
						<th data-sortable="true" data-width="20%">Rolle</th>
						<th data-sortable="true" data-width="25%">Team</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($aTeilnehmer as $k=>$v) {
						if ($v['rolle_id'] == 1) {
				            $s_color = "warning";
			            } else if ($v['rolle_id'] == 2) {
				            $s_color = "success";
			            } else if ($v['rolle_id'] == 3) {
				            $s_color = "info";
			            } else if ($v['rolle_id'] == 4) {
				            $s_color = "active";
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
						<td><?= $v['id'];?></td>
						<td><a href="<?= base_url();?>historie/timeline/<?=$v['id'];?>"><?= $v['name'];?></a></td>
						<td><?= $v['rzname'];?></td>
						<td><?= $v['rolle_bezeichnung'];?></td>
						<td><?= $v['team']['rzteam_name'];?></td>
					</tr>
				<?php
					}
				?>
				</tbody>
			</table>		

			
			</div>
		</div>
	</div>
	
	
	
<!--
	<div class="row well">
		<h1>Teilnehmer</h1>
	</div>
	<div class="row well">
		<table 	data-toggle="table"
				data-pagination="true" 
				data-detail-view="true"
				data-detail-formatter="detailFormatter"
				data-search="true"
				class="table">
			<thead>
				<tr>
					<th data-field="id">ID</th>
					<th data-sortable="true">Benutzer</th>
					<th data-sortable="true">RZ-Name</th>
					<th data-sortable="true">Rolle</th>
					<th data-sortable="true">Team</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($aTeilnehmer as $k=>$v) {
					if ($v['rolle_id'] == 1) {
			            $s_color = "warning";
		            } else if ($v['rolle_id'] == 2) {
			            $s_color = "success";
		            } else if ($v['rolle_id'] == 3) {
			            $s_color = "info";
		            } else if ($v['rolle_id'] == 4) {
			            $s_color = "active";
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
					<td><?= $v['id'];?></td>
					<td><a href="<?= base_url();?>historie/timeline/<?=$v['id'];?>"><?= $v['name'];?></a></td>
					<td><?= $v['rzname'];?></td>
					<td><?= $v['rolle_bezeichnung'];?></td>
					<td><?= $v['team']['rzteam_name'];?></td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>		
	</div>
-->
	
</div>