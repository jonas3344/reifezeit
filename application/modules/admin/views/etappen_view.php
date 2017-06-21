		<div class="container admin">
			<div class="row well">
				<h1>Etappen <?= $sRundfahrt?></h1>
			</div>
			<div class="row well">
				<table class="table">
					<tr>
						<td colspan="3" align="right">
							<a href="<?= base_url();?>admin/stammdaten/parse_etappen"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Etappen Parsen</button></a>
							<a href="<?= base_url();?>admin/stammdaten/edit_etappe/-1"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Neue Etappe</button></a>
						</td>
					</tr>
				</table>
			<table data-toggle="table" class="table">
				<thead>
					<tr>
						<th data-sortable="true">Etappen-Nr</th>
						<th>Datum</th>
						<th data-sortable="true">Start-Ziel</th>
						<th data-sortable="true">Distanz</th>
						<th data-sortable="true">Eingabeschluss</th>
						<th data-sortable="true">Art</th>
						<th>Aktion</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($aEtappen as $k=>$v) {
					?>
						<tr>
							<td><?= $v['etappen_nr']; ?></td>
							<td><?= $v['etappen_datum']; ?></td>
							<td><?= $v['etappen_start_ziel']; ?></td>
							<td><?= $v['etappen_distanz']; ?> km</td>
							<td><?= $v['etappen_eingabeschluss']; ?></td>
							<td><?= $v['klassifizierung_name']; ?></td>
							<td><a href="<?= base_url();?>admin/stammdaten/edit_etappe/<?= $v['etappen_id'];?>"><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</button></a> <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Delete</button></td>
						</tr>
					<?php	
					}
					?>
				</tbody>
				
			</table>
			</div>
		</div>