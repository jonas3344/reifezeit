<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016  Jonas Bay - jonas.bay@bluewin.ch 
 * ----------------------------------------------------------
 *
 * @author		  Jonas Bay
 */
 
class Resultaterz {
	
	protected $CI;
	protected $iEtappeId;
	protected $aEtappen;
	protected $aKader;
	// Resultate array[etappe][fahrer]
	protected $aResultate;
	protected $aResultatePunkte;
	protected $aResultateBerg;
	
	protected $aDoping;
	
	protected $aTeilnehmer;
	protected $aTeams;
	
	protected $aRzTageswertungen;
	protected $aRzTeamwertungen;
	protected $aRzPunkte;
	protected $aRzBerg;
	protected $aRzTeam;

	
	function __construct($iEtappe) {
		$this->CI =& get_instance();
		
		$this->iEtappeId = $iEtappe;
		$this->CI->db->where('etappen_id', $iEtappe);
		$aActEtappe = $this->CI->db->get('etappen')->row_array();
		
		$this->CI->db->where('etappen_rundfahrt_id', $this->CI->config->item('iAktuelleRundfahrt'));
		$this->CI->db->where('etappen_nr<=',$aActEtappe['etappen_nr']);
		$this->aEtappen = $this->_orderEtappeByNr($this->CI->db->get('etappen')->result_array());
		
		foreach($this->aEtappen as $aEtappe) {
			$this->CI->db->where('etappen_id', $aEtappe['etappen_id']);
			$this->aKader[$aEtappe['etappen_nr']] = $this->_orderByUserId($this->CI->db->get('kader')->result_array());
		}
		
		foreach($this->aEtappen as $aEtappe) {
			$this->CI->db->where('etappen_id', $aEtappe['etappen_id']);
			$this->aResultate[$aEtappe['etappen_nr']] = $this->_orderResultateByStage($this->CI->db->get('resultate')->result_array());
			
			$this->CI->db->where('etappen_id', $aEtappe['etappen_id']);
			$this->aResultateBerg[$aEtappe['etappen_nr']] = $this->_orderResultateByStage($this->CI->db->get('resultate_berg')->result_array());
			
			$this->CI->db->where('etappen_id', $aEtappe['etappen_id']);
			$this->aResultatePunkte[$aEtappe['etappen_nr']] = $this->_orderResultateByStage($this->CI->db->get('resultate_punkte')->result_array());
		}
		
		$this->CI->db->where('rundfahrt_id', $this->CI->config->item('iAktuelleRundfahrt'));
		$this->aDoping = $this->CI->db->get('dopingfall')->result_array();
		
		
		// Teilnehmer holen

		$this->CI->db->where('t.rundfahrt_id', $this->CI->config->item('iAktuelleRundfahrt'));
		$this->aTeilnehmer = $this->_orderByUserId($this->CI->db->get('teilnahme t')->result_array());
		
		// RZ-Teams holen
		$aTemp = $this->CI->db->get('rz_team')->result_array();
		
		foreach($aTemp as $k=>$aTeam) {
			$this->CI->db->where('rundfahrt_id', $this->CI->config->item('iAktuelleRundfahrt'));
			$this->CI->db->where('rz_team_id', $aTeam['rzteam_id']);
			$aMembers = $this->CI->db->get('rz_user_team')->result_array();
			if (count($aMembers) > 0) {
				$this->aTeams[$aTeam['rzteam_id']] = $aTeam;
				$this->aTeams[$aTeam['rzteam_id']]['members'] = $this->_orderByUserId($aMembers);
			}
			
		}
		
		$this->_calculateTageswertung();
		$this->_calculateGesamtwertung();
		$this->_calculatePunkte();
		$this->_calculateBerg();
		$this->_calculateTeam();
		$this->_calculateTeamGesamt();
		
/*
		echo "<pre>";
		print_r($this->aRzTageswertungen);
		echo "</pre>";
*/
	}
	
		/*
	 * Function getGesamtForKader
	 *
	 * Returns the General Classification
	 *
	 * @param 
	 * @return Array
	 */
	
	public function getGesamtForKader() {
		return $this->aGesamtwertung;
	}
	
	/*
	 * Function getTagesWertung
	 *
	 * Returns the classification of the latest stage.
	 *
	 * @param 
	 * @return Array
	 */
	
	public function getTagesWertung() {
		return $this->aRzTageswertungen[$this->iAktuelleEtappe];
	}
	
	public function getTeam() {
		return $this->aRzTeamwertungen[$this->iAktuelleEtappe];
	}
	
	public function getGesamtWertung() {
		return $this->aGesamtwertung;
	}
	
	public function getGesamtBerg() {
		return $this->aRzBerg;
	}
	
	public function getGesamtPunkte() {
		return $this->aRzPunkte;
	}
	
	public function getTeamGesamt() {
		return $this->aRzTeam;
	}
	
	private function _calculateTageswertung() {
		foreach ($this->aEtappen as $iNrEtappe=>$aEtappe) {
			if ($aEtappe['etappen_klassifizierung'] == 6) {
				$this->_calculateMzf($iNrEtappe);
			} else {
				// 50. der Etappe herausfinden
				foreach($this->aResultate[$iNrEtappe] as $v) {
					if ($v['rang'] == 50) {
						$rueckstand50 = $v['rueckstand'];
						break;
					}
				}
					
				foreach($this->aTeilnehmer as $t) {
					$aFahrerId = array();
					$aFahrerId[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer1'];
					$aFahrerId[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer2'];
					$aFahrerId[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer3'];
					$aFahrerId[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer4'];
					$aFahrerId[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer5'];
					
					$rang_array = array();
					
					// Check Rang, falls DNF=>500
					foreach($aFahrerId as $k=>$v) {
						if (!isset($this->aResultate[$iNrEtappe][$v]['rang'])) {
							$rang_array[$v] = 500;
						} else {
							$rang_array[$v] = $this->aResultate[$iNrEtappe][$v]['rang'];
						}
					}
					
					asort($rang_array);
					$rang_array_komplett[$iNrEtappe][$t['user_id']] = $rang_array;
					
					if (($this->aEtappen[$iNrEtappe]['etappen_klassifizierung'] != 3) && ($this->aEtappen[$iNrEtappe]['etappen_klassifizierung'] != 5)) {
						array_pop($rang_array);
					}
						
					$zeit = 0;
					$bc = 0;
					foreach($rang_array as $k=>$v){
						if ($v == 1) {
							$bc += 3;
						} else if ($v == 2) {
							$bc += 2;
						} else if ($v == 3) {
							$bc +=1;
						}
						if ($v > 50) {
							if ($v!=500) {
								if (($this->aResultate[$iNrEtappe][$k]['rueckstand'] < $rueckstand50)) {
									$zeit+= $this->aResultate[$iNrEtappe][$k]['rueckstand'];
								} else {
									$zeit+= $rueckstand50;
								}
							} else {
								$zeit+= $rueckstand50;
							}
						} else {
							$zeit += $this->aResultate[$iNrEtappe][$k]['rueckstand'];
						}
						
					}
					$temp[$iNrEtappe][$t['user_id']]['bc'] = $bc;
					if ($t['out'] == 1) {
						if ($t['out_etappen_id'] >= $this->aEtappen[$iNrEtappe]['etappen_id']) {
							$this->aRzTageswertungen[$iNrEtappe][$t['user_id']]['zeit'] = $zeit;
						}
					} else {
						$this->aRzTageswertungen[$iNrEtappe][$t['user_id']]['zeit'] = $zeit;
					}
				}
			}
		}
		
		// Sort und Berechnen
		foreach($this->aRzTageswertungen as $k=>$v) {
			asort($this->aRzTageswertungen[$k]);
			
			$siegerzeit = array_values($this->aRzTageswertungen[$k])[0]['zeit'];
			$zeit_letzter = array_values($this->aRzTageswertungen[$k])[count($this->aRzTageswertungen[$k])-1]['zeit'];
			if (count($this->aDoping) > 0) {
				foreach($this->aDoping as $dk=>$d) {
					if ($d['etappen_id'] == $this->aEtappen[$k]['etappen_id']) {
						if (isset($this->aRzTageswertungen[$k][$d['user_id']])) {
							$this->aRzTageswertungen[$k][$d['user_id']]['zeit'] = $zeit_letzter + 120;
							$temp[$k][$dk]['bc'] = 0;
						}
					}
				}
			}
			
			asort($this->aRzTageswertungen[$k]);
			$rang = 1;
			foreach($this->aRzTageswertungen[$k] as $p=>$v) {
				$this->aRzTageswertungen[$k][$p]['zeit_real'] = $v['zeit'];
				$this->aRzTageswertungen[$k][$p]['zeit'] = $v['zeit'] - $siegerzeit;
				$this->aRzTageswertungen[$k][$p]['rang'] = $rang;
				$this->aRzTageswertungen[$k][$p]['user_id'] = $p;
				if (isset($temp[$k][$p]['bc'])) {
					$this->aRzTageswertungen[$k][$p]['bc'] = $temp[$k][$p]['bc'];
				}
				$rang++;
			}
			
		}
		
		// Tiebreaker
		/*
			4.11. Zeitgleiche Spieler bekommen normalerweise den selben Platz zugewiesen, nur beim Tagessieg werden die Zielfotos genauer ausgewertet. Als Unterscheidungsmerkmale gelten hier:
			1. bestplatzierter Fahrer im Team
			2. nächstbessere Platzierung
			Identische Aufstellungen teilen sich den Tagessieg. 
		*/
		$tiebreaker = array();
		foreach($this->aRzTageswertungen as $k=>$v) {
			if ($this->aEtappen[$k]['etappen_klassifizierung'] != 6) {
				foreach($this->aRzTageswertungen[$k] as $p=>$l) {
					if ($this->aRzTageswertungen[$k][$p]['zeit'] == 0) {
						
						$tiebreaker[$k][$p] = $l;					
					}
				}
			}
		}
		
		foreach($tiebreaker as $k=>$v) {
			if (count($v)>1) {
				// In dieser Etappe hats mehr als einen zeitgleichen
				$rang = array();
				foreach($v as $p=>$l) {
					$rang[$p] = $rang_array_komplett[$k][$p];
				}
				
				if (count($v) == 2) {
					// Wir haben zwei Zeitgleiche
					$changed = false;
					for($i=0;$i<5;$i++) {
						if (array_values(array_values($rang)[0])[$i] < array_values(array_values($rang)[1])[$i]) {
							$changed = true;
							break;
						} else if (array_values(array_values($rang)[0])[$i] > array_values(array_values($rang)[1])[$i]) {
							$temp = $this->aRzTageswertungen[$k];
							$keys = array_keys($v);
							$temp = $this->_array_flip_values($temp, $keys[0], $keys[1]);
							$temp[$keys[1]]['rang'] = 1;
							$temp[$keys[0]]['rang'] = 2;
							$this->aRzTageswertungen[$k] = $temp;
							$changed = true;
							break;
						}
					}
					if ($changed == false) {
						// Gleiche Kader
						$temp[$keys[1]]['rang'] = 1;
						$temp[$keys[0]]['rang'] = 1;
					}
				} else {
					// Wir haben mehr als 2 Zeitgleiche
					$length = count($v);
					for($i=0;$i<$length;$i++) {
						for ($l=$i;$l<$length;$l++) {
							for ($p=0;$p<5;$p++) {
								if (array_values(array_values($rang)[$i])[$p] > array_values(array_values($rang)[$l])[$p]) {
									$keys = array_keys($rang);
									$rang = $this->_array_flip_values($rang, $keys[$i], $keys[$l]);
									break;
								} else if (array_values(array_values($rang)[$i])[$p] < array_values(array_values($rang)[$l])[$p]) {
									break;
								}
							}
						}
					}
					
					for($i=0;$i<($length-1);$i++) {
						if (count(array_diff(array_values($rang)[$i], array_values($rang)[$i+1])) == 0) {
							$keys = array_keys($rang);
							$this->aRzTageswertungen[$k][$keys[$i]]['rang'] = 1;	
						} else {
							$break = $i;
							break;
						}
					}
					
					$count = 0;
					foreach($rang as $u=>$v) {
						if ($count <= $break) {
							$this->aRzTageswertungen[$k][$u]['rang'] = 1;
						} else {
							$this->aRzTageswertungen[$k][$u]['rang'] = $count+1;
						}
						$count++;
					}
				}
			
				// Wir sortieren noch nach Rang
				$length = count($this->aRzTageswertungen[$k]);
				
				for ($i=0;$i<$length;$i++) {
					for($p=$i;$p<$length;$p++) {
						if (array_values($this->aRzTageswertungen[$k])[$i]['rang'] > array_values($this->aRzTageswertungen[$k])[$p]['rang']) {
							$keys = array_keys($this->aRzTageswertungen[$k]);
							$temp = $this->aRzTageswertungen[$k];
							$temp = $this->_array_flip_values($temp, $keys[$i], $keys[$p]);
							$this->aRzTageswertungen[$k] = $temp;
							
						}
					}
				}
			}	
		}	
	}
	
	private function _calculateMzf($iEtappe) {
		foreach($this->aTeams as $k=>$v) {
			$teamresult = array();
			foreach($v['members'] as $p=>$l) {
				$fahrer_ids = array();
				$fahrer_ids[] = $this->aKader[$iEtappe][$l['user_id']]['fahrer1'];
				$fahrer_ids[] = $this->aKader[$iEtappe][$l['user_id']]['fahrer2'];
				$fahrer_ids[] = $this->aKader[$iEtappe][$l['user_id']]['fahrer3'];
				$fahrer_ids[] = $this->aKader[$iEtappe][$l['user_id']]['fahrer4'];
				$fahrer_ids[] = $this->aKader[$iEtappe][$l['user_id']]['fahrer5'];
				
				$rang_array = array();
				
				for ($i=0;$i<count($fahrer_ids);$i++) {
					if (!isset($this->aResultate[$iEtappe][$fahrer_ids[$i]]['rang'])) {
						$rang_array[$fahrer_ids[$i]] = 500;
					} else {
						$rang_array[$fahrer_ids[$i]] = $this->aResultate[$iEtappe][$fahrer_ids[$i]]['rang'];
					}
					
				}
				
				
				asort($rang_array);
				$rang_array_komplett[$i][$l['user_id']] = $rang_array;
				
				array_pop($rang_array);
				
				$zeit = 0;
				$bc = 0;
				foreach($rang_array as $n=>$m){
					$zeit+= $this->aResultate[$iEtappe][$n]['rueckstand'];					
				}
				$teamresult[$l['user_id']] = $zeit;
				
			}
			
			asort($teamresult);
			
			$i=1;
			foreach($teamresult as $m=>$n) {
				if ($i <= 3) {
					$this->aRzTageswertungen[$iEtappe][$m]['zeit'] = array_values($teamresult)[2];
				} else if ($i > 3) {
					$this->aRzTageswertungen[$iEtappe][$m]['zeit'] = $n;
				}
				$i++;
			}
		}
	}
	
	/*
		
	Berechnet die Gesamtwertung für die Punktwertung bis zur im Konstruktor angegebenen Etappe
	
	Falls die ersten 2 Fahrer dieselbe Punktzahl haben wird ihre Gesamtklassierung als Tiebreaker verwendet.	
		
	*/
	
	private function _calculatePunkte() {
		foreach($this->aTeilnehmer as $t) {
			$punkte = 0;
			foreach ($this->aEtappen as $iNrEtappe=>$aEtappe) {
				$doped = false;
				$fahrer_ids = array();
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer1'];
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer2'];
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer3'];
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer4'];
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer5'];

				if (count($this->aDoping) > 0) {
					foreach($this->aDoping as $dk=>$d) {
						if ($d['etappen_id'] == $this->aEtappen[$iNrEtappe]['etappen_id']) {
							if ($t['user_id'] == $d['user_id']) {
								$doped = true;
							}
						}
					}
				}
				
				foreach($fahrer_ids as $k=>$v) {
					if ($doped == false) {
						if (isset($this->aResultatePunkte[$iNrEtappe][$v]['punkte'])) {
							$punkte = $punkte + $this->aResultatePunkte[$iNrEtappe][$v]['punkte'];
						}
					} 
				}
			}
			$this->aRzPunkte[$t['user_id']]['punkte'] = $punkte;
		}
		
		for($i=0;$i<count($this->aRzPunkte);$i++) {
			for($p=$i;$p<count($this->aRzPunkte);$p++) {
				if (array_values($this->aRzPunkte)[$i]['punkte'] < array_values($this->aRzPunkte)[$p]['punkte']) {
					$keys = array_keys($this->aRzPunkte);
					$this->aRzPunkte = $this->_array_flip_values($this->aRzPunkte, $keys[$i], $keys[$p]);
				}
				
			}
			
		}
		
		$rang = 1;
		foreach($this->aRzPunkte as $k=>$v) {
			$this->aRzPunkte[$k]['rang'] = $rang;
			$rang++;
		}
		
		if (array_values($this->aRzPunkte)[0]['punkte'] == array_values($this->aRzPunkte)[1]['punkte']) {
			// Die ersten 2 haben gleich viele Punkte ==> Tie-Breaker
			$keys = array_keys($this->aRzPunkte);
			if ($this->aGesamtwertung[$keys[0]]['rang'] > $this->aGesamtwertung[$keys[1]]['rang']) {
				$this->aRzPunkte[$keys[0]]['rang'] = 2;
				$this->aRzPunkte[$keys[1]]['rang'] = 1;
				$this->aRzPunkte = $this->_array_flip_values($this->aRzPunkte, $keys[0], $keys[1]);
			}	
		}
	}

	private function _calculateBerg() {
		// Punkte zusammenzählen
		foreach($this->aTeilnehmer as $t) {
			$bergpunkte = 0;
			foreach ($this->aEtappen as $iNrEtappe=>$aEtappe) {
				$doped = false;
				$fahrer_ids = array();
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer1'];
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer2'];
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer3'];
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer4'];
				$fahrer_ids[] = $this->aKader[$iNrEtappe][$t['user_id']]['fahrer5'];
				
				if (count($this->aDoping) > 0) {
					foreach($this->aDoping as $dk=>$d) {
						if ($d['etappen_id'] == $this->aEtappen[$iNrEtappe]['etappen_id']) {
							if ($t['user_id'] == $d['user_id']) {
								$doped = true;
							}
						}
					}
				}

				foreach($fahrer_ids as $k=>$v) {
					if ($doped == false) {
						if (isset($this->aResultateBerg[$iNrEtappe][$v]['bergpunkte'])) {
							$bergpunkte = $bergpunkte + $this->aResultateBerg[$iNrEtappe][$v]['bergpunkte'];
						}
					}
				}
			}
			$this->aRzBerg[$t['user_id']]['berg'] = $bergpunkte;
		}
		
		// Sortieren	
		for($i=0;$i<count($this->aRzBerg);$i++) {
			for($p=$i;$p<count($this->aRzBerg);$p++) {
				if (array_values($this->aRzBerg)[$i]['berg'] < array_values($this->aRzBerg)[$p]['berg']) {
					$keys = array_keys($this->aRzBerg);
					$this->aRzBerg = $this->_array_flip_values($this->aRzBerg, $keys[$i], $keys[$p]);
				}
			}
		}
		
		// Rang zuweisen
		$rang = 1;
		foreach($this->aRzBerg as $k=>$v) {
			$this->aRzBerg[$k]['rang'] = $rang;
			$rang++;
		}
		
		
		// Die ersten 2 haben gleich viele Punkte ==> Tie-Breaker
		if (array_values($this->aRzBerg)[0]['berg'] == array_values($this->aRzBerg)[1]['berg']) {			
			$keys = array_keys($this->aRzBerg);
			if ($this->aGesamtwertung[$keys[0]]['rang'] > $this->aGesamtwertung[$keys[1]]['rang']) {
				$this->aRzBerg[$keys[0]]['rang'] = 2;
				$this->aRzBerg[$keys[1]]['rang'] = 1;
				$this->aRzBerg = $this->_array_flip_values($this->aRzBerg, $keys[0], $keys[1]);
			}	
		}
		
	}

	private function _calculateTeam() {
		foreach ($this->aEtappen as $iNrEtappe=>$aEtappe) {
			foreach($this->aTeams as $k=>$v) {
				$l=0;
				$p = 0;
				$zeit = 0;
				while(($l<count($this->aRzTageswertungen[$iNrEtappe])) && ($p<3)) {
					$key = array_keys($this->aRzTageswertungen[$iNrEtappe]);
					$mem = array();
					foreach($v['members'] as $x=>$y) {
						$mem[]  = $y['user_id'];
					}
					
					if (in_array($key[$l], $mem)) {
						$zeit += $this->aRzTageswertungen[$iNrEtappe][$key[$l]]['zeit_real'];
						$p++;
					}
					$l++;
				}
				
				$this->aRzTeamwertungen[$iNrEtappe][$v['rzteam_id']]['zeit_real'] = $zeit;
			}
			
		}
		
		foreach ($this->aEtappen as $iNrEtappe=>$aEtappe) {
			for ($x=0;$x<count($this->aRzTeamwertungen[$iNrEtappe]);$x++) {
				for ($p=$x;$p<count($this->aRzTeamwertungen[$iNrEtappe]);$p++) {
					if (array_values($this->aRzTeamwertungen[$iNrEtappe])[$x]['zeit_real'] > array_values($this->aRzTeamwertungen[$iNrEtappe])[$p]['zeit_real']) {
						$keys = array_keys($this->aRzTeamwertungen[$iNrEtappe]);
						$this->aRzTeamwertungen[$iNrEtappe] = $this->_array_flip_values($this->aRzTeamwertungen[$iNrEtappe], $keys[$x], $keys[$p]);
					}
				}
				
			}
			$siegerzeit = array_values($this->aRzTeamwertungen[$iNrEtappe])[0]['zeit_real'];
			foreach($this->aRzTeamwertungen[$iNrEtappe] as $k=>$v) {
				$this->aRzTeamwertungen[$iNrEtappe][$k]['zeit'] = $this->aRzTeamwertungen[$iNrEtappe][$k]['zeit_real'] - $siegerzeit;
			}
		}
	}
	
	private function _calculateTeamGesamt() {
		$temp = array();
		
		foreach($this->aTeams as $t) {
			$i=0;
			$zeit = 0;
			foreach ($this->aEtappen as $iNrEtappe=>$aEtappe) {		
				$zeit += $this->aRzTeamwertungen[$iNrEtappe][$t['rzteam_id']]['zeit_real'];
				$i++;
			}		
			$temp[$t['rzteam_id']] = $zeit;
		}
		asort($temp);
		$fuehrung = array_values($temp)[0];
		
		$rang = 1;
		foreach($temp as $k=>$v) {
			$this->aRzTeam[$k]['zeit'] = $v - $fuehrung;
			$this->aRzTeam[$k]['rang'] = $rang;
			$rang++;
		}
		
	}
	
	private function _calculateGesamtwertung() {
		$temp = array();
		foreach($this->aTeilnehmer as $t) {
			$i = 1;
			$zeit = 0;
			foreach ($this->aEtappen as $iNrEtappe=>$aEtappe) {	
				if ($t['out'] == 1) {
					if ($t['out_etappen_id'] >= $this->aEtappen[$iNrEtappe]['etappen_id']) {
						$zeit += $this->aRzTageswertungen[$i][$t['user_id']]['zeit_real'];
						$i++;	
					}
				} else {
					$zeit += $this->aRzTageswertungen[$i][$t['user_id']]['zeit_real'];
					$i++;
				}
			}
			$temp[$t['user_id']] = $zeit;
			
		}
		asort($temp);
		$fuehrung = array_values($temp)[0];
		
		$rang = 1;
		foreach($temp as $k=>$v) {
			$this->aGesamtwertung[$k]['zeit'] = $v - $fuehrung;
			$this->aGesamtwertung[$k]['rang'] = $rang;
			$rang++;
		}
		
	}

	
	/*	
		Sortierungs-Funktionen
	*/
	
	private function _orderEtappeByNr($etappen) {
		foreach($etappen as $k=>$v) {
			$aReturn[$v['etappen_nr']] = $v;
		}
		return $aReturn;
	}
	
	private function _orderByUserId($kader) {
		foreach($kader as $k=>$v) {
			$aReturn[$v['user_id']] = $v;
		}
		return $aReturn;
	}
	
	private function _orderResultateByStage($kader) {
		$aReturn = array();
		if (count($kader) > 0) {
			foreach($kader as $k=>$v) {
				$aReturn[$v['fahrer_id']] = $v;
			}
		}
		return $aReturn;
	}
	
	private function _getAllStagesId() {
		$aReturn;
		if (count($this->aEtappen) > 0) {
			foreach($this->aEtappen as $k=>$v) {
				$aReturn[] = $v['etappen_id'];
			}
		}
		return $aReturn;
	}
	
	private function _array_flip_values($array, $id1, $id2) {
	     $temp = array();
	     foreach($array as $key=>$part) {
	         if($key != $id1 && $key != $id2)
	             $temp[$key] = $part;
	         elseif($key == $id1)
	             $temp[$id2] = $array[$id2];
	         elseif($key == $id2)
	             $temp[$id1] = $array[$id1];
	     }
	     return $temp;
	 }

}