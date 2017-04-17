<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reifezeit - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url();?>css/bootstrap.css" rel="stylesheet">

  </head>

  <body>

    
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-1 main" style="margin-top:100px;">
          <h1 class="page-header">Die Reifezeit - Login</h1>
          <button class="btn btn-default">Neues Profil erstellen</button> <button class="btn btn-default">Passwort vergessen</button>
          <hr>
          <?php if (strlen($sError) > 0) {
	          ?>
	          <div class="alert alert-danger"><?= $sError; ?></div>
	          <?php
          }
          ?>
          <?= form_open('login');?>
			  <div class="form-group">
			    <label for="user">Benutzername</label>
			    <select name="username" class="form-control">
			    <?php
					foreach($aTeilnehmer as $k=>$v) {
						?>
						<option value="<?= $v['id'];?>"><?= $v['rzname'];?></option>
						<?php
					}
				?>
			    </select>
			  </div>
			  <div class="form-group">
			    <label for="Passwort">Passwort</label>
				<?php
					$data = array(
					        'name'          => 'password',
					        'id'            => 'password',
					        'class'         => 'form-control'
					);    
				    echo form_password($data);
				?>
			  </div>
			  <button type="submit" class="btn btn-default">Anmelden</button>
			</form>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?= base_url();?>js/jquery.min.js"></script>
    <script src="<?= base_url();?>js/bootstrap.js"></script>

  </body>
</html>
