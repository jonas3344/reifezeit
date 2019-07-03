<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
if ( ! function_exists('createResultKaderpost')) 
{
	function createResultKaderpost($aResultData, $aExFuehrung) {
		$sOutputDay = '';
		$sOutputGesamt = '';
		
		$sOutputDay .= "[b][size=180]" . $aResultData['etappe']['etappen_nr'] . ". Etappe[/size][/b][br][br]";
		$sOutputDay .= "[b]T A G E S E I N Z E L W E R T U N G[/b]";
		$sOutputDay .= "[table fontsize=9][mrow color=silver][mcol]Platz[/mcol][mcol]Tageseinzelwertung[/mcol][mcol]Sattlerei-Name[/mcol][mcol]Team[/mcol][mcol]Status[/mcol][mcol]Zeit[/mcol][mcol]BCs[/mcol][/mrow]";
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
			$sOutputDay .= "[mrow color=" . $color_row . "][mcol] " . $rang . " ";
			$sOutputDay .= "[/mcol][mcol] " . $user['rzname'] . " [/mcol][mcol] " . $user['name'];
			if ($user['rzteam_short'] != 'none') {
				$sOutputDay .= "[/mcol][mcol color=" . $user['color_code_zelle'] . "][color=" . $user['color_code_schrift'] . "]" . $user['rzteam_short'] . "[/color]";
			} else {
				$sOutputDay .= "[/mcol][mcol]";
			}
			
			$sOutputDay .= "[/mcol][mcol color=" . $user['color_code_rolle'] . "]" . $user['rolle_bezeichnung'];
			$sOutputDay .= "[/mcol][mcol]" . _convertSeconds($v['zeit']) . "[/mcol][mcol]" . $v['bc'] . '[/mcol][/mrow]';
			
			$i++;
		}
		
		$sOutputDay .= "[/table][br][br][br]";
		$sOutputDay .= "[b]T A G E S T E A M W E R T U N G[/b]";
		$sOutputDay .= "[table fontsize=9][mrow color=silver][mcol]Platz[/mcol][mcol]Tagesteamwertung[/mcol][mcol]Zeit[/mcol][/mrow]";
		
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
			$sOutputDay .= "[mrow color=" . $color_row . "][mcol] " . $rang . " ";
			$sOutputDay .= "[/mcol][mcol color=" . $team['color_code_zelle'] . "][color=" . $team['color_code_schrift'] . "]" . $team['rzteam_name'] . "[/color]";
			$sOutputDay .= "[/mcol][mcol]" . _convertSeconds($v['zeit']) . '[/mcol][/mrow]';
			$i++;
		}
		
		$sOutputDay .= "[/table]";
		
		$sOutputGesamt .= "[b]G E S A M T E I N Z E L W E R T U N G[/b]";
		$sOutputGesamt .= "[table fontsize=9][mrow color=#eeee33][mcol]Platz[/mcol][mcol]Gesamteinzelwertung[/mcol][mcol]Sattlerei-Name[/mcol][mcol]Team[/mcol][mcol]Status[/mcol][mcol]Zeit[/mcol][/mrow]";
		
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
			$sOutputGesamt .= "[mrow color=" . $color_row . "] [mcol]" . $rang . " ";
			$sOutputGesamt .= "[/mcol][mcol] " . $user['rzname'] . " [/mcol][mcol] " . $user['name'];
			if ($user['rzteam_short'] != 'none') {
				$sOutputGesamt .= "[/mcol][mcol color=" . $user['color_code_zelle'] . "][color=" . $user['color_code_schrift'] . "]" . $user['rzteam_short'] . "[/color]";
			} else {
				$sOutputGesamt .= "[/mcol][mcol]";
			}
			$sOutputGesamt .= "[/mcol][mcol color=" . $user['color_code_rolle'] . "]" . $user['rolle_bezeichnung'];
			$sOutputGesamt .= "[/mcol][mcol]" . _convertSeconds($v['zeit']) . '[/mcol][/mrow]';
			$i++;
		}
		
		$sOutputGesamt .= "[/table][br][br]";
		
		$sOutputGesamt .= "[b]G E S A M T T E A M W E R T U N G[/b]";
		$sOutputGesamt .= "[table fontsize=9][mrow color=#8888ff][mcol]Platz[/mcol][mcol]Gesamtteamwertung[/mcol][mcol]Zeit[/mcol][/mrow]";

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
			$sOutputGesamt .= "[mrow color=" . $color_row . "][mcol]" . $rang . " ";
			$sOutputGesamt .= "[/mcol][mcol color=" . $team['color_code_zelle'] . "][color=" . $team['color_code_schrift'] . "]" . $team['rzteam_name'] . "[/color]";
			$sOutputGesamt .= "[/mcol][mcol]" . _convertSeconds($v['zeit']) . '[/mcol][/mrow]';
			$i++;
		
		}
		
		$sOutputGesamt .= "[/table][br][br]";
		
		$sOutputGesamt .= "[b]G E S A M T P U N K T E W E R T U N G[/b]";
		$sOutputGesamt .= "[table fontsize=9][mrow color=#00cc00][mcol]Platz[/mcol][mcol]Gesamtpunktewertung[/mcol][mcol]Sattlerei-Name[/mcol][mcol]Team[/mcol][mcol]Status[/mcol][mcol]Punkte[/mcol][/mrow]";
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
				$sOutputGesamt .= "[mrow color=" . $color_row . "][mcol]" . $rang . " ";
				$sOutputGesamt .= "[/mcol][mcol] " . $user['rzname'] . " [mcol] " . $user['name'];
				if ($user['rzteam_short'] != 'none') {
					$sOutputGesamt .= "[/mcol][mcol color=" . $user['color_code_zelle'] . "][color=" . $user['color_code_schrift'] . "]" . $user['rzteam_short'] . "[/color]";
				} else {
					$sOutputGesamt .= "[/mcol][mcol]";
				}
				$sOutputGesamt .= "[/mcol][mcol color=" . $user['color_code_rolle'] . "]" . $user['rolle_bezeichnung'];
				$sOutputGesamt .= "[/mcol][mcol]" . $v['punkte'] . '[/mcol][/mrow]';
				$i++;
			}
		}
		
		$sOutputGesamt .= "[/table][br][br]";
		
		if (array_values($aResultData['overall_berg'])[0]['berg'] > 0) {
			$sOutputGesamt .= "[b]G E S A M T B E R G W E R T U N G[/b]";
			$sOutputGesamt .= "[table fontsize=9][mrow color=#ff3333][mcol]Platz[/mcol][mcol]Gesamtbergwertung[/mcol][mcol]Sattlerei-Name[/mcol][mcol]Team[/mcol][mcol]Status[/mcol][mcol]Punkte[/mcol][/mrow]";
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
					$sOutputGesamt .= "[mrow color=" . $color_row . "][mcol]" . $rang . " ";
					$sOutputGesamt .= "[/mcol][mcol] " . $user['rzname'] . " [mcol] " . $user['name'];
					if ($user['rzteam_short'] != 'none') {
						$sOutputGesamt .= "[/mcol][mcol color=" . $user['color_code_zelle'] . "][color=" . $user['color_code_schrift'] . "]" . $user['rzteam_short'] . "[/color]";
					} else {
						$sOutputGesamt .= "[/mcol][mcol]";
					}
					$sOutputGesamt .= "[/mcol][mcol color=" . $user['color_code_rolle'] . "]" . $user['rolle_bezeichnung'];
					$sOutputGesamt .= "[/mcol][mcol]" . $v['berg'] . '[/mcol][/mrow]';
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
		$sOutput = "[table fontsize=9][mrow color=#1C1C1C][mcol][color=white]Etappe[/color][/mcol][mcol color=#eedabc]Etappensieg[/mcol][mcol color=#ffffcc]Gesamtwertung[/mcol][mcol color=#eedabc]Team Etappe[/mcol][mcol color=#5384dc]Team Gesamt[/mcol][mcol color=#ccffcc]Punkte-Trikot[/mcol][mcol color=#ffcccc]Berg-Trikot[/mcol][/mrow]";
		
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
			$sOutput .= "[mrow color=" . $sStageColor . "][mcol]" . $v['aStage']['etappen_nr'];
			$sOutput .= "[/mcol][mcol color=#fffcde]";
			if ($v['aStage']['etappen_klassifizierung'] != 6) {
				$bFirst = true;
				foreach($v['aFirst'] as $kf=>$vf) {
					if ($bFirst == false) {
						$sOutput .= ' / ';
					}
					$sOutput .= $aResultData['aTeilnehmer'][$vf['user_id']]['rzname'] . " (" . $aResultData['aTeilnehmer'][$vf['user_id']]['rzteam_short'] . ")";
					$bFirst = false;
				}

			} else {
				$sOutput .= $aResultData['aTeilnehmer'][$v['aFirst'][0]['user_id']]['rzteam_short'];
			}
						
			$sOutput .= "[/mcol][mcol color=#eeee33]" . $aResultData['aTeilnehmer'][$v['aLeader']]['rzname'] . " (" . $aResultData['aTeilnehmer'][$v['aLeader']]['rzteam_short'] . ")";
			$sOutput .= "[/mcol][mcol color=#fffcde]" . $aResultData['aTeams'][$v['aTeamStage']]['rzteam_name'];
			$sOutput .= "[/mcol][mcol color=#1177FF]" . $aResultData['aTeams'][$v['aTeamOverall']]['rzteam_name'];
			if ($v['aPoints'] == '-') {
				$sOutput .= "[/mcol][mcol color=#00cc00]" . '-';
			} else {
				$sOutput .= "[/mcol][mcol color=#00cc00]" . $aResultData['aTeilnehmer'][$v['aPoints']]['rzname'] . " (" . $aResultData['aTeilnehmer'][$v['aPoints']]['rzteam_short'] . ")";
			}
			if ($v['aBerg'] == '-') {
				$sOutput .= "[/mcol][mcol color=#ff3333]" . '-';
			} else {
				$sOutput .= "[/mcol][mcol color=#ff3333]" . $aResultData['aTeilnehmer'][$v['aBerg']]['rzname'] . " (" . $aResultData['aTeilnehmer'][$v['aBerg']]['rzteam_short'] . ")";
			}
			$sOutput .= "[/mcol][/mrow]&#13;&#10;&#13;&#10;";
		}
		$sOutput .= "[/table]";
		return $sOutput;
	}
}

if ( ! function_exists('createForumKaderpost'))
{
	
	function createForumKaderpost($aKader, $aCa, $aWechsel) {
		$sOutput = '';	
		
		$sOutput .= "[b][size=180]KADERÜBERSICHT " . $aKader['etappe']['etappen_nr'] . ".Etappe[/size][/b][br][br]";
		$sOutput .= "[table fontsize=9][mrow][mcol]Name[/mcol][mcol]Team[/mcol][mcol]Zeit[/mcol][mcol]Fahrer 1[/mcol][mcol][/mcol][mcol]Fahrer 2[/mcol][mcol][/mcol][mcol]Fahrer 3[/mcol][mcol][/mcol][mcol]Fahrer 4[/mcol][mcol][/mcol][mcol]Fahrer 5[/mcol][mcol][/mcol][mcol][/mcol][/mrow]";
		
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
			
			$sOutput .= "[mrow color=" . $s_color_player . "][mcol]" . $aTeilnehmer['rzname'];
			if (isset($aTeilnehmer['team'])) {
				$sOutput .= "[/mcol][mcol color=" . $aTeilnehmer['team']['color_code_zelle'] . "][color=" . $aTeilnehmer['team']['color_code_schrift'] . "]" . $aTeilnehmer['team']['rzteam_short'] . "[/color]";
			} else {
				$sOutput .= "[/mcol][mcol]";
			}
			
			$sOutput .= "[/mcol][mcol]" . _convertSeconds($aTeilnehmer['gw']);
			$sum = 0;
			foreach($aTeilnehmer['kader'] as $k) {
				$color = ($k['change'] == 1) ? "#ff9999" : "#ffffff";
				$fahrer_name = substr($k['fahrer_vorname'], 0, 1) . "." . $k['fahrer_name'];
				$sOutput .= "[/mcol][mcol color=" . $color . "]" . $fahrer_name . "[/mcol][mcol color=#ffffff]" . $k['fahrer_rundfahrt_credits'];
				$sum += $k['fahrer_rundfahrt_credits'];
			}
			$sOutput .= "[/mcol][mcol color=#ffffff][b]" . $sum . "[/b][/mcol][/mrow]";
		}
		$sOutput .= "[/table][br][br][br]";
		
		$sOutput .= "[b][size=180]Creditabgaben[/size][/b][br][br]";
		
		
		if (count($aCa) == 0) {
			$sOutput .= "Heute keine Creditabgaben![br]";
		} else {
			foreach($aCa as $abgabe) {
				$sOutput .= $abgabe['user_abgabe']['rzname'] . " unterstützt " . $abgabe['user_empfang']['rzname'] . " heute mit einem Bonuscredit![br]";
			}
		}
		
		$sOutput .= "[br]";
		
		$sOutput .= "[b][size=180]Einwechslungen[/size][/b][br][br]";
		
		
		foreach($aWechsel['ein'] as $a) {
			$sOutput .= $a['count'] . " x " . $a['fahrer_infos']['fahrer_name'] . " " . $a['fahrer_infos']['fahrer_vorname'] . " (" . $a['fahrer_infos']['team_short'] . ")[br]";	
		}
			
		$sOutput .= "[br][b][size=180]Auswechslungen[/size][/b][br][br]";
		
		foreach($aWechsel['aus'] as $a) {
			if ($a['fahrer_id'] > 0) {
				$sOutput .= $a['count'] . " x " . $a['fahrer_infos']['fahrer_name'] . " " . $a['fahrer_infos']['fahrer_vorname'] . " (" . $a['fahrer_infos']['team_short'] . ")[br]";	
			}
			
		}  
		return $sOutput;
	}	
	
}

if ( ! function_exists('forumShortlist'))
{
	function forumShortlist($aShortlist, $aFahrer, $aUser) {
		$sOutput = "[b][size=180]Shortlist " . $aShortlist['name'] . "[/size][/b][br]";
		$sOutput .= '[b][size=140]Von ' . $aUser['name'] . '[/size][/b][br][br]';
		$sOutput .= '[table fontsize=9][mrow][mcol]Startnummer[/mcol][mcol]Fahrer[/mcol][mcol]Team[/mcol][mcol]Nation[/mcol][mcol]Credits[/mcol][/mrow]';
		foreach($aFahrer as $k=>$v) {
			$sOutput .= '[mrow][mcol]' . $v['fahrer_startnummer'] . '[/mcol][mcol]' . $v['fahrer_vorname'] . ' ' . $v['fahrer_name'] . '[/mcol][mcol]' . $v['team_short'] . '[/mcol][mcol]' . $v['fahrer_nation'] . '[/mcol][mcol]' . $v['fahrer_rundfahrt_credits'] . '[/mcol][/mrow]';
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