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
	protected $iParser;
	protected $aEtappe;
	protected $aAusreisser;
	
	protected $aResult;
	protected $aResultPoints;
	protected $aResultMoutain;
	
	
	
	function __construct($aParams) {
		$this->CI =& get_instance();

		$this->iParser = $aParams['iParser'];
		$this->CI->load->model('Parser_model', 'model');
		
		$this->aEtappe = $this->CI->model->getOneRow('etappen', 'etappen_id=' . $aParams['iEtappe']);
	}
	
	public function parseResult($sResult, $iType, $aAusreisser) {
		$this->aAusreisser = $aAusreisser;
		if ($this->iParser == 1) {
			if ($iType == 1) {
				$this->_parseGiroTime($sResult);
				$this->_calculateTime();
			} else if ($iType == 2) {
				$this->_parseGiroPoints($sResult);
			} else if ($iType == 3) {
				$this->_parseGiroMountains($sResult);
			}
		} else if ($this->iParser == 2) {
			$this->_parseASO($sResult, $iType);
		}
		$this->_saveToDb($iType);
	}
	
	private function _calculateTime() {
		$sOutput = "<table>";
		if (($this->aEtappe['etappen_klassifizierung'] == 3) || ($this->aEtappe['etappen_klassifizierung'] == 5) || ($this->aEtappe['etappen_klassifizierung'] == 6)) {
			$bZeitfahren = true;
		} else {
			$bZeitfahren = false;
		}
		$bBergetappe = ($this->aEtappe['etappen_klassifizierung'] == 4) ? true : false;
		
		if ($this->aAusreisser['iAusreisser'] == 2) {
			// Keine Ausreisser
			foreach($this->aResult as $key => $r) {
				if ($r['rang'] == 1) {
					$this->aResult[$key]['rueckstandS'] = 0;
					$this->aResult[$key]['rueckstandOhneBS'] = 0;
				} else {			
					$i_minutes_temp = substr($r['rueckstand'], 0, strpos($r['rueckstand'], ':'));
					$i_seconds_temp = substr($r['rueckstand'], strpos($r['rueckstand'], ':')+1);
					
					if ($bZeitfahren == false) {
						if ($r['rang'] == 1) {
							$zeitS = ($i_minutes_temp*60) + $i_seconds_temp;
						} else if ($r['rang'] == 2) {
							$zeitS = ($i_minutes_temp*60) + $i_seconds_temp + 3;
						} else if ($r['rang'] == 3) {
							$zeitS = ($i_minutes_temp*60) + $i_seconds_temp + 6;
						} else if ($r['rang'] == 4) {
							$zeitS = ($i_minutes_temp*60) + $i_seconds_temp + 8;	
						} else if ($r['rang'] == 5) {
							$zeitS = ($i_minutes_temp*60) + $i_seconds_temp + 10;
						} else {
							$zeitS = ($i_minutes_temp*60) + $i_seconds_temp + 15;
						}
					} else if ($zeitfahren == true) {
						$zeitS = ($i_minutes_temp*60) + $i_seconds_temp;
					}
					$this->aResult[$key]['rueckstandOhneBS'] = ($i_minutes_temp*60) + $i_seconds_temp;
					$this->aResult[$key]['rueckstandS'] = $zeitS;		
					
				}
				$sOutput .= "<tr><td>" . $r['fahrer_startnummer'] . "</td><td>" . $r['nachname'] . "</td><td>" . $this->aResult[$key]['rueckstandS'] . "</td></tr>";
			}
			
		} else if ($this->aAusreisser['iAusreisser'] == 1) {
			// Ausreisser
			// Rückstand Hauptfeld herausfinden
			$iRueckstandHauptfeld = 0;
			
			foreach($resultat as $key => $r){
				if ($r['rang'] == $this->aAusreisser['iFirstHauptfeld']) {
					$i_minutes_temp = substr($r['rueckstand'], 0, strpos($r['rueckstand'], ':'));
					$i_seconds_temp = substr($r['rueckstand'], strpos($r['rueckstand'], ':')+1);
					
					$iRueckstandHauptfeld = ($i_minutes_temp*60) + $i_seconds_temp;
					break;
				}
				
			}
			
			$zahl1 = ($bBergetappe==true) ? 180 : 60;
			$zahl2 = ($bBergetappe==true) ? 90 : 30;
			
			foreach($this->aResult as $key => $r){
				$i_minutes_temp = substr($r['rueckstand'], 0, strpos($r['rueckstand'], ':'));
				$i_seconds_temp = substr($r['rueckstand'], strpos($r['rueckstand'], ':')+1);
				$i_ruckstand_temp = ($i_minutes_temp*60) + $i_seconds_temp;
				
				if ($r['rang'] == 1) {
					$zeitS = 0;
					$this->aResult[$key]['rueckstandOhneBS'] = 0;
				} else if ($r['rang'] < $i_rang_hauptfeld) {
					// Vor Hauptfeld
					if ($i_ruckstand_temp < $zahl2) {
						$zeitS = $i_ruckstand_temp;
					} else if (($iRueckstandHauptfeld - $i_ruckstand_temp) < $zahl2) {
						$zeitS = $zahl1 - ($iRueckstandHauptfeld - $i_ruckstand_temp);
					} else {
						$zeitS = $zahl2;
					}
					
				} else if ($r['rang'] >= $i_rang_hauptfeld) {
					if ($iRueckstandHauptfeld > $zahl1) {
						$zeitS = $zahl1 + ($iRueckstandHauptfeld - $i_ruckstand_hauptfeld);
					} else {
						$zeitS = $i_ruckstand_temp;
					}
					
				}
				
				if ($r['rang'] == 1) {
					$zeitS = $zeitS;
				} else if ($r['rang'] == 2) {
					$zeitS += 3;
				} else if ($r['rang'] == 3) {
					$zeitS += 6;
				} else if ($r['rang'] == 4) {
					$zeitS += 8;	
				} else if ($r['rang'] == 5) {
					$zeitS += 10;
				} else {
					$zeitS += 15;
				}
				
				$this->aResult[$key]['rueckstandS'] = $zeitS;
				$this->aResult[$key]['rueckstandOhneBS'] = ($i_minutes_temp*60) + $i_seconds_temp;
				
				$sOutput .= "<tr><td>" . $r['startnummer'] . "</td><td>" . $resultat[$key]['rueckstandS'] . "</td></tr>";
			}
		}
		$sOutput .= '</table>';
		echo $sOutput;
	}
	
	private function _saveToDb($iType) {
		if ($iType == 1) {
			foreach($this->aResult as $k=$v) {
				$data['rang'] = $r['rang'];
				$data['fahrer_id'] = $a['fahrer_id'];
				$data['etappen_id'] = $this->aEtappe['etappen_id'];
				$data['rueckstand'] = $r['rueckstandS'];
				$data['rueckstandOhneBS'] = $r['rueckstandOhneBS'];
				$this->CI->model->saveRecord('resultate', $aData, -1);
			}
		} else if ($iType == 2) {
			foreach($this->aResultPoints as $k=$v) {
				$data['fahrer_id'] = $v['fahrer_id'];
				$data['etappen_id'] = $this->aEtappe['etappen_id'];
				$data['punkte'] = $v['punkte'];
				$this->CI->model->saveRecord('resultate_punkte', $aData, -1);
			}
		} else if ($iType == 3) {
			foreach($this->aResultountain as $k=$v) {
				$data['fahrer_id'] = $v['fahrer_id'];
				$data['etappen_id'] = $this->aEtappe['etappen_id'];
				$data['bergpunkte'] = $v['bergpunkte'];
				$this->CI->model->saveRecord('resultate_punkte', $aData, -1);
			}

		}
	}	
	
	private function _parseASO($sResult, $iRangType) {
		
	}
	
	private function _parseGiroTime($sResult) {
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
					$aFinal[$iRang]['nachname'] = substr($aFinal[$iRang]['nachname'], 0, 1) . strtolower(substr($aFinal[$iRang]['nachname'], 1));
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
					$aFinal[$iRang]['nachname'] = substr($aFinal[$iRang]['nachname'], 0, 1) . strtolower(substr($aFinal[$iRang]['nachname'], 1));
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
					$aFinal[$iRang]['nachname'] = substr($aFinal[$iRang]['nachname'], 0, 1) . strtolower(substr($aFinal[$iRang]['nachname'], 1));
					$search = false;
					}
				}
			}
			
				
			if ($search == true) {
				$sZeile = trim($sZeile);
				$temp = substr($sZeile, 0, strpos($sZeile, ' '));
				$sZeile = substr($sZeile, strpos($sZeile, ' '));
				$aFinal[$iRang]['nachname'] = $temp;
				$aFinal[$iRang]['nachname'] = substr($aFinal[$iRang]['nachname'], 0, 1) . strtolower(substr($aFinal[$iRang]['nachname'], 1));
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

			/* Team Start */
			$sZeile = trim($sZeile);

			foreach($aTeamlist as $k=>$v) {

				if (stripos($sZeile, $v)!== FALSE) {
					$aFinal[$iRang]['team'] = substr($sZeile, 0, (strpos($sZeile, $v) + strlen($v)));
					$sZeile = substr($sZeile, (strpos($sZeile, $v) + strlen($v)));
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
				$aFinal[$iRang]['rueckstand'] = $temp;
			} else {
				$aFinal[$iRang]['rueckstand'] = $sZeile;
			}

			
			$iRang++;
		}
		
		foreach($aFinal as $k=>$v) {
			$aFahrer = $this->CI->model->checkFahrer($v['nachname'], $v['vornamen']);
			$aFinal[$k]['fahrer_id'] = $aFahrer['fahrer_id'];
			$aFinal[$k]['fahrer_startnummer'] = $aFahrer['fahrer_startnummer'];
			
		}
		
		$this->aResult = $aFinal;
	}
	
	private function _parseGiroPoints($sResult) {
		
	}
	
	private function _parseGiroMoutain($sResult) {
		
	}
}
	