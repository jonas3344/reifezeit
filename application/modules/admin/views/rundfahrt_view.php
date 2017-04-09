		<div class="container admin">
			<div class="row well">
				<h1>Rundfahrten</h1>
			</div>
			<div class="row well">
				<table class="table">
					<tr><td colspan="3" align="right"><a href="<?= base_url();?>admin/stammdaten/edit_rundfahrt/-1"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Neue Rundfahrt</button></a></td>
					</tr>
				</table>
					<table data-toggle="table" data-pagination="true">
						    <thead>
						        <tr>
						            <th data-sortable="true">Rundfahrtsbezeichnung</th>
						            <th data-sortable="true">Rundfahrtsjahr</th>
						            <th>Aktionen</th>
						        </tr>
						    </thead>
						    <tbody>
					<?php foreach ($aRundfahrten as $k=>$v) {
						?>
						<tr>
							<td><?= $v['rundfahrt_bezeichnung'];?></td>
							<td><?= $v['rundfahrt_jahr'];?></td>	
							<td align="right"><a href="<?= base_url();?>admin/stammdaten/edit_rundfahrt/<?= $v['rundfahrt_id'];?>"><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</button></a> <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Delete</button></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
