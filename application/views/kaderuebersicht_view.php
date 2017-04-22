<div class="container admin">
	<div class="row well">
		<h1>Kaderübersicht!</h1>
	</div>
	<?php
	if (count($aDoping) > 0){
		?>
		<div class="row ">
			<div class="alert alert-danger">
			<?php
			foreach($aDoping as $k=>$v) {
				?>
				Du bist in der <?= $v['etappen_nr'];?>.Etappe mit zu vielen Credits angetreten!<br>
				<?php
			}	
			?>
			</div>
		</div>
		<?php
	}		
	?>
	<div class="row well">
		<div class="table-responsive">
			<table class="table table-striped vertical-align">
				<thead>
					<th>#</th>
					<th></th>
					<th>Fahrer 1</th>
					<th></th>
					<th>Fahrer 2</th>
					<th></th>
					<th>Fahrer 3</th>
					<th></th>
					<th>Fahrer 4</th>
					<th></th>
					<th>Fahrer 5</th>
					<th></th>
					<th></th>
				</thead>
				<tbody>
					<?php
					foreach($aKader as $k=>$v) {
						$iSum = 0;
						$sBorder = ($this->config->item('iAktuelleEtappe') == $v['aEtappe']['etappen_id']) ? ' class="active_etappe"' : '';
						?>
						<tr<?= $sBorder;?>>
							<td<?= $v['aEtappe']['sColor'];?>><a class="btn" rel="popover" data-img="<?= base_url();?>img/<?= $v['aEtappe']['etappen_profil'];?>"><?= $k+1;?></a></div></td>
							<td align="center"><a href="<?= base_url();?>kader/tag/<?=$v['aEtappe']['etappen_id'];?>"><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button></a></td>
							<?php
							foreach($v['aKader']['aFahrer'] as $p=>$k) {
								$iSum += $k['fahrer_rundfahrt_credits'];
								$sChange = '';
								if ($k['change'] == 1) {
									$sChange = '<span class="glyphicon glyphicon-sort" aria-hidden="true"></span>';
								} else if ($k['change'] == 2) {
									$sChange = '';
								}
								?>
								<td><?= strtoupper(substr($k['fahrer_vorname'], 0, 1));?>.<?= $k['fahrer_name'];?> <?= $sChange;?></td>
								<td class="border_right"><?= $k['fahrer_rundfahrt_credits']; ?></td>
								<?php
							}	
							?>
							<td><?= $iSum;?></td>
						</tr>
						<?php
					}	
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>