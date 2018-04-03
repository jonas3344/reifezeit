<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> Shortlists</span>
				</div>
				<div class="actions">
					<a class="btn prev" id="new">
						Neue Shortlist
					</a>
				</div>
			</div>
			<div class="portlet-body">
				<ul class="nav nav-tabs" role="tablist" id="tablist">
					<?php
					foreach($aShortlists as $k=>$v) {
						$sActive = ($v['id'] == $iShortlist) ? ' class="active"' : '';
						?>
						<li<?= $sActive;?>><a href="#<?= $v['name'];?>" data-toggle="tab"><?= $v['name'];?></a></li>
						<?php
					}
					foreach($aSharedShortlists as $k=>$v) {
						$sActive = ($v['id'] == $iShortlist) ? ' class="active"' : '';
						?>
						<li<?= $sActive;?>><a href="#<?= $v['name'];?>" data-toggle="tab"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> <?= $v['name'];?></a></li>
						<?php
					}
					?>
				</ul>
				<div class="tab-content panel">
				<?php
				foreach($aShortlists as $k=>$v) {
					$sActive = ($v['id'] == $iShortlist) ? ' active' : '';
					?>
					<div class="tab-pane<?= $sActive;?>" id="<?= $v['name'];?>">
						<div class="panel panel-default">
						<div class="panel-body">
							<button class="btn btn-default btn-sm share" id="<?= $v['id'];?>_<?= $v['share_to_team'];?>">Shortlist <?= ($v['share_to_team'] == 0) ? 'freigeben' : 'nicht mehr freigeben';?></button>
							<button class="btn btn-default btn-sm forencode" id="<?= $v['id'];?>">Forencode</button>
							<button class="btn btn-default btn-sm delete" id="<?= $v['id'];?>">Shortlist löschen</button>
						</div>
						</div>
						<table class="table table-striped" style="font-size: 1.1rem">
							<thead>
								<th>Fahrer</th>
								<th>Team</th>
								<th>Nation</th>
								<th>Credits</th>
								<th></th>
							</thead>
							<?php
								foreach($v['aFahrer'] as $kF=>$vF) {
									?>
									<tr id="row_<?= $v['id'] . '_' . $vF['fahrer_id'];?>">
										<td width="60%"><?= $vF['fahrer_vorname'] . ' ' . $vF['fahrer_name'];?></td>
										<td width="10%"><?= $vF['team_short'];?></td>
										<td width="10%"><img src="<?= base_url() . 'img/flags/' . strtolower($vF['fahrer_nation']) . '.png';?>" width="20"></td>
										<td width="10%"><?= $vF['fahrer_rundfahrt_credits'];?></td>
										<td width="10%"><button class="btn btn-default btn-xs remove" id="<?= $v['id'] . '_' . $vF['fahrer_id'];?>"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></td>
									</tr>
									<?php		
								}
							?>
						</table>
					</div>
	
					<?php
				}
				?>
				
				<?php
				foreach($aSharedShortlists as $k=>$v) {
					$sActive = ($v['id'] == $iShortlist) ? ' active' : '';
					?>
					<div class="tab-pane<?= $sActive;?>" id="<?= $v['name'];?>">
						<div class="panel panel-default">
						<div class="panel-heading"><?= $v['name'];?> (Von <?= $v['rzname'];?>)</div>
						<div class="panel-body">
							<button class="btn btn-default forencode" id="<?= $v['id'];?>">Forencode</button>
						</div>
						</div>
						<table class="table table-striped" style="font-size: 1.1rem">
							<thead>
								<th>Fahrer</th>
								<th>Team</th>
								<th>Nation</th>
								<th>Credits</th>
								<th></th>
							</thead>
							<?php
								foreach($v['aFahrer'] as $kF=>$vF) {
									?>
									<tr id="row_<?= $v['id'] . '_' . $vF['fahrer_id'];?>">
										<td width="60%"><?= $vF['fahrer_vorname'] . ' ' . $vF['fahrer_name'];?></td>
										<td width="10%"><?= $vF['team_short'];?></td>
										<td width="10%"><img src="<?= base_url() . 'img/flags/' . strtolower($vF['fahrer_nation']) . '.png';?>" width="20"></td>
										<td width="10%"><?= $vF['fahrer_rundfahrt_credits'];?></td>
										<td width="10%"></td>
									</tr>
									<?php		
								}
							?>
						</table>
					</div>
	
					<?php
				}
				?>
	
			</div>
				
			</div>
		</div>
	</div>
	
<!--
	<div class="row well">
		<h1>Shortlists</h1>
	</div>
	<div class="row well">
		<button class="btn btn-default" id="new">Neue Shortlist</button>
	</div>
	<div class="row well">
		<ul class="nav nav-tabs" role="tablist" id="tablist">
			<?php
			foreach($aShortlists as $k=>$v) {
				$sActive = ($v['id'] == $iShortlist) ? ' class="active"' : '';
				?>
				<li<?= $sActive;?>><a href="#<?= $v['name'];?>" data-toggle="tab"><?= $v['name'];?></a></li>
				<?php
			}
			foreach($aSharedShortlists as $k=>$v) {
				$sActive = ($v['id'] == $iShortlist) ? ' class="active"' : '';
				?>
				<li<?= $sActive;?>><a href="#<?= $v['name'];?>" data-toggle="tab"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> <?= $v['name'];?></a></li>
				<?php
			}
			?>
		</ul>
		<div class="tab-content panel">
			<?php
			foreach($aShortlists as $k=>$v) {
				$sActive = ($v['id'] == $iShortlist) ? ' active' : '';
				?>
				<div class="tab-pane<?= $sActive;?>" id="<?= $v['name'];?>">
					<div class="panel panel-default">
					<div class="panel-heading"><?= $v['name'];?></div>
					<div class="panel-body">
						<button class="btn btn-default share" id="<?= $v['id'];?>_<?= $v['share_to_team'];?>">Shortlist <?= ($v['share_to_team'] == 0) ? 'freigeben' : 'nicht mehr freigeben';?></button>
						<button class="btn btn-default forencode" id="<?= $v['id'];?>">Forencode</button>
						<button class="btn btn-default delete" id="<?= $v['id'];?>">Shortlist löschen</button>
					</div>
					</div>
					<table class="table table-striped">
						<thead>
							<th>Fahrer</th>
							<th>Team</th>
							<th>Nation</th>
							<th>Credits</th>
							<th></th>
						</thead>
						<?php
							foreach($v['aFahrer'] as $kF=>$vF) {
								?>
								<tr id="row_<?= $v['id'] . '_' . $vF['fahrer_id'];?>">
									<td width="60%"><?= $vF['fahrer_vorname'] . ' ' . $vF['fahrer_name'];?></td>
									<td width="10%"><?= $vF['team_short'];?></td>
									<td width="10%"><img src="<?= base_url() . 'img/flags/' . strtolower($vF['fahrer_nation']) . '.png';?>" width="20"></td>
									<td width="10%"><?= $vF['fahrer_rundfahrt_credits'];?></td>
									<td width="10%"><button class="btn btn-default remove" id="<?= $v['id'] . '_' . $vF['fahrer_id'];?>"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></td>
								</tr>
								<?php		
							}
						?>
					</table>
				</div>

				<?php
			}
			?>
			
			<?php
			foreach($aSharedShortlists as $k=>$v) {
				$sActive = ($v['id'] == $iShortlist) ? ' active' : '';
				?>
				<div class="tab-pane<?= $sActive;?>" id="<?= $v['name'];?>">
					<div class="panel panel-default">
					<div class="panel-heading"><?= $v['name'];?> (Von <?= $v['rzname'];?>)</div>
					<div class="panel-body">
						<button class="btn btn-default forencode" id="<?= $v['id'];?>">Forencode</button>
					</div>
					</div>
					<table class="table table-striped">
						<thead>
							<th>Fahrer</th>
							<th>Team</th>
							<th>Nation</th>
							<th>Credits</th>
							<th></th>
						</thead>
						<?php
							foreach($v['aFahrer'] as $kF=>$vF) {
								?>
								<tr id="row_<?= $v['id'] . '_' . $vF['fahrer_id'];?>">
									<td width="60%"><?= $vF['fahrer_vorname'] . ' ' . $vF['fahrer_name'];?></td>
									<td width="10%"><?= $vF['team_short'];?></td>
									<td width="10%"><img src="<?= base_url() . 'img/flags/' . strtolower($vF['fahrer_nation']) . '.png';?>" width="20"></td>
									<td width="10%"><?= $vF['fahrer_rundfahrt_credits'];?></td>
									<td width="10%"></td>
								</tr>
								<?php		
							}
						?>
					</table>
				</div>

				<?php
			}
			?>

		</div>
	</div>
-->
</div>