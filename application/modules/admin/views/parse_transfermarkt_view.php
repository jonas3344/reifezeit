		<div class="container admin">
			<div class="row well">
				<p><strong>Format:</strong> <em>fahrer_id</em>(tab)<em>farhrer_startnummer</em>(tab)<em>fahrer_vorname</em>(tab)<em>fahrer_nation</em>(tab)<em>team_short</em>(tab)<em>fahrer_rundfahrt_credits</em></p>
				<form action="<?= base_url();?>admin/administration/final_parse_transfermarkt" method="post">
					<textarea class="form-control" rows="50" name="fahrer"></textarea>
					<input type="submit" value="Fahrer parsen" class="btn btn-default" style="margin-top:5px;">
				</form>
			</div>
			
		</div>
