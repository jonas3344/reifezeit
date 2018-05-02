<div class="container admin">
	<div class="row well">
		<h1>Kapitäne</h1>
	</div>
	<div class="row well">
		<select id="kapitaene" class="form-control" style="margin-bottom: 5px;">
			<?php
			foreach($user as $k=>$v) {
				?>
				<option value="<?= $v['id']?>"><?= $v['name'];?></option>
				<?php	
			}
			?>
		</select>
		
		<button class="btn btn-default" type="button" id="save">Kapitän speichern</button>
	</div>
	<div class="row well">
		<table class="table table-striped">
			<tbody id="kbody">
				<?php
				foreach($kapitaene as $k=>$v) {
					?>
					<tr>
						<td><?= $v['name'];?></td>
						<td><button type="button" class="btn btn-default btnDelete" data-id="<?= $v['id'];?>"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></button></td>
					</tr>
					<?php	
				}
				?>
			</tbody>
		</table>
	</div>
</div>