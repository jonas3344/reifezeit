<div class="container admin">

	<div class="row">
		<div class="col-md-6">
        	<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-info-sign"></i>
						<span class="caption-subject text-uppercase"> Fahrerdetails</span>
					</div>
					<div class="actions">
						<a href="<?= base_url();?>historie/index/<?= $iUser;?>" class="btn">
							<i class="glyphicon glyphicon-eye-open"></i>
							Alle Daten 
						</a>
					</div>
				</div>
				<div class="portlet-body">
					<p>
						<div class="avatar">
							<img src="<?= base_url();?>img/avatars/<?= $sAvatar;?>" width="128px">
							<?php
								if ($iUser == $this->session->userdata('user_id')) {
									?>
									<button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#upload_avatar"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button>
									<?php
								}
							?>
						</div>
						<table class="table">
					<tr>
						<td width="40%">Sattlerei-Name:</td>
						<td><?= $aUser['name'];?></td>
					</tr>
					<tr>
						<td>Aktueller RZ-Name:</td>
						<td><?= $aUser['rzname'];?></td>
					</tr>
					<tr>
						<td>Erste Teilnahme:</td>
						<td><?= $aHistory[0]['bezeichnung'] . ' ' . $aHistory[0]['jahr'];?></td>					
					</tr>
					<tr>
						<td>Anzahl Teilnahmen:</td>
						<td><?= count($aHistory);?></td>					
					</tr>
					<tr>
						<td>Anzahl Gesamtsiege:</td>
						<td><?= count($aSuccess['aGesamt']);?></td>					
					</tr>
					<tr>
						<td>Anzahl Etappensiege:</td>
						<td><?= count($aEs);?></td>					
					</tr>
					<tr>
						<td>Anzahl Siege Punktwertung:</td>
						<td><?= count($aSuccess['aPunkte']);?></td>					
					</tr>
					<tr>
						<td>Anzahl Siege Bergwertung:</td>
						<td><?= count($aSuccess['aBerg']);?></td>					
					</tr>
					<tr>
						<td>Anzahl Tage im Leadertrikot:</td>
						<td><?= is_array($aLeader[1]) ? count($aLeader[1]) : 0;?></td>					
					</tr>
					<tr>
						<td>Anzahl Tage im Punktetrikot:</td>
						<td><?= is_array($aLeader[2]) ? count($aLeader[2]) : 0;?></td>					
					</tr>
					<tr>
						<td>Anzahl Tage im Bergtrikot:</td>
						<td><?= is_array($aLeader[3]) ? count($aLeader[3]) : 0;?></td>					
					</tr>
					<tr>
						<td>Bisherige Teams:</td>
						<td><ul class="teamlist" style="padding-left: 14px;">
							<?php 
							foreach($aTeams as $k=>$v) {
								?>
								<li><a href="<?= base_url();?>historie/teams/<?= $v['rzteam_id'];?>"><?= $v['rzteam_name'];?></a></li>
								<?php
							}?>
						</ul></td>
					</tr>
					</table>
					</p>
				</div>
			</div>

				
		</div>

		<div class="col-md-6">
			<div class="timeline timeline-single-column">
				<span class="timeline-label">
                        <button class="btn btn-danger"><i class="glyphicon glyphicon-arrow-down"></i></button>
                    </span>
				<?php
				$aFirst = true;
				foreach($aTimelineData as $k=>$v) {
					?>
					<span class="timeline-label">
                        <span class="label label-primary"><?= $k;?></span>
                    </span>
					<?php
					if ($aFirst == true) {
						?>
						<div class="timeline-item">
	                        <div class="timeline-point timeline-point-primary">
	                            <i class="glyphicon glyphicon-ok"></i>
	                        </div>
	                        <div class="timeline-event timeline-event-primary">
	                            <div class="timeline-heading">
	                                <h4>Erste Teilnahme an einer Reifezeit-Rundfahrt</h4>
	                            </div>
	                            <div class="timeline-body">
	                                <p>
		                                <?= $v['aFirst']['bezeichnung'] . ' ' . $v['aFirst']['jahr'];?>
	                                </p>
	                            </div>
	                        </div>
	                    </div>
						<?php
						$aFirst = false;
						unset($v['aFirst']);
					}
					foreach($v as $kE => $vE) {
						foreach($vE as $aErfolg) {
							if ($aErfolg['art'] == 1) {
								$sText = 'warning';
								$sIcon = 'glyphicon glyphicon-star';
							} else if ($aErfolg['art'] == 2) {
								$sText = 'success';
								$sIcon = 'glyphicon glyphicon-star';
							}else if ($aErfolg['art'] == 3) {
								$sText = 'danger';
								$sIcon = 'glyphicon glyphicon-star';
							} else if ($aErfolg['art'] == 4) {
								$sText = 'info';
								$sIcon = 'glyphicon glyphicon-thumbs-up';
							}

						?>
							<div class="timeline-item">
	                        <div class="timeline-point timeline-point-<?= $sText;?>">
	                            <i class="<?= $sIcon;?>"></i>
	                        </div>
	                        <div class="timeline-event timeline-event-<?= $sText;?>">
	                            <div class="timeline-heading">
	                                <h4><?= $aErfolg['text'] . ' ' . $aErfolg['rundfahrt'];?></h4>
	                            </div>
	                        </div>
	                    </div>

						<?php
						}
					}
				}	
				?>
				<span class="timeline-label">
                        <button class="btn btn-danger"><i class="glyphicon glyphicon-arrow-down"></i></button>
                    </span>

			</div>
			
        </div>
	</div>
</div>

<div class="modal fade" id="upload_avatar" tabindex="-1" role="dialog" aria-labelledby="upload_avatar">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Avatar heraufladen</h4>
      </div>
      <div class="modal-body">
        <form action="<?= base_url()?>historie/uploadAvatar" method="post" enctype="multipart/form-data">
	        <input type="hidden" name="user" value="<?= $iUser;?>">
	        <div class="form-group">
			    <label for="avatar">Avatar</label>
			    <input type="file" id="avatar" name="avatar">
			    <p class="help-block">gif, jpg oder png, wird mit einer Breite von 128px dargestellt</p>
		  </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
        <button type="submit" class="btn btn-primary">Upload</button>
      </div>
      </form>
    </div>
  </div>
</div>