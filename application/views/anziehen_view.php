<div class="container admin">
	<div class="row well">
		<h1>Anziehen!</h1>
	</div>
	<div class="row">
		<div class="alert alert-info lead">
			6.6. "Anziehen": Ein Sprinter kann einmalig während der Rundfahrt auf einer Sprintetappe 2 eigene unverbrauchte BC-Annahmen einsetzen und dafür einem beliebigen Teammitglied eine zusätzliche BC-Annahme ermöglichen. Der Sprinter erhält eine zusätzliche BC-Abgabe und einen zusätzlichen BC, den er diesem Teammitglied geben muss. Für den Tag der Abgabe wird die Creditbasis des Sprinters um -3 Credits gesenkt.
		</div>
	</div>
	<div class="row well">
		<input type="hidden" id="etappen_id" value="<?= $iEtappe;?>">
		<select name="empfaenger" id="empfaenger" class="form-control" style="margin-bottom: 5px">
		<?php
		foreach($aTeamMembers as $k=>$v) {
			?>
			<option id="<?= $v['user_id'];?>"><?= $v['rzname'];?></option>
			<?php
		}	
		?>
		</select>
		<button class="btn btn-default" id="submit"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Anziehen eintragen</button></a>
	</div>
</div>