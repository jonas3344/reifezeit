<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> Teams <?= $this->config->item('sAktuelleRundfahrt');?></span>
				</div>
			</div>
			<div class="portlet-body">
				<!--<div class="row">-->
			<?php
				$i=0;
				foreach($data['teams'] as $k=>$v) {
					if ($i%3 == 0) {
						echo '</div><div class="row">';
					}
					?>
						<div class="col-md-4">
							<div class="panel panel-default">
							  <div class="panel-heading text-center" style="background-color: <?= $v['zelle'];?> !important; color: <?= $v['schrift'];?>; background-image: none"><strong><?= $v['name'];?></strong></div>
							  <div class="panel-body text-center">
							      <ul class="list-group">
								    <?php
									foreach($data['fahrer'][$k] as $kF=>$vF) {
										?>
										 <li class="list-group-item"><div class="row"><div class="col-md-4 text-left"><?= $vF['rolle_bezeichnung'];?></div><div class="col-md-8 text-left"><a href="<?= base_url();?>historie/timeline/<?= $vF['id'];?>"><?= $vF['rzname'];?></a></div></div></li>
										<?php
									}   
									?>   
								  </ul>
							  </div>
							</div>
						</div>
						<?php
					$i++;
				}
				?>
				</div>
			</div>
		</div>
	</div>	
</div>