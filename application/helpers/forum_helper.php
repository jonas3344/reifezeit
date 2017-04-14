<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('createForumKaderpost'))
{
	
	function createForumKaderpost($aKader, $aCa, $aWechsel) {
		$sOutput = '';	
		
		$sOutput .= "[b][size=18]KADERÜBERSICHT " . $aKader['etappe']['etappen_nr'] . ".Etappe[/size][/b]<br><br>";
		$sOutput .= "[table fontsize=9][mcol]Name[mcol]Team[mcol]Zeit[mcol]Fahrer 1[mcol][mcol]Fahrer 2[mcol][mcol]Fahrer 3[mcol][mcol]Fahrer 4[mcol][mcol]Fahrer 5[mcol][mcol]<br>";
		
/*
		echo "<pre>";
		print_r($aKader);
		echo "</pre>";
		
*/
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
			
			$sOutput .= "[mrow color=" . $s_color_player . "]" . $aTeilnehmer['rzname'] . "[mcol color=" . $aTeilnehmer['team']['color_code_zelle'] . "][color=" . $aTeilnehmer['team']['color_code_schrift'] . "]" . $aTeilnehmer['team']['rzteam_short'] . "[/color]";
			$sOutput .= "[mcol]" . _convertSeconds($aTeilnehmer['gw']);
			$sum = 0;
			foreach($aTeilnehmer['kader'] as $k) {
				$color = ($k['change'] == 1) ? "#ff9999" : "#ffffff";
				$fahrer_name = ($k['fahrer_name'] == "Martin") ? substr($$k['fahrer_vorname'], 0, 1) . "." . $k['fahrer_name'] : $k['fahrer_name'];
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