<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reifezeit - Neues Profil</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url();?>css/bootstrap.css" rel="stylesheet">

  </head>

  <body>

    
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-1 main" style="margin-top:100px;">
          <h1 class="page-header">Die Reifezeit - Neues Profil</h1>
             <form action="<?= base_url();?>login/saveNewProfile" method="post" data-toggle="validator" role="form">
			  <div class="form-group">
			    <label for="sattlerei_user">Dein Sattlerei-Benutzernamen</label>
			    <input type="text" class="form-control" name="sattlerei_user" required>
			  </div>
			   <div class="form-group">
			    <label for="rz_user">Dein Reifezeit-Namen</label>
			    <input type="text" class="form-control" name="rz_user" required>
			  </div>
			  <div class="form-group">
			    <label for="email">Deine E-Mailadresse</label>
			    <input type="email" class="form-control" name="email" required>
			  </div>
			  <div class="form-group">
			    <label for="password">Passwort</label>
				<input type="password" name="password" id="password" class="form-control" required>
			  </div>
			  <div class="form-group">
			    <label for="pw_confirm">Passwort bestätigen</label>
				<input type="password" name="pw_confirm" id="pw_confirm" class="form-control" data-match="#password" required>
			  </div>
			  <button type="submit" class="btn btn-primary">Anmelden</button>
			</form>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?= base_url();?>js/jquery.min.js"></script>
    <script src="<?= base_url();?>js/bootstrap.js"></script>
    <script src="<?= base_url();?>js/frontend/validator.min.js"></script>

  </body>
</html>
