<div class="container admin">
	<div class="row well">
		<h1>Planung</h1>
	</div>
	<div class="row well">
		<button class="btn btn-default" id="new">Neue Planung</button>
	</div>
	<div class="row well">
	    <ul class="nav nav-tabs" role="tablist" id="tablist">
			<?php
			foreach($aPlanung as $k=>$v) {
				if ($v['id'] == $iPlanung) {
					$sActive = ' class="active"';
				} else {
					$sActive = '';
				}
				?>
				<li<?= $sActive;?>><a href="#<?= $v['planung_name'];?>" data-toggle="tab"><?= $v['planung_name'];?></a></li>
				<?php
			}	
			?>
	    </ul>
	    <div class="tab-content panel">
			<?php
			foreach($aPlanung as $k=>$v) {
				if ($v['id'] == $iPlanung) {
					$sActive = ' active';
				} else {
					$sActive = '';
				}
				?>
				<div class="tab-pane<?= $sActive;?>" id="<?= $v['planung_name'];?>">
					<div class="panel panel-default">
					<!-- Default panel contents -->
					<div class="panel-heading"><?= $v['planung_name'];?></div>
					<div class="panel-body">
    
  
						<button class="btn btn-default save" id="<?= $v['id'];?>">Planung übernehmen</button> 
						<button class="btn btn-default rename" id="<?= $v['id'];?>">Planung umbennenen</button> 
						<button class="btn btn-default reset" id="<?= $v['id'];?>">Planung zurücksetzen</button> 
						<button class="btn btn-default delete" id="<?= $v['id'];?>">Planung löschen</button><br>
						
						<div class="form-group" style="margin-top:5px;">
						<label class="radio-inline">
						  <input type="radio" name="<?= $v['id'];?>_sortorder" value="2" checked> Sortierung nach Credits
						</label>
						<label class="radio-inline">
						  <input type="radio" name="<?= $v['id'];?>_sortorder" value="1"> Sortierung nach #
						</label>
						</div>
					</div>
					<table class="table table-striped">
					<?php
					foreach($v['aData']['aKader'] as $kk=>$vk) {
						$sColor = '';
						if ($vk['aEtappe']['etappen_klassifizierung'] == 1) {
							$sColor = ' class = success'; 
						} else if ($vk['aEtappe']['etappen_klassifizierung'] == 4) {
							$sColor = ' class = danger'; 
						} else if ($vk['aEtappe']['etappen_klassifizierung'] == 3 || $vk['aEtappe']['etappen_klassifizierung'] == 5 || $vk['aEtappe']['etappen_klassifizierung'] == 6) {
							$sColor = ' class = warning'; 
						}
						if ($vk['aEtappe']['bEdit'] == true) {
							$bEditable = true;
						} else {
							$bEditable = false;
						}
						?>
						<tr>
							<td width="8%"><?php if ($bEditable) {
								?> 
								<div class="dropdown">
									<button class="btn btn-default btn-xs saveKaderDay" id="<?= $vk['aEtappe']['etappen_nr'];?>_<?= $v['id'];?>"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color: green"></span></button>
								  <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default btn-xs">
								    <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
								  </button>
								  <ul class="dropdown-menu" aria-labelledby="dLabel">
									<li><a href="#" class="kaderuebertragUp" id="<?= $vk['aEtappe']['etappen_nr'];?>_<?= $v['id'];?>"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></a></li>
								    <li><a href="#" class="kaderuebertrag" id="<?= $vk['aEtappe']['etappen_nr'];?>_<?= $v['id'];?>"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></a></li>
								    <li><a href="#" class="kaderuebertragAll" id="<?= $vk['aEtappe']['etappen_nr'];?>_<?= $v['id'];?>"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></a></li>						
								  </ul>
								</div>								
<!-- 								<button class="btn btn-default btn-xs kaderuebertrag" id="<?= $vk['aEtappe']['etappen_nr'];?>_<?= $v['id'];?>"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></button> -->
								
								<?php
									}
								?>	
							</td>
							<td<?= $sColor;?> width="4%"><a rel="popover" data-img="<?= base_url();?>img/<?= $vk['aEtappe']['etappen_profil'];?>"><?= $vk['aEtappe']['etappen_nr'];?></a></td>
							<?php
							$iSum = 0;
							$i=1;
							foreach($vk['aFahrer'] as $kf=>$vf) {
								$iSum += $vf['fahrer_rundfahrt_credits'];
								$sChange = '';
							    if ($vf['change'] == 1) {
									$sChange = ' class="info"';
								} else if ($vf['change'] == 2) {
									$sChange = '';
								}
								?>
								<td width="10%"<?= $sChange;?>><span class="fahrer<?= $i . '_' . $v['id'] . '_' . $vk['aEtappe']['etappen_nr'];?> span_select" id="<?= $vf['fahrer_id'];?>"><?= strtoupper(substr($vf['fahrer_vorname'], 0, 1));?>.<?= $vf['fahrer_name'];?></span></td>
								<td width="4%"><?php if ($bEditable) {
									?>
									<button class="btn btn-default btn-xs change" id="<?= $i . '_' . $v['id'] . '_' . $vk['aEtappe']['etappen_nr'];?>"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button>
									<?php } ?>
									</td>
								<td width="4%"><?= $vf['fahrer_rundfahrt_credits'];?></td>
								<?php
									$i++;
							}	
							?>
							<td width="4%"><?= $iSum;?></td>
						</tr>
						<?php
					}
					
					?>
					</table>
					</div>
				</div>
				<?php
			}	
			?>
	    </div>
	</div>
</div>