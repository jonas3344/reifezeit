<div class="container admin">
	<div class="row well">
		<h1>Herzlich Willkommen!</h1>
		<h2>Aktuelle Rundfahrt: <?= $this->config->item('sAktuelleRundfahrt');?>
	</div>
	<div class="row well">
		<a href="<?= base_url();?>kader/tag"><button class="btn btn-default">Heutiger Kader</button></a><br>
		<a href="<?= base_url();?>kader/kaderuebersicht"><button class="btn btn-default">Kaderübersicht</button></a><br>
		<a href="<?= base_url();?>rundfahrt/transfermarkt"><button class="btn btn-default">Transfermarkt</button></a>
	</div>
</div>