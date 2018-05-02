<div class="container admin">

	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase"> Infos</span>
				</div>
				<div class="actions">
				</div>
			</div>
			<div class="portlet-body">
				<h4>Aktuelle Rundfahrt: <?= $this->config->item('sAktuelleRundfahrt');?></h4>
				<p>
					Die Reifezeit ist ein Manager-Spiel der <a href="http://www.cyclingmanager.de" target="_blank">Sattlerei</a>, für dessen Erfindung wir unserem Mitglied <a href="http://www.cyclingmanager.de/profile.php?mode=viewprofile&u=134" target="_blank">luttenberger</a> dankbar sein dürfen.
					Die Reifezeit wird während der drei großen Radrundfahrten der Saison (Giro, Tour, Vuelta) ausgetragen.
					Jeder Manager muss für jede Etappe fünf Fahrer verpflichten, wobei er pro Etappe nur einen Fahrer austauschen und der Gesamtwert seiner fünf Fahrer nie sein Credit-Limit überschreiten darf.
					Ihre Besonderheit als Spiel erhält die Reifezeit durch den Umstand, dass die Fahrer nicht ihre Platzierung, sondern ihre gefahrene Zeit in die Wertung einbringen.</p>
					Du kannst:
					<ul class="teamlist">
						<li><a href="<?= base_url();?>teilnahme/teilnehmer">Die Teilnehmer betrachten</a></li>
						<li><a href="<?= base_url();?>rundfahrt/transfermarkt">Den Transfermarkt betrachten</a></li>
						<li><a href="<?= base_url();?>rundfahrt/etappen">Die Etappen betrachten</a></li>
						<li><a href="<?= base_url();?>rundfahrt/resultate">Die Resultate betrachten</a></li>
						<li><a href="<?= base_url();?>shortlist">Shortlists anlegen</a></li>
						<li><a href="<?= base_url();?>kader/tag">Dein Kader abändern</a></li>
						<li><a href="<?= base_url();?>kader/kaderuebersicht">Die Kaderübersicht betrachten</a></li>
						<li><a href="<?= base_url();?>planung">Planungen anlegen oder bearbeiten</a></li>
						<li><a href="<?= base_url();?>historie/timeline">Deine RZ-Geschichte betrachten</a></li>
					</ul>
					Im Forum findest du:
					<ul class="teamlist">
						<li><a href="http://www.cyclingmanager.de/viewtopic.php?f=57&t=21765" target="_blank">Das Regelwerk</a></li>
						<li><a href="http://www.cyclingmanager.de/viewtopic.php?f=57&t=21760" target="_blank">Den Thread zur Rundfahrt mit allen Resultaten</a></li>
						<li><a href="hhttp://www.cyclingmanager.de/viewtopic.php?f=57&t=11740" target="_blank">Die Ruhmeshalle</a></li>
					</ul>

			</div>
		</div>
	</div>
	
	<div class="row" style="margin-top:10px">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-barcode"></i>
					<span class="caption-subject text-uppercase"> Impressum</span>
				</div>
				<div class="actions">
				</div>
			</div>
			<div class="portlet-body">
				<p>
					<h4>© <a href="http://www.cyclingmanager.de/profile.php?mode=viewprofile&u=50" target="_blank">jonas</a> 2017-<?= date('Y');?></h4>
					Made with/on:
					<ul class="teamlist">
						<li><a href="http://www.apple.com" target="_blank">iMac</a></li>
						<li><a href="http://www.panic.com" target="_blank">Coda</a></li>
						<li><a href="https://www.barebones.com/products/textwrangler/" target="_blank">TextWrangler</a></li>
						<li><a href="http://www.codeigniter.com" target="_blank">CodeIgniter</a></li>
						<li><a href="http://www.jquery.com" target="_blank">jQuery</a></li>
						<li><a href="http://www.getbootstrap.com" target="_blank">Twitter Bootstrap</a></li>
						<li><a href="http://glyphicons.com/" target="_blank">Glyphicons</a></li>
						<li><a href="http://www.iconfinder.com" target="_blank">Iconfinder</a></li>
					</ul>
				</p>
			</div>
		</div>
	</div>

</div>