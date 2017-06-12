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
	    <div class="tab-content panel" style="margin-bottom: 300px;">
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
					<table class="table table-header-rotated">
						<thead>
							<th class="rotate"><div class="rotate"><span class="rotate">Aktion</span></div></th>
							<th class="rotate"><div class="rotate"><span class="rotate">Etappe</span></div></th>
							<th class="rotate"><div class="rotate"><span class="rotate">Fahrer 1</span></div></th>
							<th></th>
							<th></th>
							<th class="rotate"><div class="rotate"><span class="rotate">Fahrer 2</span></div</th>
							<th></th>
							<th></th>
							<th class="rotate"><div class="rotate"><span class="rotate">Fahrer 3</span></div</th>
							<th></th>
							<th></th>
							<th class="rotate"><div class="rotate"><span class="rotate">Fahrer 4</span></div</th>
							<th></th>
							<th></th>
							<th class="rotate"><div class="rotate"><span class="rotate">Fahrer 5</span></div></th>
							<th></th>
							<th></th>
							<th class="rotate"><div class="rotate"><span class="rotate">Eingesetzte Credits</span></div></th>
							<?php
							if (($aUser['rolle_id'] == 3) || ($aUser['rolle_id'] == 6)) {
								?>
								<th class="rotate"><div class="rotate"><span class="rotate">Fexpunkte</span></div></th>
								<?php
							}
							?>
							<th class="rotate"><div class="rotate"><span class="rotate">CA/BC, etc.</span></div></th>
							<th class="rotate"><div class="rotate"><span class="rotate">Verfügbare Credits</span></div></th>
							
						</thead>
						<tbody>
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
							<td width="4%"><?php if ($bEditable) {
								?> 
								<div class="dropdown">
									<button class="btn btn-default btn-xs saveKaderDay" id="<?= $vk['aEtappe']['etappen_nr'];?>_<?= $v['id'];?>"><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color: green"></span></button><br>
								  <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default btn-xs" style="margin-top:2px;">
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
								<td width="9%"<?= $sChange;?>><span class="fahrer<?= $i . '_' . $v['id'] . '_' . $vk['aEtappe']['etappen_nr'];?> span_select" id="<?= $vf['fahrer_id'];?>"><?= strtoupper(substr($vf['fahrer_vorname'], 0, 1));?>.<?= $vf['fahrer_name'];?></span></td>
								<td width="3%"><?php if ($bEditable) {
									?>
									<button class="btn btn-default btn-xs change" id="<?= $i . '_' . $v['id'] . '_' . $vk['aEtappe']['etappen_nr'];?>"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button>
									<?php } ?>
									</td>
								
								
								<td width="3%"><?= $vf['fahrer_rundfahrt_credits'];?></td>
								
								<?php
									$i++;
							}	
							?>
							
							<td width="4%"><?= $iSum;?></td>
							<?php
							if (($aUser['rolle_id'] == 3) || ($aUser['rolle_id'] == 6)) {
								?>
									<td width="6%">
										<?php
											if ($aUser['rolle_id'] == 3) {
												if (($vk['aEtappe']['etappen_klassifizierung'] != 3) && ($vk['aEtappe']['etappen_klassifizierung'] != 5) && ($vk['aEtappe']['etappen_klassifizierung'] != 6)) {
												?>
												<select class="form-control fex" id="fex_<?= $v['id'];?>_<?= $vk['aEtappe']['etappen_id'];?>">
													<option value="0"<?= ($vk['iFex'] == 0) ? ' selected' : ''?>>0</option>
													<option value="2"<?= ($vk['iFex'] == 2) ? ' selected' : ''?>>2</option>
													<option value="4"<?= ($vk['iFex'] == 4) ? ' selected' : ''?>>4</option>
												</select>
												<?php
												}
											} else if ($aUser['rolle_id'] == 6) {
												if ($vk['aEtappe']['etappen_klassifizierung'] == 4) {
													?>
													<select class="form-control fex" id="fex_<?= $v['id'];?>_<?= $vk['aEtappe']['etappen_id'];?>">
														<option value="0"<?= ($vk['iFex'] == 0) ? ' selected' : ''?>>0</option>
														<option value="1"<?= ($vk['iFex'] == 1) ? ' selected' : ''?>>1</option>
														<option value="2"<?= ($vk['iFex'] == 2) ? ' selected' : ''?>>2</option>
														<option value="3"<?= ($vk['iFex'] == 3) ? ' selected' : ''?>>3</option>
														<option value="4"<?= ($vk['iFex'] == 4) ? ' selected' : ''?>>4</option>
														<option value="5"<?= ($vk['iFex'] == 5) ? ' selected' : ''?>>5</option>
													</select>
												<?php
												}
												
											}
											?>
									</td>
								<?php
							}
							?>
							<td width="6%"><input type="text" class="form-control spielfeld" id="spielfeld_<?= $v['id'];?>_<?= $vk['aEtappe']['etappen_id'];?>" value="<?= $vk['iSpielfeld'];?>"></td>
							<td><span id="avCredits_<?= $v['id'];?>_<?= $vk['aEtappe']['etappen_id'];?>"><?= $vk['iCreditbase'] + $vk['iSpielfeld'] + $vk['iFex'];?></span></td>
						</tr>
						<?php
					}
					
					?>
						</tbody>
					</table>
					</div>
				</div>
				<?php
			}	
			?>
	    </div>
	</div>
</div>