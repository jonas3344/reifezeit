		<div class="container admin">
			<div class="row well">
				<h1><?= ($iId == -1) ? 'Neue Rundfahrt' : 'Rundfahrt bearbeiten';?></h1>
			</div>
			<div class="row well">
				<?php
					if (strlen(validation_errors()) > 0) {
						?>
						<div class="alert alert-danger"><?= validation_errors();?></div>
					<?php } ?>
				<?= form_open('admin/stammdaten/edit_rundfahrt/' . $iId);?>
					<div class="form-group">
				    	<label for="rundfahrt_bezeichnung">Rundfahrtsbezeichnung</label>
				    	<?php
						$data = array(
						        'name'          => 'rundfahrt_bezeichnung',
						        'id'            => 'rundfahrt_bezeichnung',
						        'class'         => 'form-control',
						        'value'			=> set_value('rundfahrt_bezeichnung', $aRundfahrt['rundfahrt_bezeichnung'])
						);    
					    echo form_input($data);
						?>
					</div>
					<div class="form-group">
				    	<label for="rundfahrt_jahr">Jahr</label>
				    	<?php
						$data = array(
						        'name'          => 'rundfahrt_jahr',
						        'id'            => 'rundfahrt_jahr',
						        'class'         => 'form-control',
						        'value'			=> set_value('rundfahrt_jahr', $aRundfahrt['rundfahrt_jahr'])
						);    
					    echo form_input($data);
						?>
					</div>
					<div class="form-group">
				    	<label for="regeln">Regeln</label>
				    	<textarea name="regeln" id="regeln" class="form-control" rows="22"><?= $aRegeln['regeln'];?></textarea>
					</div>
					<button type="submit" class="btn btn-default">Absenden</button>
				<?= form_close(); ?>
			</div>
		</div>