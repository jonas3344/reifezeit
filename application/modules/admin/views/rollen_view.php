<div class="container admin">
	<div class="row well">
		<h1>Rollen</h1>
	</div>
	<div class="row well table-responsive">
		<table class="table striped">
			<thead>
				<?php
				foreach($aHeader as $k=>$v) {
					?>
					<th><?= $v;?></th>
					<?php
				}	
				?>
			</thead>
			<tbody>
			<?php
				foreach($aRollen as $k=>$v) {
					echo '<tr>';
					foreach($v as $kl=>$p) {
						?>
						<td><?=$p;?></td>
						<?php
					}
					echo '</tr>';
				}	
				?>
				
			</tbody>
		</table>
	</div>
</div>