<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016  Jonas Bay - jonas.bay@bluewin.ch 
 * ----------------------------------------------------------
 *
 * @author		  Jonas Bay
 */
 
class Parserrz {
	
	protected $CI;
	// 1 = Giro, 2 = Tour/Vuelta
	protected $iRangParser;
	
	function __construct($iRangParser) {
		$this->CI =& get_instance();
		$this->iParser = $iRangParser;
	}
	
	public function parseResult($sResult, $iType) {
		if ($this->iParser == 1) {
			$this->_parseGiro($sResult, $iType);
		} else if ($this->iParser == 2) {
			$this->_parseASO($sResult, $iType);
		}
	}
	
	private function _parseASO($sResult, $iRangType) {
		
	}
	
	private function _parseGiro($sResult, $iType) {
		$aFinal = array();
		
		$aResult = explode("\n", $sResult);
		$iRang = 0;
		
		$langnamen = array("DE BIE", "PLAZA MOLINA", "URAN URAN", "ROJAS GIL", "VAN ZYL", "LE GAC", "HERRADA LOPEZ", "FRAILE MATARRANZ", "DE MARCHI", "DE BACKER", "ANTON HERNANDEZ", "VAN EMDEN", "KUDUS GHEBREMEDHIN", "LOPEZ GARCIA");
		$langnamen2 = array("DE LA CRUZ");
		$langnamen3 = array();
		
		$langnamen_vor = array("Eduard Michael", "Axel Maximiliano", "Pier Paolo", "Tom Jelte", "Johan Esteban", "Carlos A.", "Sergio M.", "Javier Alexis", "Francesco Manuel", "Murilo Antonio", "Luis Leon", "Lars Ytting", "Bert Jan", "Jay Robert", "Jean Christophe", "Jose Joaquin", "Joseph Lloyd", "Vegard Stake");
		
		$langnamen_vor2 = array("Andre Fernando S");
		
		$aTeamlist = array('LOTTO SOUDAL', 'NIPPO - VINI FANTINI', 'TEAM GIANT - ALPECIN', 'ETIXX - QUICK-STEP', 'LAMPRE - MERIDA', 'TEAM KATUSHA', 'GAZPROM - RUSVELO', 'BMC RACING TEAM', 'TINKOFF', 'CANNONDALE PRO CYCLING TEAM', 'WILIER TRIESTINA - SOUTHEAST', 'TREK - SEGAFREDO', 'FDJ', 'TEAM DIMENSION DATA', 'MOVISTAR TEAM', 'TEAM SKY', 'ASTANA PRO TEAM', 'TEAM LOTTO NL - JUMBO', 'BARDIANI CSF', 'AG2R LA MONDIALE', 'IAM CYCLING', 'ORICA GREENEDGE');
		
		foreach ($aResult as $sZeile) {
			// Resultat abkappen
			$sZeile = trim($sZeile);
			if ($iRang<9) {
				$aFinal[$iRang]['rang'] = substr($sZeile, 0, 1);
				$sZeile = substr($sZeile, 1);
			} else if (($iRang<99) && ($iRang>8)) {
				$aFinal[$iRang]['rang'] = substr($sZeile, 0, 2);
				$sZeile = substr($sZeile, 2);
			} else if ($iRang>98) {
				$aFinal[$iRang]['rang'] = substr($sZeile, 0, 3);
				$sZeile = substr($sZeile, 3);
			}
			
			
			
			/*Nation Start*/
			$sZeile = trim($sZeile);
			$aFinal[$iRang]['nation'] = substr($sZeile, 0, 3);
			$sZeile = substr($sZeile, 3);
			
			/* Nachnamen */
			$search = true;
			
			foreach($langnamen as $l) {
				if (strpos($sZeile, $l)) {
					$sZeile = trim($sZeile);
					$temp = substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$sZeile = trim($sZeile);
					$temp = $temp . " " . substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$aFinal[$iRang]['nachname'] = $temp;
					$search = false;
				}
			}
			if ($search == true) {
				foreach($langnamen2 as $l) {
				if (strpos($sZeile, $l)) {
					$sZeile = trim($sZeile);
					$temp = substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$sZeile = trim($sZeile);
					$temp = $temp . " " . substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$sZeile = trim($sZeile);
					$temp = $temp . " " . substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($a, ' '));
					$aFinal[$iRang]['nachname'] = $temp;
					$search = false;
				}
			}
			}
			if ($search == true) {
				foreach($langnamen3 as $l) {
				if (strpos($sZeile, $l)) {
					
					$sZeile = trim($sZeile);
					$temp = substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$sZeile = trim($sZeile);
					$temp = $temp . " " . substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$sZeile = trim($sZeile);
					$temp = $temp . " " . substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$sZeile = trim($sZeile);
					$temp = $temp . " " . substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$aFinal[$iRang]['nachname'] = $temp;
					$search = false;
					}
				}
			}
			
				
			if ($search == true) {
				$sZeile = trim($sZeile);
				$temp = substr($sZeile, 0, strpos($sZeile, ' '));
				$sZeile = substr($sZeile, strpos($sZeile, ' '));
				$aFinal[$iRang]['nachname'] = $temp;
			}
			
			/* Nachnamen End*/
			/* Vornamen Begin*/
			$search = true;
				
			foreach ($langnamen_vor as $l) {
				if (strpos($sZeile, $l)) {
					$sZeile = trim($sZeile);
					$temp = substr($sZeile, 0, strpos($sZeile, ' '));
					
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$sZeile = trim($sZeile);
					$temp = $temp . " " . substr($sZeile, 0, strpos($sZeile, chr(9)));
					$sZeile = substr($sZeile, strpos($sZeile, chr(9)));
					$aFinal[$iRang]['vornamen'] = $temp;
					$search = false;
					
				}
			}
			
			if ($search == true) {
				foreach ($langnamen_vor2 as $l) {
				if (strpos($sZeile, $l)) {
					$sZeile = trim($sZeile);
					$temp = substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$sZeile = trim($sZeile);
					$temp = $temp . " " . substr($sZeile, 0, strpos($sZeile, ' '));
					$sZeile = substr($sZeile, strpos($sZeile, ' '));
					$sZeile = trim($sZeile);
					$temp = $temp . " " . substr($sZeile, 0, strpos($sZeile, chr(9)));
					$sZeile = substr($sZeile, strpos($sZeile, chr(9)));
					$aFinal[$iRang]['vornamen'] = $temp;
					$search = false;
					
					}
			}
			}
			
			if ($search == true) {
				$sZeile = trim($sZeile);
				$temp = substr($sZeile, 0, strpos($sZeile, chr(9)));
				//echo $sZeile . "-" . strpos($sZeile, '\t') . "<br>";
				$sZeile = substr($sZeile, strpos($sZeile, chr(9)));
				$aFinal[$iRang]['vornamen'] = $temp;
			}
			
			/* Vornamen End*/

			//echo $sZeile . '<br>';
			
			/* Team Start */
			$sZeile = trim($sZeile);

			foreach($aTeamlist as $k=>$v) {

				if (stripos($sZeile, $v)!== FALSE) {
					$aFinal[$iRang]['team'] = substr($sZeile, 0, (strpos($sZeile, $v) + strlen($v)));
					$sZeile = substr($sZeile, (strpos($sZeile, $v) + strlen($v)));
					/* echo "gugus" . $v; */
					break;
				}
			}
				
			/*Gesamtzeit start*/
			$sZeile = trim($sZeile);
			$temp = substr($sZeile, 0, strpos($sZeile, 'h'));		
			$sZeile = substr($sZeile, (strpos($sZeile, 'h')+1));
			trim($sZeile);
			
			$temp .= ':' . substr($sZeile, 1, strpos($sZeile, '’'));
			$temp = substr($temp, 0, strlen($temp) -1) . ':';
			$sZeile = substr($sZeile, (strpos($sZeile, '’')+1));
			$sZeile = substr($sZeile, 1);
			$sZeile = substr($sZeile, 1);
			$temp .= substr($sZeile, 1, strpos($sZeile, '”'));
			$temp = substr($temp, 0, strlen($temp) -1);
			$sZeile = substr($sZeile, strpos($sZeile, '”')+1);
			$sZeile = substr($sZeile, 1);
			$sZeile = substr($sZeile, 1);
			
			$aFinal[$iRang]['zeit'] = $temp;
			
			/*Rückstand*/
			$sZeile = trim($sZeile);
			if (strpos($sZeile, ' ') > 0) {
				$sZeile = trim($sZeile);
				$temp = substr($sZeile, 0, strpos($sZeile, '’'));
				$sZeile = substr($sZeile, strpos($sZeile, '’')+1);
				$sZeile = substr($sZeile, 1);
				$sZeile = substr($sZeile, 1);
				$temp .= ':' . substr($sZeile, 1, 2);
				$aFinal[$iRang]['ruckstand'] = $temp;
			} else {
				$aFinal[$iRang]['ruckstand'] = $sZeile;
			}

			
			$iRang++;
		}
		
		echo "<pre>";
		print_r($aFinal);
		echo "</pre>";
		
	}
}
	