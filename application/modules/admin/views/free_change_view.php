<div class="container admin">
	<div class="row well">
		<h1>Kader verändern</h1>
	</div>
	<div class="row">
		<div class="alert alert-info">
			<table class="table">
				<tr>
					<td width="15%">User:</td>
					<td><?= $aUser['rzname'] . '(' . $aUser['name'] . ')';?></td>
				</tr>
				<tr>
					<td>Etappe:</td>
					<td><?= $aEtappe['etappen_nr'];?>.Etappe</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row well">
		<input type="hidden" id="user_id" value="<?= $aUser['id'];?>">
		<input type="hidden" id="etappen_id" value="<?= $aEtappe['etappen_id'];?>">
		<?php
		$i=1;
		while ($i<6) {
			echo '<strong>Fahrer ' . $i . '</strong>';
			?>
			<select class="form-control" name="fahrer<?=$i;?>" id="fahrer<?=$i;?>">
				<?php
				foreach($aFahrer as $k=>$v) {
					$sSel = '';
					if ($v['fahrer_id'] == $aKader['fahrer' . $i]) {
						$sSel = " selected";
					}
					?>
					<option value="<?=$v['fahrer_id'];?>"<?= $sSel;?>>#<?= $v['fahrer_startnummer'] . ' ' . strtoupper(substr($v['fahrer_vorname'], 0, 1)) . '.' . $v['fahrer_name'] . ' '  . $v['fahrer_rundfahrt_credits'];?></option>
					<?php
				}	
				?>
			</select>
			<?php
			$i++;
		}	
			
			
		?>
	</div>
</div>