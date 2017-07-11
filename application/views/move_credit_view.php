<div class="container admin">
	<div class="row well">
		<h1>Schonen!</h1>
	</div>
	<div class="row">
		<div class="alert alert-info">	
			Es ist möglich, Basiscredits in eine Folgeetappe zu verschieben. Dazu muss ein Fahrer aber eine Creditbasis von mindestens 31 Credits besitzen. Die Creditbasis verringert sich für diese Etappe um <strong>-2</strong>, die der nachfolgenden Etappe erhöht sich um <strong>+1</strong>, unabhängig von der Etappenklassierung. Dies kann <strong>einmal</strong> pro Spieler und Rundfahrt erfolgen.
		</div>
	</div>
	<div class="row well">
		<input type="hidden" id="etappen_id" value="<?= $aEtappe['etappen_id'];?>">
		<table>
			<tr>
				<td><button class="btn btn-default btn-lg disabled"><?= $aEtappe['etappen_nr'];?>.Etappe</button></td>
				<td rowspan="2" style="padding-left: 10px; padding-right: 10px;"><button class="btn btn-default btn-lg" id="move">=></button></td>
				<td><button class="btn btn-default btn-lg disabled"><?= $aEtappe['etappen_nr'] + 1;?>.Etappe</button></td>
			</tr>
			<tr>
				<td align="center" style="padding-top: 10px;"><button class="btn btn-default btn-lg disabled">-2</button></td>
				<td align="center" style="padding-top: 10px;"><button class="btn btn-default btn-lg disabled">+1</button></td>
			</tr>
		
	</div>
</div>