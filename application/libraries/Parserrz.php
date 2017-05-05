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
				$this->_parseGiroMountain($sResult);
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
			foreach($this->aResult as $k=>$v) {
				$aData['rang'] = $v['rang'];
				$aData['fahrer_id'] = $v['fahrer_id'];
				$aData['etappen_id'] = $this->aEtappe['etappen_id'];
				$aData['rueckstand'] = $v['rueckstandS'];
				$aData['rueckstandOhneBS'] = $v['rueckstandOhneBS'];
				$this->CI->model->saveRecord('resultate', $aData, -1);
			}
		} else if ($iType == 2) {
			foreach($this->aResultPoints as $k=>$v) {
				$aData['fahrer_id'] = $v['fahrer_id'];
				$aData['etappen_id'] = $this->aEtappe['etappen_id'];
				$aData['punkte'] = $v['punkte'];
				$this->CI->model->saveRecord('resultate_punkte', $aData, -1);
			}
		} else if ($iType == 3) {
			foreach($this->aResultountain as $k=>$v) {
				$aData['fahrer_id'] = $v['fahrer_id'];
				$aData['etappen_id'] = $this->aEtappe['etappen_id'];
				$aData['bergpunkte'] = $v['bergpunkte'];
				$this->CI->model->saveRecord('resultate_berg', $aData, -1);
			}

		}
	}	
	
	private function _parseASO($sResult, $iRangType) {
		
	}
	
	private function _parseGiroTime($sResult) {
		$aFinal = array();
		
		$aResult = explode("\n", $sResult);
		$iRang = 0;
				
	
		foreach ($aResult as $sZeile) {
			// Resultat abkappen
			$aTemp = explode("\t", $sZeile);
			
			$aFinal[$iRang]['rang'] = $aTemp[1];
			$aFinal[$iRang]['nation'] = $aTemp[2];
			$aFinal[$iRang]['namen'] = $aTemp[3];

			
			// Zeit berechnen
			$sZeit = $aTemp[7];
			$iMin = substr($sZeit, 0, strpos($sZeit, chr(226)));
			$sZeit = substr($sZeit, strpos($sZeit, chr(226))+3);
			$iSec = substr($sZeit, 1, 2);
			$sZeit = $iMin . ":" . $iSec . "<br>";
			
			$aFinal[$iRang]['rueckstand'] = $sZeit;

			$iRang++;

		}
			
		$aFinal = $this->CI->model->checkFahrer($aFinal);
		
		
		$this->aResult = $aFinal;
	}
	
	private function _parseGiroPoints($sResult) {
		$aResult = explode("\n", $sResult);
		
		$aFinal = array();
		
		$iRang = 0;
		foreach($aResult as $sZeile) {
			$aTemp = explode("\t", $sZeile);
			
			$aFinal[$iRang]['namen'] = $aTemp[3];
			$aFinal[$iRang]['punkte'] = $aTemp[5];
			$iRang++;
		}
		

		$aFinal = $this->CI->model->checkFahrer($aFinal);
		
		
		$this->aResultPoints = $aFinal;

	}
	
	private function _parseGiroMountain($sResult) {
		$aResult = explode("\n", $sResult);
		
		$aFinal = array();
		
		$iRang = 0;
		foreach($aResult as $sZeile) {
			$aTemp = explode("\t", $sZeile);
			
			$aFinal[$iRang]['namen'] = $aTemp[3];
			$aFinal[$iRang]['bergpunkte'] = $aTemp[5];
			$iRang++;
		}
		
		$aBergpunkte = array();
		foreach($aFinal as $k=>$v) {
			$bFound = false;
			foreach($aBergpunkte as $kb=>$vb) {
				if ($v['namen'] == $vb['namen'] && $bFound == false) {
					$aBergpunkte[$kb]['bergpunkte'] = $aBergpunkte[$kb]['bergpunkte'] + $v['bergpunkte'];
					$bFound = true;
				}
			}
			if ($bFound == false) {
				$aBergpunkte[] = $v;
			}
		}
		
		$aFinal = $this->CI->model->checkFahrer($aBergpunkte);
		

		$this->aResultountain = $aFinal;
		
	}
}
	