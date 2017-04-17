<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Teilnahme_model extends MY_Model 
{
	
	public function getTeilnehmerForList() {
		
		$this->db->where('t.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->join('rz_user u', 'u.id=t.user_id');
		$this->db->join('rollen r', 'r.rolle_id=t.rolle_id');
		$aUser = $this->db->get('teilnahme t')->result_array();
		
		foreach($aUser as $k=>$v) {
			$this->db->where('ut.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->where('ut.user_id', $v['user_id']);
			$this->db->join('rz_team rt', 'rt.rzteam_id=ut.rz_team_id');
			$aUser[$k]['team'] = $this->db->get('rz_user_team ut')->row_array();
		}
		
		return $aUser;
	}
	
	public function checkTeilnahme() {
		$this->db->join('rollen r', 'r.rolle_id=t.rolle_id');
		$this->db->where('t.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('t.user_id', $this->session->userdata('user_id'));
		$aTeilnahme = $this->db->get('teilnahme t')->result_array();

		if (count($aTeilnahme) == 1) {
			$this->db->join('rz_team t', 't.rzteam_id=ut.rz_team_id');
			$this->db->where('ut.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->where('ut.user_id', $this->session->userdata('user_id'));
			$aTeilnahme[0]['team'] = $this->db->get('rz_user_team ut')->row_array();
			return $aTeilnahme[0];
		} else {
			return false;
		}
		
	}
	
	public function getRollen() {
		$aRollen = $this->getRows('rollen');
		
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aKapitaen = $this->db->get('rundfahrt_kapitaen')->row_array();
		if (count($aKapitaen) == 0) {
			unset($aRollen[0]);
		}
		
		$bIsNeo = true;
		for($i=1;$i<3;$i++) {
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt')-$i);
			$aTeilnahme = $this->db->get('teilnahme')->result_array();
			if (count($aTeilnahme) == 1) {
				if ($aTeilnahme[0]['out'] == 0) {
					$bIsNeo = false;
				}
			}
		}
		
		return $aRollen;	
	}
}