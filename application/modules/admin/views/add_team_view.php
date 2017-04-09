<div class="container admin">
	<div class="row well">
		<h1>Team hinzufügen</h1>
	</div>
	<div class="row well">
		<table class="table">
			<tbody>
			<?php
			foreach($aTeams as $k=>$v) {
				?>
				<tr>
					<td width="10%"><a href="#"><button type="button" class="btn btn-default add" id="<?= $v['team_id'];?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button></a></td>
					<td><?= $v['team_name'];?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	</div>
</div>