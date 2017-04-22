<div class="container admin">
	<div class="row well">
		<h1>Transfermarkt</h1>
	</div>
	<div class="row well">
		<a href="<?= base_url();?>rundfahrt/transfermarkt/1"><button class="btn btn-default"><span class="glyphicon glyphicon-sort-by-order" aria-hidden="true"></span> Sortieren nach Teams</button></a>
		<a href="<?= base_url();?>rundfahrt/transfermarkt/2"><button class="btn btn-default"><span class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span> Sortieren nach Credits</button></a>
		<a href="<?= base_url();?>rundfahrt/transfermarkt/3"><button class="btn btn-default"><span class="glyphicon glyphicon-sort-by-alphabet" aria-hidden="true"></span> Sortieren nach Namen</button></a>
	</div>
	<div class="row well responsive-table">
		<table class="table table-striped">
			<thead>
				<th>#</th>
				<th>Name</th>
				<th>Nation</th>
				<th>Credits</th>
			</thead>
			<tbody>
			<?php
			foreach($aData as $k=>$aTeams) {
				?>
				<tr class="success">
					<td colspan="4"><strong><?= $aTeams['team_name'];?></strong></td>
				</tr>
				<?php
				foreach($aTeams['fahrer'] as $aFahrer) {
					$sColor = ($aFahrer['ausgeschieden'] == 1) ? ' class="danger"' : '';
					?>
					<tr<?= $sColor;?>>
						<td><?= $aFahrer['fahrer_startnummer'];?></td>
						<td><?= $aFahrer['fahrer_vorname'] . ' ' . $aFahrer['fahrer_name'];?></td>
						<td><img src="<?= base_url() . 'img/flags/' . strtolower($aFahrer['fahrer_nation']) . '.png';?>" width="20"></td>
						<td><?= $aFahrer['fahrer_rundfahrt_credits'];?></td>
					</tr>
					<?php
				}
			}	
			?>
			</tbody>
		</table>
	</div>
</div>