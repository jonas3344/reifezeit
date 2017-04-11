<div class="container admin">
	<div class="row well">
		<h1>Transfermarkt</h1>
	</div>
	<div class="row well">
		<table class="table">
			<tr>
				<td colspan="3" align="right">
					<a href="<?= base_url();?>admin/administration/addTeam/"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Team hinzufügen</button></a> 
					<a href="<?= base_url();?>admin/administration/orderTeams/"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span> Teams sortieren</button></a>
					<a href="#"><button type="button" class="btn btn-default"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Transfermarkt checken</button></a>
					<a href="#"><button type="button" class="btn btn-default" id='openTransfermarkt'><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Transfermarkt <?= ($iFreigabeTransfermarkt == 1) ? 'schliessen' : 'öffnen';?></button></a>
				</td>
			</tr>
		</table>
		<table class="table">
			<?php
			foreach($aTeams as $k=>$v) {
			?>	
				<thead>
					<th colspan="5"><?= $v['team_name'];?> | <a href="<?= base_url();?>admin/administration/addFahrerTransfermarkt/<?= $v['team_id'];?>"><button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Fahrer hinzufügen</button></a> <a href="#"><button type="button" class="btn btn-default btn-sm remove_team" id="<?= $v['team_id'];?>"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Team entfernen</button></a></th>
				</thead>
			<?php	
				foreach($v['fahrer'] as $kf => $aFahrer) {
					$sColor = ($aFahrer['ausgeschieden'] == 1) ? 'danger' : '';
				?>
					<tr class="<?= $sColor;?>">
						<td width="10%"><a href="#" id="fahrerstartnummer" class="fahrerstartnummer" data-type="text" data-pk="<?= $aFahrer['fahrer_id'];?>" data-url="<?= base_url();?>admin/administration/setFahrerStartnummer/" data-title="Set Startnummer"><?= $aFahrer['fahrer_startnummer'];?></a></td>
						<td><?= $aFahrer['fahrer_vorname'] . ' ' . $aFahrer['fahrer_name'];?></td>
						<td><?= $aFahrer['fahrer_nation'];?></td>
						<td><a href="#" id="fahrercredit" class="fahrercredit" data-type="text" data-pk="<?= $aFahrer['fahrer_id'];?>" data-url="<?= base_url();?>admin/administration/setFahrerCredits/" data-title="Set Credit"><?= $aFahrer['fahrer_rundfahrt_credits'];?></a></td>
						<td>
							<a href="#"><button type="button" class="btn btn-default btn-sm remove" id="<?= $aFahrer['fahrer_id'];?>"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></a>
						</td>
					</tr>
				<?php	
				}
			}	
			?>
		</table>
	</div>
</div>