<div class="container admin">
	<div class="row">
		<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-info-sign"></i>
						<span class="caption-subject text-uppercase"> Alle Rundfahrten</span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-responsive history_table">
						<table class="table table-striped">
							<thead>
								<th>Rundfahrt</th>
							</thead>
							<tbody>
								<?php
									foreach($aRundfahrten as $k=>$v) {
									?>
									<tr>
										<td><a href="<?= base_url();?>historie/rundfahrt/<?= $v['id'];?>"><?= $v['bezeichnung'] . ' ' . $v['jahr'];?></a></td>
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