<div class="container admin">
	<div class="row well">
		<h1>Transfermarkt - Teams sortieren</h1>
	</div>
	<div class="row well">
		<table class="table">
			<tbody id="sortable">
			<?php
			foreach($aTeams as $k=>$v) {
			?>
			<tr id="item_<?= $v['team_id'] ?>">
				<td class="id" id="<?= $v['team_id'];?>"><span class="glyphicon glyphicon-resize-vertical sort"></span></td>
				<td><?= $v['team_name'];?></td>
				<td><?= $v['start_order'];?></td>
			</tr>
			<?php	
			}
			?>
			</tbody>
		</table>
	</div>
</div>