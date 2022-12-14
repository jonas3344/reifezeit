<div class="container admin">
	<div class="row">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span class="caption-subject text-uppercase">Dein Profil</span>
				</div>
			</div>
			<div class="portlet-body">
					<p>
						Hier kannst Du Deinen RZ-Namen sowie Dein Passwort anpassen. Bitte beachte, dass der RZ-Namen während einer Rundfahrt nicht mehr verändert werden kann.
							<br><br>
						Zur Erinnerung der Passus aus dem Reglement:
							<br><br>
						9. Fahrernamen
						<br><br>
						9.1. Es wird erwartet, dass sich die Reifezeitfahrer einen Namen zulegen, der<br>
						a) aus einem oder mehreren Vornamen und<br>
						b) einem Familiennamen besteht, wobei der<br>
						c) Name in seiner Gesamtheit unbedingt den Sattlerei-Namen des Users erkennbar machen sollte.<br><br>
						
						9.2. Alle Namen beginnen mit Großbuchstaben.
						<br><br>
						9.3. Die Rennleitung darf Namen ändern, wenn die Spieler Punkt 9.1. oder Punkt 9.2. nicht genügend beachten.
					</p>
					<table class="table">
						<tr>
							<td width="20%"><strong>Dein RZ-Namen:</strong></td>
							<td><a href="#" id="rz_name" class="rz_name" data-type="text" data-pk="<?= $this->session->userdata('user_id');?>" data-url="<?= base_url();?>profil/setUserData/rzname" data-title="Set RZ-Name"><?= $aUser['rzname'];?></a></td>
						</tr>
						<tr>
							<td width="20%"><strong>Deine E-Mail-Adresse:</strong></td>
							<td><a href="#" id="email" class="rz_name" data-type="text" data-pk="<?= $this->session->userdata('user_id');?>" data-url="<?= base_url();?>profil/setUserData/email" data-title="Set E-Mail"><?= $aUser['email'];?></a></td>
						</tr>
					</table>
					<hr>
					<div class="form-group">
					    <label for="pwOld">Altes Passwort</label>
						<?php
							$data = array(
							        'name'          => 'pwOld',
							        'id'            => 'pwOld',
							        'class'         => 'form-control'
							);    
						    echo form_password($data);
						?>
					</div>
					<div class="form-group">
						<label for="pwNew">Neues Passwort</label>
						<?php
							$data = array(
							        'name'          => 'pwNew',
							        'id'            => 'pwNew',
							        'class'         => 'form-control'
							);    
						    echo form_password($data);
						?>
					</div>
					<div class="form-group">
						<label for="pwNewConfirm">Neues Passwort Bestätigen</label>
						<?php
							$data = array(
							        'name'          => 'pwNewConfirm',
							        'id'            => 'pwNewConfirm',
							        'class'         => 'form-control'
							);    
						    echo form_password($data);
						?>
					</div>
						<button id="changePw" class="btn btn-default">Passwort abändern</button>
					</div>
			
				</div>

		</div>
	</div>
<!--
	<div class="row well">
		<h1>Dein Profil</h1>
	</div>
	<div class="row">
		<div class="alert alert-info">
			Hier kannst Du Deinen RZ-Namen sowie Dein Passwort anpassen. Bitte beachte, dass der RZ-Namen während einer Rundfahrt nicht mehr verändert werden kann.
				<br><br>
			Zur Erinnerung der Passus aus dem Reglement:
				<br><br>
			9. Fahrernamen
			<br><br>
			9.1. Es wird erwartet, dass sich die Reifezeitfahrer einen Namen zulegen, der<br>
			a) aus einem oder mehreren Vornamen und<br>
			b) einem Familiennamen besteht, wobei der<br>
			c) Name in seiner Gesamtheit unbedingt den Sattlerei-Namen des Users erkennbar machen sollte.<br><br>
			
			9.2. Alle Namen beginnen mit Großbuchstaben.
			<br><br>
			9.3. Die Rennleitung darf Namen ändern, wenn die Spieler Punkt 9.1. oder Punkt 9.2. nicht genügend beachten.
		</div>
	</div>
	<div class="row well">
		<table class="table">
			<tr>
				<td width="20%"><strong>Dein RZ-Namen:</strong></td>
				<td><a href="#" id="rz_name" class="rz_name" data-type="text" data-pk="<?= $this->session->userdata('user_id');?>" data-url="<?= base_url();?>profil/setUserData/rzname" data-title="Set RZ-Name"><?= $aUser['rzname'];?></a></td>
			</tr>
			<tr>
				<td width="20%"><strong>Deine E-Mail-Adresse:</strong></td>
				<td><a href="#" id="rz_name" class="rz_name" data-type="text" data-pk="<?= $this->session->userdata('user_id');?>" data-url="<?= base_url();?>profil/setUserData/email" data-title="Set RZ-Name"><?= $aUser['email'];?></a></td>
			</tr>
		</table>
		<hr>
		<div class="form-group">
		    <label for="pwOld">Altes Passwort</label>
			<?php
				$data = array(
				        'name'          => 'pwOld',
				        'id'            => 'pwOld',
				        'class'         => 'form-control'
				);    
			    echo form_password($data);
			?>
			<label for="pwNew">Neues Passwort</label>
			<?php
				$data = array(
				        'name'          => 'pwNew',
				        'id'            => 'pwNew',
				        'class'         => 'form-control'
				);    
			    echo form_password($data);
			?>
			<label for="pwNewConfirm">Neues Passwort Bestätigen</label>
			<?php
				$data = array(
				        'name'          => 'pwNewConfirm',
				        'id'            => 'pwNewConfirm',
				        'class'         => 'form-control'
				);    
			    echo form_password($data);
			?>
			<button id="changePw" class="btn btn-default">Passwort abändern</button>
		</div>

	</div>
-->
</div>