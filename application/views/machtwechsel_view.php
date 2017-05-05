<div class="container admin">
	<div class="row well">
		<h1>Machtwechsel!</h1>
	</div>
	<div class="row">
		<div class="alert alert-info lead">
			6.5. "Machtwechsel": Ein Kapitän kann einmalig während der Rundfahrt auf einer normalen oder einer Bergetappe 2 eigene unverbrauchte BC-Annahmen einsetzen und dafür einem beliebigen Teammitglied eine zusätzliche BC-Annahme ermöglichen. Der Kapitän erhält dafür eine zusätzliche BC-Abgabe, die er beliebig einsetzen kann. Für den Tag der Abgabe wird die Creditbasis des Kapitäns um -1 Credit gesenkt. 
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
		<button class="btn btn-default" id="submit"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Machtwechsel eintragen</button></a>
	</div>
</div>