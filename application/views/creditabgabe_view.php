<div class="container admin">
	<div class="row well">
		<h1>Creditabgabe <?= $aEtappe['etappen_nr'];?>.Etappe</h1>
	</div>
	<div class="row">
		<div class="alert alert-info lead">
			Creditabgaben können immer nur für die aktuelle Etappe getätigt werden. Du bist selbst dafür verantwortlich, dass du nicht mehr Credits abgibst als du gewonnen hast.
		</div>
	</div>
	<div class="row well">
		<select name="empfaenger" id="empfaenger" class="form-control" style="margin-bottom: 5px">
		<?php
		foreach($aTeamMembers as $k=>$v) {
			?>
			<option id="<?= $v['user_id'];?>"><?= $v['rzname'];?></option>
			<?php
		}	
		?>
		</select>
		<button class="btn btn-default" id="submit"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Creditabgabe eintragen</button></a>
	</div>
</div>