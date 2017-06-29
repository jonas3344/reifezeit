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
			if ($iType == 1) {
				$this->_parseAsoTime($sResult, $iType);
				$this->_calculateTime();
			} else if ($iType == 2) {
				$this->_parseAsoPoints($sResult);
			} else if ($iType == 3) {
				$this->_parseAsoMountain($sResult);
			}
		}
		
		echo "<pre>";
		print_r($this->aResult);
		echo "</pre>";
		//$this->_saveToDb($iType);
	}
	
	private function _calculateTime() {
		$sOutput = "<br><br><br><br><br><table>";
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
					} else if ($bZeitfahren == true) {
						$zeitS = ($i_minutes_temp*60) + $i_seconds_temp;
					}
					$this->aResult[$key]['rueckstandOhneBS'] = ($i_minutes_temp*60) + $i_seconds_temp;
					$this->aResult[$key]['rueckstandS'] = $zeitS;		
					
				}
				$sOutput .= "<tr><td>" . $r['fahrer_startnummer'] . "</td><td>" . $r['namen'] . "</td><td>" . $this->aResult[$key]['rueckstandS'] . "</td></tr>";
			}
			
		} else if ($this->aAusreisser['iAusreisser'] == 1) {
			// Ausreisser
			// Rückstand Hauptfeld herausfinden
			$iRueckstandHauptfeld = 0;
			
			foreach($this->aResult as $key => $r){
				if ($r['rang'] == $this->aAusreisser['iFirstHauptfeld']) {
					$i_minutes_temp = substr($r['rueckstand'], 0, strpos($r['rueckstand'], ':'));
					$i_seconds_temp = substr($r['rueckstand'], strpos($r['rueckstand'], ':')+1);
					
					$iRueckstandHauptfeld = ($i_minutes_temp*60) + $i_seconds_temp;
					break;
				}
				
			}
				
			$zahl1 = ($bBergetappe==true) ? 180 : 60;
					
			foreach($this->aResult as $key => $r){
				$i_minutes_temp = substr($r['rueckstand'], 0, strpos($r['rueckstand'], ':'));
				$i_seconds_temp = substr($r['rueckstand'], strpos($r['rueckstand'], ':')+1);
				$i_ruckstand_temp = ($i_minutes_temp*60) + $i_seconds_temp;
				
				if ($r['rang'] == 1) {
					$zeitS = 0;
					$this->aResult[$key]['rueckstandOhneBS'] = 0;
				} else if ($r['rang'] < $this->aAusreisser['iFirstHauptfeld']) {
					// Vor Hauptfeld
				
					$zeitS = round(($i_ruckstand_temp / $iRueckstandHauptfeld) * $zahl1, 0);
					
				} else if ($r['rang'] >= $this->aAusreisser['iFirstHauptfeld']) {
					if ($iRueckstandHauptfeld > $zahl1) {
						$zeitS = $zahl1 + ($i_ruckstand_temp - $iRueckstandHauptfeld);
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
				
				$sOutput .= "<tr><td>" . $r['fahrer_startnummer'] . "</td><td>" . $r['namen'] . "</td><td>" . $zeitS . "</td></tr>";
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
	
	private function _parseAsoTime($sResult, $iRangType) {
		$aFinal = array();
		
		$aResult = explode("\n", $sResult);
		$iRang = 0;
		
		foreach ($aResult as $sZeile) {
			$aTemp = explode("\t", $sZeile);
			
			$aFinal[$iRang]['rang'] = substr($aTemp[0], 0, -1);
			$aFinal[$iRang]['nation'] = substr($aTemp[1], 0, 3);
			$aFinal[$iRang]['namen'] = substr($aTemp[1], 3);
			$aFinal[$iRang]['startnummer'] = $aTemp[2];
			
			$sZeit = $aTemp[5];
			
			
			$iMin = substr($sZeit, 2, 2);
			$iSec = substr($sZeit, 6, 2);
			if (trim($sZeit) == "") {
				$iMin = "00";
				$iSec = "00";
			}
			
			$aFinal[$iRang]['rueckstand'] = $iMin . ":" . $iSec;
			
			$iRang++;
			
		}
		
		$aFinal = $this->CI->model->checkFahrer($aFinal, $this->iParser);

		$this->aResult = $aFinal;
		
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
			if ($this->aEtappe['etappen_klassifizierung'] == 3) {
				$sZeit = $aTemp[6];
			} else {
				$sZeit = $aTemp[7];
			}
			
			$iMin = substr($sZeit, 0, strpos($sZeit, chr(226)));
			$sZeit = substr($sZeit, strpos($sZeit, chr(226))+3);
			$iSec = substr($sZeit, 1, 2);
			$sZeit = $iMin . ":" . $iSec . "<br>";
			
			$aFinal[$iRang]['rueckstand'] = $sZeit;

			$iRang++;

		}
					
		$aFinal = $this->CI->model->checkFahrer($aFinal, $this->iParser);
		
		
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
		

		$aFinal = $this->CI->model->checkFahrer($aFinal, $this->iParser);
		
		
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
	