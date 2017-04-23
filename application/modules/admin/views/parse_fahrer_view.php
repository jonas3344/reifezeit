<div class="container admin">
	<div class="row well">
		<h1>Team updaten - Fahrer parsen</h1>
		<h2><?= $aTeam['team_name'];?>
	</div>
	<div class="row well">
		<form action="<?= base_url();?>admin/stammdaten/parseFahrerResult/<?= $aTeam['team_id'];?>" method="post">
		<textarea class="form-control" rows="30" name="fahrer"></textarea>
		<input type="submit" class="btn btn-default" value="Fahrer parsen">
		</form>
	</div>
</div>