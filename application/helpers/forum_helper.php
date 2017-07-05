<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
if ( ! function_exists('createResultKaderpost')) 
{
	function createResultKaderpost($aResultData, $aExFuehrung) {
		$sOutputDay = '';
		$sOutputGesamt = '';
		
		$sOutputDay .= "[b][size=18]" . $aResultData['etappe']['etappen_nr'] . ". Etappe[/size][/b]<br><br>";
		$sOutputDay .= "[b]T A G E S E I N Z E L W E R T U N G[/b]";
		$sOutputDay .= "[table fontsize=9][mrow color=silver]	Platz	[mcol]	Tageseinzelwertung	[mcol]	Sattlerei-Name	[mcol]	Team	[mcol]	Status	[mcol]	Zeit	[mcol]	BCs";
		$i=0;
		
		foreach($aResultData['stage_result'] as $k=>$v) {
			$user = $aResultData['teilnehmer'][$k];
			$rang = $v['rang'];
			if ($i>1) {
				if (array_values($aResultData['stage_result'])[$i]['zeit'] == array_values($aResultData['stage_result'])[$i-1]['zeit']) {
					$rang = "-";
				}
				
			}
			if ($k == $aExFuehrung['id_fuhrender_gesamt']) {
				$color_row = "gold";
			} else if ($k == $aExFuehrung['id_fuhrender_punkte']) {
				$color_row = "limegreen";
			} else if ($k == $aExFuehrung['id_fuhrender_berg']) {
				$color_row = "red";
			} else {
				$color_row = (($i%2) == 0) ? "#eeeeee" : "#ffffff";
			}
			$sOutputDay .= "[mrow color=" . $color_row . "] " . $rang . " ";
			$sOutputDay .= "[mcol] " . $user['rzname'] . " [mcol] " . $user['name'];
			if ($user['rzteam_short'] != 'none') {
				$sOutputDay .= "[mcol color=" . $user['color_code_zelle'] . "][color=" . $user['color_code_schrift'] . "]" . $user['rzteam_short'] . "[/color]";
			} else {
				$sOutputDay .= "[mcol]";
			}
			
			$sOutputDay .= "[mcol color=" . $user['color_code_rolle'] . "]" . $user['rolle_bezeichnung'];
			$sOutputDay .= "[mcol]" . _convertSeconds($v['zeit']) . "[mcol]" . $v['bc'];
			
			$i++;
		}
		
		$sOutputDay .= "[/table]<br><br><br>";
		$sOutputDay .= "[b]T A G E S T E A M W E R T U N G[/b]";
		$sOutputDay .= "[table fontsize=9][mrow color=silver]	Platz	[mcol]	Tagesteamwertung	[mcol]	Zeit";
		
		$i = 0;
		
		$id_fuhrendes_team = array_keys($aResultData['overall_team'])[0];
		
		foreach($aResultData['stage_team'] as $k=>$v) {
			$team = $aResultData['teams'][$k];
			if ($k == $id_fuhrendes_team) {
				$color_row = "gold";
			} else {
				$color_row = (($i%2) == 0) ? "#eeeeee" : "#ffffff";
			}
			
			$rang = $i + 1;
			if ($i>1) {
				if (array_values($aResultData['stage_team'])[$i]['zeit'] == array_values($aResultData['stage_team'])[$i-1]['zeit']) {
					$rang = "-";
				}		
			}
			$sOutputDay .= "[mrow color=" . $color_row . "] " . $rang . " ";
			$sOutputDay .= "[mcol color=" . $team['color_code_zelle'] . "][color=" . $team['color_code_schrift'] . "]" . $team['rzteam_name'] . "[/color]";
			$sOutputDay .= "[mcol]" . _convertSeconds($v['zeit']);
			$i++;
		}
		
		$sOutputDay .= "[/table]";
		
		$sOutputGesamt .= "[b]G E S A M T E I N Z E L W E R T U N G[/b]";
		$sOutputGesamt .= "[table fontsize=9][mrow color=#eeee33]	Platz	[mcol]	Gesamteinzelwertung	[mcol]	Sattlerei-Name	[mcol]	Team	[mcol]	Status	[mcol]	Zeit";
		
		$i = 0;
		foreach($aResultData['overall'] as $k=> $v) {
			$user = $aResultData['teilnehmer'][$k];
			$rang = $v['rang'];
			if ($i>1) {
				if (array_values($aResultData['overall'])[$i]['zeit'] == array_values($aResultData['overall'])[$i-1]['zeit']) {
					$rang = "-";
				}
				
			}
		
			$color_row = (($i%2) == 0) ? "#eeeeee" : "#ffffff";
			$sOutputGesamt .= "[mrow color=" . $color_row . "] " . $rang . " ";
			$sOutputGesamt .= "[mcol] " . $user['rzname'] . " [mcol] " . $user['name'];
			if ($user['rzteam_short'] != 'none') {
				$sOutputGesamt .= "[mcol color=" . $user['color_code_zelle'] . "][color=" . $user['color_code_schrift'] . "]" . $user['rzteam_short'] . "[/color]";
			} else {
				$sOutputGesamt .= "[mcol]";
			}
			$sOutputGesamt .= "[mcol color=" . $user['color_code_rolle'] . "]" . $user['rolle_bezeichnung'];
			$sOutputGesamt .= "[mcol]" . _convertSeconds($v['zeit']);
			$i++;
		}
		
		$sOutputGesamt .= "[/table]<br><br>";
		
		$sOutputGesamt .= "[b]G E S A M T T E A M W E R T U N G[/b]";
		$sOutputGesamt .= "[table fontsize=9][mrow color=#8888ff]	Platz	[mcol]	Gesamtteamwertung	[mcol]	Zeit";

		$i = 0;
		foreach($aResultData['overall_team'] as $k=>$v) {
			$team = $aResultData['teams'][$k];
			$color_row = (($i%2) == 0) ? "#eeeeee" : "#ffffff";
			
			$rang = $i + 1;
			if ($i>1) {
				if (array_values($aResultData['overall_team'])[$i]['zeit'] == array_values($aResultData['overall_team'])[$i-1]['zeit']) {
					$rang = "-";
				}		
			}
			$sOutputGesamt .= "[mrow color=" . $color_row . "] " . $rang . " ";
			$sOutputGesamt .= "[mcol color=" . $team['color_code_zelle'] . "][color=" . $team['color_code_schrift'] . "]" . $team['rzteam_name'] . "[/color]";
			$sOutputGesamt .= "[mcol]" . _convertSeconds($v['zeit']);
			$i++;
		
		}
		
		$sOutputGesamt .= "[/table]<br><br>";
		
		$sOutputGesamt .= "[b]G E S A M T P U N K T E W E R T U N G[/b]";
		$sOutputGesamt .= "[table fontsize=9][mrow color=#00cc00]	Platz	[mcol]	Gesamtpunktewertung	[mcol]	Sattlerei-Name	[mcol]	Team	[mcol]	Status	[mcol]	Punkte";
		$i = 0;
		foreach($aResultData['overall_points'] as $k=>$v) {
			if ($v['punkte'] > 0) {
				$user = $aResultData['teilnehmer'][$k];
				
				$color_row = (($i%2) == 0) ? "#eeeeee" : "#ffffff";
				$rang = $v['rang'];
				if ($i>1) {
					if (array_values($aResultData['overall_points'])[$i]['punkte'] == array_values($aResultData['overall_points'])[$i-1]['punkte']) {
						$rang = "-";
					}
					
				}
				$sOutputGesamt .= "[mrow color=" . $color_row . "] " . $rang . " ";
				$sOutputGesamt .= "[mcol] " . $user['rzname'] . " [mcol] " . $user['name'];
				if ($user['rzteam_short'] != 'none') {
					$sOutputGesamt .= "[mcol color=" . $user['color_code_zelle'] . "][color=" . $user['color_code_schrift'] . "]" . $user['rzteam_short'] . "[/color]";
				} else {
					$sOutputGesamt .= "[mcol]";
				}
				$sOutputGesamt .= "[mcol color=" . $user['color_code_rolle'] . "]" . $user['rolle_bezeichnung'];
				$sOutputGesamt .= "[mcol]" . $v['punkte'];
				$i++;
			}
		}
		
		$sOutputGesamt .= "[/table]<br><br>";
		
		if (array_values($aResultData['overall_berg'])[0]['berg'] > 0) {
			$sOutputGesamt .= "[b]G E S A M T B E R G W E R T U N G[/b]";
			$sOutputGesamt .= "[table fontsize=9][mrow color=#ff3333]	Platz	[mcol]	Gesamtbergwertung	[mcol]	Sattlerei-Name	[mcol]	Team	[mcol]	Status	[mcol]	Punkte";
			$i = 0;
			foreach($aResultData['overall_berg'] as $k=>$v) {
				if ($v['berg'] > 0) {
					$user = $aResultData['teilnehmer'][$k];
					
					$color_row = (($i%2) == 0) ? "#eeeeee" : "#ffffff";
					$rang = $v['rang'];
					if ($i>1) {
						if (array_values($aResultData['overall_berg'])[$i]['berg'] == array_values($aResultData['overall_berg'])[$i-1]['berg']) {
							$rang = "-";
						}
						
					}
					$sOutputGesamt .= "[mrow color=" . $color_row . "] " . $rang . " ";
					$sOutputGesamt .= "[mcol] " . $user['rzname'] . " [mcol] " . $user['name'];
					if ($user['rzteam_short'] != 'none') {
						$sOutputGesamt .= "[mcol color=" . $user['color_code_zelle'] . "][color=" . $user['color_code_schrift'] . "]" . $user['rzteam_short'] . "[/color]";
					} else {
						$sOutputGesamt .= "[mcol]";
					}
					$sOutputGesamt .= "[mcol color=" . $user['color_code_rolle'] . "]" . $user['rolle_bezeichnung'];
					$sOutputGesamt .= "[mcol]" . $v['berg'];
					$i++;
				}
			}
		}
		
		$sOutputGesamt .= "[/table]";

		
		$aOutput['sOutputDay'] = $sOutputDay;
		$aOutput['sOutputGesamt'] = $sOutputGesamt; 
		return $aOutput;
	}
}

if ( ! function_exists('createRuhmeshalle')) 
{
	function createRuhmeshalle($aResultData) {
		$sOutput = "[table fontsize=9][mrow color=#1C1C1C][color=white]Etappe[/color][mcol color=#eedabc]Etappensieg[mcol color=#ffffcc]Gesamtwertung[mcol color=#eedabc]Team Etappe[mcol color=#5384dc]Team Gesamt[mcol color=#ccffcc]Punkte-Trikot[mcol color=#ffcccc]Berg-Trikot\n";
		
		foreach($aResultData['aEtappen'] as $k=>$v) {
			$sStageColor = '';
			if ($v['aStage']['etappen_klassifizierung'] == 1) {
				$sStageColor = 'lime';
			} else if ($v['aStage']['etappen_klassifizierung'] == 2) {
				$sStageColor = 'grey';
			} else if ($v['aStage']['etappen_klassifizierung'] == 4) {
				$sStageColor = 'red';
			} else {
				$sStageColor = 'yellow';
			}
			$sOutput .= "[mrow color=" . $sStageColor . "]" . $v['aStage']['etappen_nr'];
			$sOutput .= "[mcol color=#fffcde]" . $aResultData['aTeilnehmer'][$v['aFirst']['user_id']]['rzname'] . " (" . $aResultData['aTeilnehmer'][$v['aFirst']['user_id']]['rzteam_short'] . ")";
			$sOutput .= "[mcol color=#eeee33]" . $aResultData['aTeilnehmer'][$v['aLeader']]['rzname'] . " (" . $aResultData['aTeilnehmer'][$v['aLeader']]['rzteam_short'] . ")";
			$sOutput .= "[mcol color=#fffcde]" . $aResultData['aTeams'][$v['aTeamStage']]['rzteam_name'];
			$sOutput .= "[mcol color=#1177FF]" . $aResultData['aTeams'][$v['aTeamOverall']]['rzteam_name'];
			if ($v['aPoints'] == '-') {
				$sOutput .= "[mcol color=#00cc00]" . '-';
			} else {
				$sOutput .= "[mcol color=#00cc00]" . $aResultData['aTeilnehmer'][$v['aPoints']]['rzname'] . " (" . $aResultData['aTeilnehmer'][$v['aPoints']]['rzteam_short'] . ")";
			}
			if ($v['aBerg'] == '-') {
				$sOutput .= "[mcol color=#ff3333]" . '-';
			} else {
				$sOutput .= "[mcol color=#ff3333]" . $aResultData['aTeilnehmer'][$v['aBerg']]['rzname'] . " (" . $aResultData['aTeilnehmer'][$v['aBerg']]['rzteam_short'] . ")";
			}
			$sOutput .= "\n";
		}
		$sOutput .= "[/table]";
		return $sOutput;
	}
}

if ( ! function_exists('createForumKaderpost'))
{
	
	function createForumKaderpost($aKader, $aCa, $aWechsel) {
		$sOutput = '';	
		
		$sOutput .= "[b][size=18]KADERÜBERSICHT " . $aKader['etappe']['etappen_nr'] . ".Etappe[/size][/b]<br><br>";
		$sOutput .= "[table fontsize=9][mcol]Name[mcol]Team[mcol]Zeit[mcol]Fahrer 1[mcol][mcol]Fahrer 2[mcol][mcol]Fahrer 3[mcol][mcol]Fahrer 4[mcol][mcol]Fahrer 5[mcol][mcol]<br>";
		
		foreach($aKader['teilnehmer'] as $aTeilnehmer) {
			if ($aTeilnehmer['rolle_id'] == 1) {
				$s_color_player = "pink";
			} else if ($aTeilnehmer['rolle_id'] == 2) {
			    $s_color_player = "lightgray";
			} else if ($aTeilnehmer['rolle_id'] == 3) {
			    $s_color_player = "gray";
			} else if ($aTeilnehmer['rolle_id'] == 4) {
			    $s_color_player = "#3399ff";
			} else if ($aTeilnehmer['rolle_id'] == 5) {
			    $s_color_player = "white";
			} else if ($aTeilnehmer['rolle_id'] == 6) {
			    $s_color_player = "orange";
			} else if ($aTeilnehmer['rolle_id'] == 7) {
			    $s_color_player = "limegreen";
			} else if ($aTeilnehmer['rolle_id'] == 8) {
			    $s_color_player = "#ffff99";
			}
			
			$sOutput .= "[mrow color=" . $s_color_player . "]" . $aTeilnehmer['rzname'];
			if (isset($aTeilnehmer['team'])) {
				$sOutput .= "[mcol color=" . $aTeilnehmer['team']['color_code_zelle'] . "][color=" . $aTeilnehmer['team']['color_code_schrift'] . "]" . $aTeilnehmer['team']['rzteam_short'] . "[/color]";
			} else {
				$sOutput .= "[mcol]";
			}
			
			$sOutput .= "[mcol]" . _convertSeconds($aTeilnehmer['gw']);
			$sum = 0;
			foreach($aTeilnehmer['kader'] as $k) {
				$color = ($k['change'] == 1) ? "#ff9999" : "#ffffff";
				$fahrer_name = ($k['fahrer_name'] == "Martin") ? substr($k['fahrer_vorname'], 0, 1) . "." . $k['fahrer_name'] : $k['fahrer_name'];
				$sOutput .= "[mcol color=" . $color . "]" . $fahrer_name . "[mcol color=#ffffff]" . $k['fahrer_rundfahrt_credits'];
				$sum += $k['fahrer_rundfahrt_credits'];
			}
			$sOutput .= "[mcol color=#ffffff][b]" . $sum . "[/b]<br>";
		}
		$sOutput .= "[/table]<br><br><br>";
		
		$sOutput .= "[b][size=18]Creditabgaben[/size][/b]<br><br>";
		
		
		if (count($aCa) == 0) {
			$sOutput .= "Heute keine Creditabgaben!<br>";
		} else {
			foreach($aCa as $abgabe) {
				$sOutput .= $abgabe['user_abgabe']['rzname'] . " unterstützt " . $abgabe['user_empfang']['rzname'] . " heute mit einem Bonuscredit!<br>";
			}
		}
		
		$sOutput .= "<br>";
		
		$sOutput .= "[b][size=18]Einwechslungen[/size][/b]<br><br>";
		
		
		foreach($aWechsel['ein'] as $a) {
			$sOutput .= $a['count'] . " x " . $a['fahrer_infos']['fahrer_name'] . " " . $a['fahrer_infos']['fahrer_vorname'] . " (" . $a['fahrer_infos']['team_short'] . ")<br>";	
		}
			
		$sOutput .= "<br>[b][size=18]Auswechslungen[/size][/b]<br><br>";
		
		foreach($aWechsel['aus'] as $a) {
			if ($a['fahrer_id'] > 0) {
				$sOutput .= $a['count'] . " x " . $a['fahrer_infos']['fahrer_name'] . " " . $a['fahrer_infos']['fahrer_vorname'] . " (" . $a['fahrer_infos']['team_short'] . ")<br>";	
			}
			
		}  
		return $sOutput;
	}	
	
}

if ( ! function_exists('forumShortlist'))
{
	function forumShortlist($aShortlist, $aFahrer, $aUser) {
		$sOutput = "[b][size=18]Shortlist " . $aShortlist['name'] . "[/size][/b]<br>";
		$sOutput .= '[b][size=14]Von ' . $aUser['name'] . '[/size][/b]<br><br>';
		$sOutput .= '[table fontsize=9][mcol]Startnummer[mcol]Fahrer[mcol]Team[mcol]Nation[mcol]Credits<br>';
		foreach($aFahrer as $k=>$v) {
			$sOutput .= '[mrow]' . $v['fahrer_startnummer'] . '[mcol]' . $v['fahrer_vorname'] . ' ' . $v['fahrer_name'] . '[mcol]' . $v['team_short'] . '[mcol]' . $v['fahrer_nation'] . '[mcol]' . $v['fahrer_rundfahrt_credits'];
		}
		$sOutput .= '[/table]';
		return $sOutput;
	}
}

if ( ! function_exists('_cmpWechsel'))
{
	function _cmpWechsel ($a, $b) 
	{    
	   if ( $a['count'] == $b['count'] )
	   {     
	        if ($a['count'] == $b['count']) 
	            return 0;
	        else
	           return ( $a['count'] < $b['count'] ) ? +1 : -1;
	    }
	    else
	        return ( $a['count'] < $b['count'] ) ? +1 : -1;
	} 
} 

if ( ! function_exists('_cmpGw'))
{
	function _cmpGw ($a, $b) 
	{    
	   if ( $a['gw'] == $b['gw'] )
	   {     
	        if ($a['gw'] == $b['gw']) 
	            return 0;
	        else
	           return ( $a['gw'] > $b['gw'] ) ? +1 : -1;
	    }
	    else
	        return ( $a['gw'] > $b['gw'] ) ? +1 : -1;
	}
}