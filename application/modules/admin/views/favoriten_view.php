<div class="container admin">
	<div class="row well">
		<h1>Favoriten</h1>
	</div>
	<div class="row well">
		<h2>Favoriten GK</h2>
		<table class="table table-striped">
			<thead>
				<th>Fahrer</th>
				<th>Punkte</th>
				<th>Rolle</th>
			</thead>
			<tbody>
		<?php 
			//print_r($favoriten);
			foreach($favoritenGk as $k=>$v) {
				?>
				<tr>
					<td><?= $v['rzname'];?></td>
					<td><?= $v['punkte'];?></td>
					<td><?= $v['rolle_bezeichnung'];?></td>
				</tr>
				<?php
			}
		?>
			</tbody>
		</table>
		<h2>Favoriten Punkte</h2>
		<table class="table table-striped">
			<thead>
				<th>Fahrer</th>
				<th>Punkte</th>
				<th>Rolle</th>
			</thead>
			<tbody>
		<?php 
			//print_r($favoriten);
			foreach($favoritenPunkte as $k=>$v) {
				?>
				<tr>
					<td><?= $v['rzname'];?></td>
					<td><?= $v['punkte'];?></td>
					<td><?= $v['rolle_bezeichnung'];?></td>
				</tr>
				<?php
			}
		?>
			</tbody>
		</table>
		<h2>Favoriten Berg</h2>
		<table class="table table-striped">
			<thead>
				<th>Fahrer</th>
				<th>Punkte</th>
				<th>Rolle</th>
			</thead>
			<tbody>
		<?php 
			//print_r($favoriten);
			foreach($favoritenBerg as $k=>$v) {
				?>
				<tr>
					<td><?= $v['rzname'];?></td>
					<td><?= $v['punkte'];?></td>
					<td><?= $v['rolle_bezeichnung'];?></td>
				</tr>
				<?php
			}
		?>
			</tbody>
		</table>
	</div>
</div>