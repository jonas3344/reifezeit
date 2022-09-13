<div class="container admin">
	<div class="row well">
		<h1>Doper</h1>
	</div>
	<div class="row well">
		<select class="form-control" id="teilnehmer" style="margin-bottom: 15px;">
			<?php
			foreach($aTeilnehmer as $k=>$v) {
				?>
				<option value="<?= $v['id'];?>"><?= $v['rzname'];?></option>
				<?php	
			}	
			?>
		</select>
		<input type="hidden" id="etappen_id" value="<?= $iEtappe;?>">
		<a href="#"><button type="button" class="btn btn-default" id="submit">Doper eintragen</button></a>
	</div>
	<div class="row well">
		<table class="table">
			<thead>
				<th>Etappe</th>
				<th>User</th>
				<th></th>
			</thead>
			<tbody>
		<?php
			foreach($aDoper as $k=>$v) {
			?>
				<tr>
					<td><?= $v['etappen_nr'];?></td>
					<td><?= $v['rzname'] . ' (' .  $v['name'] . ')';?></td>
					<td><a href="<?= base_url();?>admin/dopingtest/deleteDoper/<?= $v['dopingfall_id'];?>/<?= $iEtappe;?>" class="btn btn-default">Löschen</a>
				</tr>			
			<?php	
			}
		?>
			</tbody>
		</table>
	</div>
</div>