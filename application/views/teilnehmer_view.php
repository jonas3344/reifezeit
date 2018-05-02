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
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<div class="panel panel-default">
						  <div class="panel-heading text-center"><strong>Titelverteidiger</strong></div>
						  <div class="panel-body text-center">
						    <a href="<?= base_url(); ?>historie/timeline/<?= $lastYear['sieger']['id'];?>"><?= $lastYear['sieger']['rzname'];?></a>
						  </div>
						</div>
					</div>
				</div>
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
				            $s_color = "kapitaen";
				            $colorSchrift = 'color:black;';
			            } else if ($v['rolle_id'] == 2) {
				            $s_color = "rundfahrer";
				            $colorSchrift = 'color:black;';
			            } else if ($v['rolle_id'] == 3) {
				            $s_color = "etappenjaeger";
				            $colorSchrift = 'color:black;';
			            } else if ($v['rolle_id'] == 4) {
				            $s_color = "helfer";
				            $colorSchrift = 'color:black;';
			            } else if ($v['rolle_id'] == 5) {
				            $s_color = "neo";
				            $colorSchrift = 'color:black;';
			            } else if ($v['rolle_id'] == 6) {
				            $s_color = "bergfex";
				            $colorSchrift = 'color:black;';
			            } else if ($v['rolle_id'] == 7) {
				            $s_color = "sprinter";
			            } else if ($v['rolle_id'] == 8) {
				            $s_color = "zeitfahrer";
				            $colorSchrift = 'color:black;';
			            }
	              ?>
					<tr class="<?= $s_color;?>">
						<td><?= $v['id'];?></td>
						<td><a href="<?= base_url();?>historie/timeline/<?=$v['id'];?>"  style="<?= $colorSchrift;?>"><?= $v['name'];?></a></td>
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
</div>