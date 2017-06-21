		<div class="container admin">
			<div class="row well">
				Etappen parsen
			</div>
			<form action="<?= base_url();?>admin/stammdaten/parse_etappen_submit" method="post">
			<div class="row well">
				<select name="rundfahrt" class="form-control">
					<?php
					foreach($aRundfahrten as $k=>$v) {
						?>
						<option value="<?= $v['rundfahrt_id'];?>"><?= $v['rundfahrt_bezeichnung'];?></option>
						<?php
					}	
					?>
				</select>
				<div class="checkbox">
				    <label>
				      <input type="checkbox" name="replace"> Etappen ersetzen
				    </label>
				</div>
				<input type="submit" class="btn btn-default" value="Parsen">
			</div>
			
			<div class="row well">
				<p><strong>Format:</strong> <em>etappen_nr</em>(tab)<em>etappen_klassifizierung</em>(tab)<em>etappen_datum</em>(tab)<em>etappen_start_ziel</em>(tab)<em>etappen_distanz</em>(tab)<em>etappen_eingabeschluss</em>(tab)<em>etappen_profil</em></p>
				<textarea class="form-control" rows="30" name="parse_text"></textarea>
				
			</div>
			</form>
		</div>
