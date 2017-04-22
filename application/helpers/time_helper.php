<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('_convertSeconds')) {
	
	function _convertSeconds($iTime) {
		$sTime = "";
		(int)$iHours = (int)($iTime / 3600);
		if ($iHours>0) {
			if ($iHours < 10) {
				$sTime .= '0' . $iHours . ":";
			} else {
				$sTime .= $iHours . ":";
			}
		} else {
			$sTime .= "00:";
		}
		$iMinutes = (int)($iTime/60) - ($iHours*60);
		if ($iMinutes>0) {
			if ($iMinutes < 10) {
				$sTime .= "0" . $iMinutes . ":";
			} else {
				$sTime .= $iMinutes . ":";
			}
		} else if ($iMinutes == 0) {
			$sTime .= "00:";
		} 
		
		$iSeconds = $iTime - ($iHours*3600) - ($iMinutes *60);
		if ($iSeconds == 0) {
			$sTime .= "00";
		} else if ($iSeconds < 10) {
			$sTime .= "0" . $iSeconds;
		} else {
			$sTime .= $iSeconds;
		}	
		return $sTime;
	}
	
}

if ( ! function_exists('_create_timestamp')) {

	function _create_timestamp($s_datum, $s_zeit) {
		$i_tag = substr($s_datum, 0, 2);
		$i_monat = substr($s_datum, 3, 2);
		$i_jahr = substr($s_datum, 6, 4);
		
		$i_stunde = substr($s_zeit, 0, 2);
		$i_minute = substr($s_zeit, 3, 2);
		
		return mktime($i_stunde, $i_minute, 0, $i_monat, $i_tag, $i_jahr);
	}
}