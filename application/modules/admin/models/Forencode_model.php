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
		$this->db->join('rz_user_team', 'rz_user_team.user_id=t.user_id');
		$this->db->join('rz_team rt', 'rt.rzteam_id=rz_user_team.rz_team_id');
		$this->db->join('rollen r', 't.rolle_id=r.rolle_id');
		$this->db->where('t.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aTeilnehmer = $this->db->get('teilnahme t')->result_array();
		
		$aReturn = array();
		foreach($aTeilnehmer as $k=>$v) {
			$aReturn[$v['id']] = $v;
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
		foreach($aTemp as $k=>$v) {
			if ($v['etappen_nr']<$iEtappeNr) {
				$aEtappen[] = $v;
			}
		}
		return $aEtappen;
	}
}