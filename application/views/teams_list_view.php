<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> Listen Teams</span>
				</div>
				<ul class="nav nav-tabs">
						<li class="active">
							<a href="#t" data-toggle="tab">
							Teilnahmen</a>
						</li>
						<li>
							<a href="#r" data-toggle="tab">
							Rundfahrtensieger</a>
						</li>
						<li>
							<a href="#e" data-toggle="tab">
							Etappensiege</a>
						</li>
						<li>
							<a href="#l" data-toggle="tab">
							Leadertrikots</a>
						</li>
					</ul>

			</div>
			<div class="portlet-body">
				<div class="tab-content">
					<div class="tab-pane active" id="t">
						<div class="panel panel-default panel-table">
							<div class="panel-heading">
								<div class="row">
			                        <div class="col col-xs-6">
			                            <h3 class="panel-title">Anzahl Teilnahmen</h3>
			                        </div>
			                    </div>
							</div>

							<div class="panel-body">
								<div class="table-responsive history_table">
									<table class="table table-striped">
										<thead>
											<th width="5%">Rang</th>
											<th width="30%">Team</th>
											<th>Anzahl Teilnahmen</th>
										</thead>
										<tbody>
											<?php
												foreach($teams as $k=>$v) {
													$sClass = ($v['id'] == $this->session->userdata('user_id')) ? ' class="success"' : '';
													?>
													<tr<?= $sClass;?>>
														<td><?= $v['rang'];?></td>
														<td><a href="<?= base_url();?>historie/teams/<?= $v['rzteam_id'];?>"><?= $v['rzteam_name'];?></a></td>
														<td><?= $v['anzahl'];?></td>
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
					<div class="tab-pane" id="r">
						<div class="panel panel-default panel-table">
							<div class="panel-heading">
								<div class="row">
			                        <div class="col col-xs-4">
			                            <h3 class="panel-title" id="title_gesamtwertung">Gesamtsieger</h3>
			                        </div>
			                        <div class="col col-xs-8 text-right">
			                            <div class="pull-right">
			                                <div class="btn-group" data-toggle="buttons">
			                                    <label class="btn btn-warning btn-filter-gw active" data-target="completed">
			                                        <input type="radio" name="gesamtwertung" id="rang_team_gw" autocomplete="off" checked>
			                                        Gesamtsiege Team
			                                    </label>
			                                    <label class="btn btn-info btn-filter-gw" data-target="completed">
			                                        <input type="radio" name="gesamtwertung" id="rang_gw" autocomplete="off" checked>
			                                        Gesamtsiege Einzeln
			                                    </label>
			                                    <label class="btn btn-success btn-filter-gw" data-target="pending">
			                                        <input type="radio" name="gesamtwertung" id="rang_punkte" autocomplete="off"> Punktwertungen
			                                    </label>
			                                    <label class="btn btn-danger btn-filter-gw" data-target="all">
			                                        <input type="radio" name="gesamtwertung" id="rang_berg" autocomplete="off"> Bergwertungen
			                                    </label>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
							</div>
							<div class="panel-body">
								<div class="table-responsive history_table">
									<table class="table table-striped" id="gesamtsieger_tabelle">
										<thead id="gesamtsieger_tabelle_head">
	
										</thead>
										<tbody id="gesamtsieger_tabelle_body">

										</tbody>		
									</table>
								</div>

							</div>
							
						</div>						
					</div>
					<div class="tab-pane" id="e">
						<div class="panel panel-default panel-table">
							<div class="panel-heading">
								<div class="row">
			                        <div class="col col-xs-6">
			                            <h3 class="panel-title">Anzahl Etappensiege</h3>
			                        </div>
			                    </div>
							</div>
							<div class="panel-body">
								<div class="table-responsive history_table">
									<table class="table table-striped">
										<thead>
											<th width="5%">Rang</th>
											<th width="30%">User</th>
											<th>Anzahl Etappensiege</th>
										</thead>
										<tbody>
											<?php
												foreach($aEts as $k=>$v) {
													$sClass = ($v['id'] == $this->session->userdata('user_id')) ? ' class="success"' : '';
													?>
													<tr<?= $sClass;?>>
														<td><?= $v['rang'];?></td>
														<td><a href="<?= base_url();?>historie/timeline/<?= $v['id'];?>"><?= $v['name'];?></a></td>
														<td><?= $v['anzahl'];?></td>
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
					<div class="tab-pane" id="l">
						<div class="panel panel-default panel-table">
							<div class="panel-heading">
								<div class="row">
			                        <div class="col col-xs-6">
			                            <h3 class="panel-title" id="title_leader">Tage im Leadertrikot</h3>
			                        </div>
			                        <div class="col col-xs-6 text-right">
			                            <div class="pull-right">
			                                <div class="btn-group" data-toggle="buttons">
			                                    <label class="btn btn-warning btn-filter-gw active" data-target="completed">
			                                        <input type="radio" name="leadertrikot" id="1" autocomplete="off" checked>
			                                        Leadertrikots
			                                    </label>
			                                    <label class="btn btn-success btn-filter-gw" data-target="pending">
			                                        <input type="radio" name="leadertrikot" id="2" autocomplete="off"> Punktetrikots
			                                    </label>
			                                    <label class="btn btn-danger btn-filter-gw" data-target="all">
			                                        <input type="radio" name="leadertrikot" id="3" autocomplete="off"> Bergtrikots
			                                    </label>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
							</div>
							<div class="panel-body">
								<div class="table-responsive history_table">
									<table class="table table-striped" id="leadertrikots_tabelle">
										<thead>
											<th width="5%">Rang</th>
											<th width="30%">User</th>
											<th>Anzahl Trikots</th>
										</thead>
										<tbody>

										</tbody>		
									</table>
								</div>

							</div>
							
						</div>					
						
					</div>
				</div>
			</div>
		</div>
	</div>

	
</div>