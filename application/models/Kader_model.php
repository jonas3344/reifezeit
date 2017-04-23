<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Kader_model extends MY_Model 
{
	public function getEtappe($iEtappe) {		
		$this->db->join('etappen_klassifizierungen ek', 'ek.klassifizierung_id=e.etappen_klassifizierung');
		$this->db->where('e.etappen_id', $iEtappe);
		$this->db->order_by('e.etappen_nr', 'ASC');
		return $this->db->get('etappen e')->row_array();
	}
	
	public function getEtappen() {		
		$this->db->join('etappen_klassifizierungen ek', 'ek.klassifizierung_id=e.etappen_klassifizierung');
		$this->db->where('e.etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->order_by('e.etappen_nr', 'ASC');
		return $this->db->get('etappen e')->result_array();
	}
	
	public function getKader($iEtappe) {
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('etappen_id', $iEtappe);
		$aKader = $this->db->get('kader')->row_array();
			
		$aTemp = $this->_getFahrerId($aKader);
		
		foreach($aTemp as $k=>$v) {
			$this->db->join('fahrer f', 'fr.fahrer_id=f.fahrer_id');
			$this->db->join('team t', 't.team_id=f.fahrer_team_id');
			$this->db->where('fr.fahrer_id', $v);
			$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$aKader['aFahrer'][] = $this->db->get('fahrer_rundfahrt fr')->row_array();
		}
		
		return $aKader;
	}
	
	public function getAlleKader() {
		$aEtappen = $this->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'));
		$aReturn = array();
		$this->load->library('wechsel', $aEtappen[0]['etappen_id']);
		
		foreach($aEtappen as $k=>$v) {
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->where('etappen_id', $v['etappen_id']);
			$aReturn[$k]['aKader'] = $this->db->get('kader')->row_array();
			$aReturn[$k]['aEtappe'] = $v;
			
			$sColor = '';
			if ($v['etappen_klassifizierung'] == 1) {
				$sColor = ' class = success';
			} else if ($v['etappen_klassifizierung'] == 2) {
				$sColor = ' class = active';
			} else if ($v['etappen_klassifizierung'] == 3) {
				$sColor = ' class = warning';
			} else if ($v['etappen_klassifizierung'] == 4) {
				$sColor = ' class = danger';
			} else if ($v['etappen_klassifizierung'] == 5) {
				$sColor = ' class = warning';
			} else if ($v['etappen_klassifizierung'] == 6) {
				$sColor = ' class = warning';
			}
			$aReturn[$k]['aEtappe']['sColor'] = $sColor;
				
			$aTemp = $this->_getFahrerId($aReturn[$k]['aKader']);
			
			$this->wechsel->__construct($v['etappen_id']);
			
			$aWechsel = $this->wechsel->getChangeByRider($this->session->userdata('user_id'));
			
			foreach($aTemp as $p=>$l) {
				$this->db->join('fahrer f', 'fr.fahrer_id=f.fahrer_id');
				$this->db->join('team t', 't.team_id=f.fahrer_team_id');
				$this->db->where('fr.fahrer_id', $l);
				$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
				$aTemp = $this->db->get('fahrer_rundfahrt fr')->row_array();
				if (in_array($l, $aWechsel)) {
					$aTemp['change'] = 1;
				} else {
					$aTemp['change'] = 0;
				}

				$aReturn[$k]['aKader']['aFahrer'][] = $aTemp;
			}
		}
		return $aReturn;
	}
	
	public function getDopingAll() {
		$this->db->join('etappen e', 'e.etappen_id=d.etappen_id');
		$this->db->where('d.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('d.user_id', $this->session->userdata('user_id'));
		return $this->db->get('dopingfall d')->result_array();
	}
	
	public function getUser() {
		$this->db->join('rollen r', 't.rolle_id=r.rolle_id');
		$this->db->where('t.user_id', $this->session->userdata('user_id'));
		$this->db->where('t.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		
		return $this->db->get('teilnahme t')->row_array();
	}
	
	public function getDoping($iEtappe) {
		$iEtappenNr = $this->_getEtappenNr($iEtappe);
		
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$aDoping = $this->db->get('dopingfall')->row_array();
		
		if ($iEtappenNr > $this->_getEtappenNr($aDoping['etappen_id'])) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function getCA($sArt, $iEtappe) {
		if ($sArt == 'an') {
			$this->db->join('rz_user u', 'ca.abgeber=u.id');
			$this->db->where('ca.etappen_id', $iEtappe);
			$this->db->where('ca.empfaenger', $this->session->userdata('user_id'));
			$aReturn = $this->db->get('creditabgabe ca')->result_array();
		} else if ($sArt == 'ab') {
			$this->db->join('rz_user u', 'ca.empfaenger=u.id');
			$this->db->where('ca.etappen_id', $iEtappe);
			$this->db->where('ca.abgeber', $this->session->userdata('user_id'));
			$aReturn = $this->db->get('creditabgabe ca')->result_array();
		}
		return $aReturn;
	}
	
	public function getFahrerForDropdown($iSort) {
		$this->db->select('fr.fahrer_startnummer, f.fahrer_id, f.fahrer_name, f.fahrer_vorname, t.team_short, fr.fahrer_rundfahrt_credits');
		$this->db->join('fahrer f', 'fr.fahrer_id=f.fahrer_id');
		$this->db->join('team t', 't.team_id=f.fahrer_team_id');
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		if ($iSort == 1) {
			$this->db->order_by('fr.fahrer_startnummer', 'ASC');
		} else if ($iSort == 2) {
			$this->db->order_by('fr.fahrer_rundfahrt_credits', 'DESC');
			$this->db->order_by('fr.fahrer_startnummer', 'ASC');
		}
		return $this->db->get('fahrer_rundfahrt fr')->result_array();
	}
	
	public function saveKader($iEtappe, $sPosition, $iFahrer) {
		// Check
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('etappen_id', $iEtappe);
		$aKader = $this->db->get('kader')->row_array();
			
		$aTemp = $this->_getFahrerId($aKader);
		
		if (in_array($iFahrer, $aTemp)) {
			return 'Du hast diesen Fahrer bereits im Kader!';
		}
		
		$aEtappe = $this->getOneRow('etappen', 'etappen_id=' . $iEtappe);
		
		$this->db->where('etappen_nr>=', $aEtappe['etappen_nr']);
		$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aAllEtappen = $this->db->get('etappen')->result_array();
		
		foreach($aAllEtappen as $k=>$v) {
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->where('etappen_id', $v['etappen_id']);
			$this->db->update('kader', array($sPosition=>$iFahrer));
		}
		return 'ok';
	}
	
	public function getTeamMembers() {
		$this->db->select('rz_team_id');
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$aTeamOfUser = $this->db->get('rz_user_team')->row_array();
		
		if (count($aTeamOfUser) == 1) {
			$this->db->join('rz_team t', 't.rzteam_id=ut.rz_team_id');
			$this->db->join('rz_user u', 'u.id=ut.user_id');
			$this->db->where('ut.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->where('ut.rz_team_id', $aTeamOfUser['rz_team_id']);
			$aTemp = $this->db->get('rz_user_team ut')->result_array();
			
			foreach($aTemp as $k=>$v) {
				if ($v['user_id'] == $this->session->userdata('user_id')) {
					unset($aTemp[$k]);
				}
			}
			return $aTemp;
		}  else {
			return false;
		}
	}
	
	public function addFex($iPunkte, $iEtappe) {
		$this->db->where('etappen_id', $iEtappe);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->update('kader', array('einsatz_creditpool' => $iPunkte));
	}
	
	public function getEingesetzteFexPuntke() {
		$this->db->join('etappen e', 'e.etappen_id=k.etappen_id');
		$this->db->where('e.etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('k.user_id', $this->session->userdata('user_id'));
		$this->db->where('k.einsatz_creditpool>', 0);
		return $this->db->get('kader k')->result_array();
	}
	
	private function _getFahrerId($aArray) {
		return array($aArray['fahrer1'], $aArray['fahrer2'], $aArray['fahrer3'], $aArray['fahrer4'], $aArray['fahrer5']);
	}
	
	public function _getEtappenNr($iEtappe) {
		$aEtappe = $this->getOneRow('etappen', 'etappen_id=' . $iEtappe);
		return $aEtappe['etappen_nr'];
	}
}