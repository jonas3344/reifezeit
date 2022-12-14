<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Parser_model extends MY_Model 
{
	public function checkFahrer($aFinal, $iType) {
		$this->db->join('fahrer f', 'f.fahrer_id=fr.fahrer_id');
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aFahrer = $this->db->get('fahrer_rundfahrt fr')->result_array();
		
		if ($iType == 1) {
			foreach($aFinal as $k=>$v) {
				foreach($aFahrer as $kf=>$vf) {
					$sName = $vf['fahrer_name'] . ' ' . $vf['fahrer_vorname'];
					//echo $sName . "-" . $v['namen'] . "_" . strcasecmp($v['namen'], $sName) . "<br>";
					
					if (strcasecmp($v['namen'], $sName) == 0) {
						$aFinal[$k]['fahrer_id'] = $vf['fahrer_id'];
						$aFinal[$k]['fahrer_startnummer'] = $vf['fahrer_startnummer'];
					}
				}
			}
		} else if ($iType == 2) {
			foreach($aFinal as $k=>$v) {
				foreach($aFahrer as $kf=>$vf) {					
					if ($v['fahrer_startnummer'] == $vf['fahrer_startnummer']) {
						$aFinal[$k]['fahrer_id'] = $vf['fahrer_id'];
					}
				}
			}
		}
		
		return $aFinal;
	}
	
	public function getFahrerInfo($iStartnummer) {
		$this->db->select('f.fahrer_name, f.fahrer_vorname, t.team_short');
		$this->db->join('fahrer f', 'f.fahrer_id=fr.fahrer_id');
		$this->db->join('team t', 'f.fahrer_team_id=t.team_id');
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('fr.fahrer_startnummer', $iStartnummer);
		return $this->db->get('fahrer_rundfahrt fr')->row_array();
	}
	
	public function saveBc($iNaechsteEtappe, $aAktuelleEtappe, $aWinner) {
		$aKader = array();
		$aTeilnehmer = array();
		$aKaderTemp = $this->getRows('kader', 'etappen_id=' . $aAktuelleEtappe['etappen_id']);
		

		foreach($aKaderTemp as $k=>$v) {
			$bDoping = $this->checkValueinDb('dopingfall', array('user_id'=>$v['user_id'], 'etappen_id' => $aAktuelleEtappe['etappen_id']));
			if ($bDoping == false) {
				$aKader[$k] = $this->_kaderIntoArray($v);
				$aTeilnehmer[$k] = $v['user_id'];
			}
		}
		
		
		foreach($aKader as $k=>$v) {
			$bc = 0;

			if ($aAktuelleEtappe['etappen_klassifizierung'] == 6) {
				// MZF
				$aSiegerTeams = array();
				
				$aSiegerTeams[] = $this->_getIdFromStartnummer($aWinner['iFirst']);
				$aSiegerTeams[] = $this->_getIdFromStartnummer($aWinner['iSecond']);
				$aSiegerTeams[] = $this->_getIdFromStartnummer($aWinner['iThird']);
				
				foreach($v as $p){
					if (in_array($this->_getFahrerTeamFromId($p), $aSiegerTeams)) {
						$bc += 1;
					}
				}
				
			} else {
				if (in_array($this->_getFahrerId($aWinner['iFirst']), $v)) {
					$bc += 3;
				}
				if (in_array($this->_getFahrerId($aWinner['iSecond']), $v)) {
					$bc += 2;
				}
				if (in_array($this->_getFahrerId($aWinner['iThird']), $v)) {
					$bc += 1;
				}
			}
			$aData['gewonnene_bonuscredits'] = $bc;
			
			echo $aTeilnehmer[$k] . '-' . $bc . "<br>";
			if ($bc > 0) {
				var_dump($v);
				echo "<br>";
			}
			

			$this->db->where('etappen_id', $iNaechsteEtappe);
			$this->db->where('user_id', $aTeilnehmer[$k]);
			$this->db->update('kader', $aData);
		}
		
		
	}
	
	public function finishRundfahrt($aGesamt, $aPunkte, $aBerg) {
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('out', 0);
		$aTeilnehmer = $this->db->get('teilnahme')->result_array();
		
		foreach($aTeilnehmer as $k=>$v) {
			
			$aData['endklassierung_gesamt'] = $aGesamt[$v['user_id']]['rang'];
			$aData['endklassierung_berg'] = $aBerg[$v['user_id']]['rang'];
			$aData['endklassierung_punkte'] = $aPunkte[$v['user_id']]['rang'];
			
			$this->db->where('user_id', $v['user_id']);
			$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->update('teilnahme', $aData);
			
		}
		
	}
	
	public function getEtappenTyp($iEtappe) {
		$this->db->select('etappen_klassifizierung');
		$this->db->from('etappen');
		$this->db->where('etappen_id', $iEtappe);
		
		return $this->db->get()->row_array();
	}
	
	public function getEtappenNr($iEtappe) {
		$this->db->select('etappen_nr');
		$this->db->from('etappen');
		$this->db->where('etappen_id', $iEtappe);
		
		return $this->db->get()->row_array();
	}
	
	private function _kaderIntoArray($aKader) {
		$aReturn[] = $aKader['fahrer1'];
		$aReturn[] = $aKader['fahrer2'];
		$aReturn[] = $aKader['fahrer3'];
		$aReturn[] = $aKader['fahrer4'];
		$aReturn[] = $aKader['fahrer5'];
		
		return $aReturn;
	}
	
	private function _getTeamIdFromStartnummer($iStartnummer) {
		$this->db->select('f.fahrer_team_id');
		$this->db->where('fr.fahrer_startnummer', $iStartnummer);
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->join('fahrer_rundfahrt fr', 'fr.fahrer_id = f.fahrer_id');
		$aFahrer = $db->getOne('fahrer f');
		
		return $aFahrer['fahrer_team_id'];	
	}
	
	private function _getFahrerTeamFromId($iFahrerId) {
		$fahrer = $this->getOneRow('fahrer', 'fahrer_id=' . $iFahrerId);		
		return $fahrer['fahrer_team_id'];		
	}
	
	private function _getFahrerId($iStartnummer) {
		$this->db->where('fr.fahrer_startnummer', $iStartnummer);
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->join('team t', 't.team_id = f.fahrer_team_id');
		$this->db->join('fahrer_rundfahrt fr', 'fr.fahrer_id = f.fahrer_id');
		$fahrer = $this->db->get('fahrer f')->row_array();
		
		return $fahrer['fahrer_id'];
	}
	
	public function deleteStageResults($iEtappe) {
		echo $iEtappe;
		$this->db->where('etappen_id', $iEtappe);
		$this->db->delete('resultate');
		
		$this->db->where('etappen_id', $iEtappe);
		$this->db->delete('resultate_berg');
		
		$this->db->where('etappen_id', $iEtappe);
		$this->db->delete('resultate_punkte');
		
		$this->db->where('etappe', $iEtappe);
		$this->db->delete('temp_berg');
		
		$this->db->where('etappe', $iEtappe);
		$this->db->delete('temp_punkte');
		
	}
	
	public function getTempDataYesterday($iEtappe, $type) {
		if ($type == 'punkte') {
			$this->db->select('fahrer_id, punkte');
			$this->db->where('etappe', ($iEtappe-1));
			$return = $this->db->get('temp_punkte')->result_array();
		} else if ($type == 'berg') {
			$this->db->select('fahrer_id, berg');
			$this->db->where('etappe', ($iEtappe-1));
			$return = $this->db->get('temp_berg')->result_array();
		} 
		return $return;
	}
	
	private function _getIdFromStartnummer($iStartnummer) {
		$this->db->select('f.fahrer_team_id');
		$this->db->from('fahrer_rundfahrt fr');
		$this->db->join('fahrer f', 'f.fahrer_id=fr.fahrer_id');
		$this->db->where('fr.fahrer_startnummer', $iStartnummer);
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aResult = $this->db->get()->row_array();
		return $aResult['fahrer_team_id'];
	}
}