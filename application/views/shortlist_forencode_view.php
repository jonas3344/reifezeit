<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> Shortlists</span>
				</div>
				<div class="actions">
					<a class="btn btn-default back">
						Zurück
					</a>
					<a class="btn btn-default copy" data-clipboard-target="#forencode">
						<span class="glyphicon glyphicon-copy" aria-hidden="true"></span> Kopieren
					</a>
				</div>
			</div>
			<div class="portlet-body">
				<textarea class="form-control" rows="20" id="forencode"><?= $sOutput;?></textarea>
			</div>
		</div>
	</div>
<!--
	<div class="row well">
		<h1>Export Shortlist!</h1>
	</div>
	<div class="row well">
		<button class="btn btn-default back">Zurück</button>
		<button class="btn btn-default copy" data-clipboard-target="#forencode"><span class="glyphicon glyphicon-copy" aria-hidden="true"></span> Kopieren</button>
	</div>
	<div class="row well">
		<textarea class="form-control" rows="20" id="forencode"><?= $sOutput;?></textarea>
	</div>
-->
</div>