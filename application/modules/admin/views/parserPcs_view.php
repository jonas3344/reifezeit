<div class="container admin">
	<div class="row well">
		<h1>Parser www.procyclinstats.com</h1>
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
		<form action="<?= base_url();?>admin/parser/pcsParser/<?= $iEtappe;?>" method="post">
		<div class="form-group">
		    <label for="url">URL</label>
		    <input type="text" class="form-control" id="url" placeholder="https://www.procyclingstats.com" name="url">
		</div>
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
		<input type="submit" class="btn btn-default" value="Resultat parsen">
		</form>
	</div>
</div>