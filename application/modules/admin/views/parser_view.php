<div class="container admin">
	<div class="row well">
		<h1>Parser</h1>
	</div>
	<div class="row well">
		<select id="etappen" class="form-control">
		<?php
		foreach	($aEtappen as $k=>$v) {
			$sSelected = ($v['etappen_id'] == $iEtappe) ? ' selected' : '';
			?>
			<option value="<?= $v['etappen_id'];?>" <?= $sSelected;?>><?= $v['etappen_nr'] . '.Etappe';?></option>
			<?php	
		}
		?>
		</select>
	</div>
	<div class="row well">
		<form action="<?= base_url();?>admin/parser/parserResult/<?= $iEtappe;?>" method="post">
		<div class="radio-inline">
		  <label>
		    <input type="radio" name="parser" value="1">
		    Giro
		  </label>
		</div>
		<div class="radio-inline">
		  <label>
		    <input type="radio" name="parser" value="2" checked>
		   Tour/Vuelta
		  </label>
		</div>
		<hr>
		<div class="radio-inline">
		  <label>
		    <input type="radio" name="type" id="type1" value="1" checked>
		    Zeit
		  </label>
		</div>
		<div class="radio-inline">
		  <label>
		    <input type="radio" name="type" id="type2" value="2">
		   Punkte
		  </label>
		</div>
		<div class="radio-inline">
		  <label>
		    <input type="radio" name="type" id="type3" value="3">
		   Bergwertung
		  </label>
		</div>
		<hr>
		<div class="" id="ausreisser">
			<div class="radio-inline">
			  <label>
			    <input type="radio" name="ausreisser" id="ausreisserJa" value="1">
			   Ausreisser Ja
			  </label>
			</div>
			<div class="radio-inline">
			  <label>
			    <input type="radio" name="ausreisser" id="ausreisserNein" value="2" checked="">
			   Ausreisser Nein
			  </label>
			</div>
		</div>
		 <div class="form-group collapse" id='firstHauptfeldDiv'>
			 <hr>
		    <label for="firstHauptfeld">Erster Hauptfeld</label>
		    <input type="text" class="form-control" name="firstHauptfeld">
		  </div>
		<hr>
		<textarea class="form-control" rows="50" name="result"></textarea>
		<input type="submit" class="btn btn-default" value="Resultat parsen">
		</form>
	</div>
</div>