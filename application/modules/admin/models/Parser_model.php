<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Parser_model extends MY_Model 
{
	public function checkFahrer($sFahrerName, $sFahrerVorname) {
		$this->db->join('fahrer f', 'f.fahrer_id=fr.fahrer_id');
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->like('LOWER(f.fahrer_name)', strtolower($sFahrerName));
		$this->db->like('LOWER(f.fahrer_vorname)', strtolower($sFahrerVorname));
		$aFahrer = $this->db->get('fahrer_rundfahrt fr')->row_array();
		return $aFahrer;
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
			$aKader[$k] = $this->_kaderIntoArray($v);
			$aTeilnehmer[$k] = $v['user_id'];
		}
		
		foreach($aKader as $k=>$v) {
			$bc = 0;
			

			if ($aAktuelleEtappe['etappen_klassifizierung'] == 6) {
				// MZF
				$aSiegerTeams = array();
				
				$aSiegerTeams[] = $this->_getIdFromStartnummer($aWinner['iFirst']);
				$aSiegerTeams[] = $this->_getIdFromStartnummer($aWinner['iSecond']);
				$aSiegerTeams[] = $this->_getIdFromStartnummer($aWinner['iThirst']);
				
				foreach($k as $p){
					if (in_array($this->_getFahrerTeamFromId($p), $aSiegerTeams)) {
						$bc += 1;
					}
				}
				
			} else {
				if (in_array($this->_getFahrerId($aWinner['iFirst']), $k)) {
					$bc += 3;
				}
				if (in_array($this->_getFahrerId($aWinner['iSecond']), $k)) {
					$bc += 2;
				}
				if (in_array($this->_getFahrerId($aWinner['iThirst']), $k)) {
					$bc += 1;
				}
			}
		}
		
		$aData['gewonnene_bonuscredits'] = $bc;
		$this->db->where('etappen_id', $iNaechsteEtappe);
		$this->db->where('user_id', $aTeilnehmer[$k]);
		$this->db->update('kader', $aData);
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
	
	private function _kaderIntoArray($aKader) {
		$aReturn[] = $aKader['fahrer1'];
		$aReturn[] = $aKader['fahrer2'];
		$aReturn[] = $aKader['fahrer3'];
		$aReturn[] = $aKader['fahrer4'];
		$aReturn[] = $aKader['fahrer5'];
		
		return $a_return;
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
		$this->db->where('fr.fahrer_startnummer', $startnummer);
		$this->db->where('fr.rundfahrt_id', $rundfahrt);
		$this->db->join('team t', 't.team_id = f.fahrer_team_id');
		$this->db->join('fahrer_rundfahrt fr', 'fr.fahrer_id = f.fahrer_id');
		$fahrer = $db->this->get('fahrer f')->row_array();
		
		return $fahrer['fahrer_id'];
	}
}