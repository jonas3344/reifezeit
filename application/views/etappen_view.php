<div class="container admin">
	<div class="row">
		<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-info-sign"></i>
						<span class="caption-subject text-uppercase"> Alle Etappen: <?= $this->config->item('sAktuelleRundfahrt');?></span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-responsive">
						<table class="table striped">
							<thead>
								<th>#</th>
								<th>Klassifizierung</th>
								<th>Datum</th>
								<th>Start-Ziel</th>
								<th>Distanz</th>
								<th>Eingabeschluss</th>
								<th>Profil</th>
							</thead>
							<tbody>
								<?php
								foreach($aEtappen as $k=>$aE) {
									$sColor = '';
									if ($aE['etappen_klassifizierung'] == 1) {
										$sColor = ' class = success'; 
									} else if ($aE['etappen_klassifizierung'] == 4) {
										$sColor = ' class = danger'; 
									} else if ($aE['etappen_klassifizierung'] == 3 || $aE['etappen_klassifizierung'] == 5 || $aE['etappen_klassifizierung'] == 6) {
										$sColor = ' class = warning'; 
									}
									?>
									<tr<?= $sColor;?>>
										<td><?= $aE['etappen_nr'];?></td>
										<td><?= $aE['klassifizierung_name'];?></td>
										<td><?= $aE['etappen_datum'];?></td>
										<td><?= $aE['etappen_start_ziel'];?></td>
										<td><?= $aE['etappen_distanz'];?></td>
										<td><?= $aE['etappen_eingabeschluss'];?></td>
										<td><a href="<?= base_url();?>img/<?= $aE['etappen_profil'];?>" data-toggle="lightbox"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
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