		<div class="container admin">
			<form method="post" action="<?= base_url();?>admin/historie/submitData">
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
				<input type="submit" class="btn btn-primary" value="Historie eintragen" style="margin-top:5px">
			</div>
			</form>
		</div>
