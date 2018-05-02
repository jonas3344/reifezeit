<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> Transfermarkt</span>
				</div>
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="<?= base_url();?>rundfahrt/transfermarkt/1">
						Sortieren nach Teams</a>
					</li>
					<li>
						<a href="<?= base_url();?>rundfahrt/transfermarkt/2">
						Sortieren nach Credits</a>
					</li>
					<li>
						<a href="<?= base_url();?>rundfahrt/transfermarkt/3">
						Sortieren nach Namen</a>
					</li>
				</ul>
			</div>
			
			<div class="portlet-body">
				<div class="responsive-table">
					<?php
						if ($bView) {
					?>
					<table class="table table-striped smaller_table">
						<thead>
							<th>#</th>
							<th>Name</th>
							<th>Nation</th>
							<th>Credits</th>
							<th width="10%">Shortlist</th>
						</thead>
						<tbody>
						<?php
								
			
						foreach($aData as $k=>$aTeams) {
							?>
							<tr class="success">
								<td colspan="5"><strong><?= $aTeams['team_name'];?></strong></td>
							</tr>
							<?php
							foreach($aTeams['fahrer'] as $aFahrer) {
								$sColor = ($aFahrer['ausgeschieden'] == 1) ? ' class="danger"' : '';
								?>
								<tr<?= $sColor;?>>
									<td><?= $aFahrer['fahrer_startnummer'];?></td>
									<td><a class="showFahrerModal" id="<?= strtolower(str_replace(' ', '-', $aFahrer['fahrer_vorname'])) . '_' . strtolower(str_replace(' ', '-', $aFahrer['fahrer_name']));?>"><?= $aFahrer['fahrer_vorname'] . ' ' . $aFahrer['fahrer_name'];?></a></td>
									<td><img src="<?= base_url() . 'img/flags/' . strtolower($aFahrer['fahrer_nation']) . '.png';?>" width="20"></td>
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
						}	
			
						?>
						</tbody>
					</table>
					<?php
						}
						else {
							echo "Der Transfermarkt ist noch nicht veröffentlicht!";
						}
						?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="fahrermodal" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="min-height: 500px; height: 80%; padding: 5px;">
      <iframe src="" id="fahrerframe" style="position: relative; display:block; width: 100%; height: 80vh"></iframe>
    </div>
  </div>
</div>