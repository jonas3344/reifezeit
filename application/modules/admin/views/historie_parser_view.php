		<div class="container admin">
			<form method="post" action="<?= base_url();?>admin/historie/parseData">
			<div class="row well">
				
				<select name="rundfahrt" class="form-control">
				<?php
				foreach	($aRundfahrten as $k=>$v) {
					?>
					<option value="<?= $v['id'];?>"><?= $v['bezeichnung'] . ' ' . $v['jahr'];?></option>
					<?php	
				}
				?>
				</select>
			</div>
			<div class="row well">
				<select name="action" class="form-control">
					<option value="teilnehmer">Teilnehmer</option>
					<option value="team">Team</option>
					<option value="etappen">Etappen</option>
					<option value="zusatz">Zusatzetappen</option>
				</select>
			</div>
			<div class="row well">
				<textarea class="form-control" name="data" rows="30"></textarea>
				<input type="submit" class="btn btn-primary" value="Parsen" style="margin-top:5px">
			</div>
			</form>
		</div>
