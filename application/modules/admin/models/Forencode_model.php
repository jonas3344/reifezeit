<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Forencode_model extends MY_Model 
{
	public function getTeilnehmerForum() {
		$this->db->join('rz_user u', 'u.id=t.user_id');
		$this->db->join('rollen r', 't.rolle_id=r.rolle_id');
		$this->db->where('t.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aTeilnehmer = $this->db->get('teilnahme t')->result_array();
		
		$aReturn = array();
		foreach($aTeilnehmer as $k=>$v) {
			$this->db->where('rut.user_id', $v['user_id']);
			$this->db->where('rut.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->join('rz_team rt', 'rut.rz_team_id=rt.rzteam_id');
			$aTeam = $this->db->get('rz_user_team rut')->row_array();
			if ($aTeam == null) {
				$aTeam = array('rzteam_short'=>'none');
			}			
			$aReturn[$v['id']] = array_merge($v, $aTeam);
		}
		return $aReturn;
	}
	
	public function getTeamsForum() {
		$aTeams = $this->db->get('rz_team')->result_array();
		
		$aReturn = array();
		foreach($aTeams as $k=>$v) {
			$aReturn[$v['rzteam_id']] = $v;
		}
		return $aReturn;
	}
	
	public function getPastEtappen() {
		$aTemp = $this->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
		$aEtappen = array();
		$iEtappeNr = $this->_getEtappenNr($this->config->item('iAktuelleEtappe'));
		if ($iEtappeNr == 21) {
			foreach($aTemp as $k=>$v) {
				$aEtappen[] = $v;
			}
		} else {
			foreach($aTemp as $k=>$v) {
				if ($v['etappen_nr']<$iEtappeNr) {
					$aEtappen[] = $v;
				}
			}
		}
		
		return $aEtappen;
	}
	
	public function setOtl($iUser, $iEtappe) {
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('user_id', $iUser);
		$this->db->update('teilnahme', array('out'=>1, 'out_etappen_id'=>$iEtappe));
		echo 'ok';
	}
}