<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> Transfermarkt</span>
				</div>
				<ul class="nav nav-tabs">
					<li>
						<a href="<?= base_url();?>rundfahrt/transfermarkt/1">
						Sortieren nach Teams</a>
					</li>
					<li<?= ($iSort==2) ? ' class="active"' : '';?>>
						<a href="<?= base_url();?>rundfahrt/transfermarkt/2">
						Sortieren nach Credits</a>
					</li>
					<li<?= ($iSort==3) ? ' class="active"' : '';?>>
						<a href="<?= base_url();?>rundfahrt/transfermarkt/3">
						Sortieren nach Namen</a>
					</li>
				</ul>
			</div>
			<div class="portlet-body">
				<div class="responsive-table">
					<table class="table table-striped smaller_table">
						<thead>
							<th>#</th>
							<th>Name</th>
							<th>Nation</th>
							<th></th>
							<th>Credits</th>
							<th width="10%">Shortlist</th>
						</thead>
						<tbody>
						<?php
						foreach($aData as $k=>$aFahrer) {
							$sColor = ($aFahrer['ausgeschieden'] == 1) ? ' class="danger"' : '';
							?>
							<tr<?= $sColor;?>>
								<td><?= $aFahrer['fahrer_startnummer'];?></td>
								<td><?= $aFahrer['fahrer_vorname'] . ' ' . $aFahrer['fahrer_name'];?></td>
								<td><img src="<?= base_url() . 'img/flags/' . strtolower($aFahrer['fahrer_nation']) . '.png';?>" width="20"></td>
								<td><?= $aFahrer['team_short'];?></td>
								<td><?= $aFahrer['fahrer_rundfahrt_credits'];?></td>
								<td>
										<?php
										if (count($aShortlists) > 0) {
											?>
											<div class="dropdown">
												<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default btn-sm" style="margin-top:2px;">
												     <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
												</button>
												  <ul class="dropdown-menu" aria-labelledby="dLabel">
													  <?php
														  foreach($aShortlists as $k=>$v) {
															  if ($this->Shortlist_model->checkFahrerShortlist($aFahrer['fahrer_id'], $v['id']) == false)
															  {
																	?>
																	<li><a class="addToShortlist" id="<?= $aFahrer['fahrer_id'] . '_' . $v['id'];?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <?= $v['name'];?></a></li>
															  <?php
																}
														  }
														  ?>	
												  </ul>
											</div>	
											<?php
										}
										?>
									</td>
							</tr>
							<?php
						}	
						?>
						</tbody>
					</table>
				</div>				
			</div>
		</div>
	</div>
</div>