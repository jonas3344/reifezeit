<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('_convertSeconds')) {
	
	function _convertSeconds($iTime) {
		$sTime = "";
		(int)$iHours = (int)($iTime / 3600);
		if ($iHours>0) {
			$sTime .= $iHours . ":";
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