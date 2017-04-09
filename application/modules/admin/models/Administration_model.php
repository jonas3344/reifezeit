<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Administration_model extends MY_Model 
{
	public function getTeilnehmer($iRundfahrt) {
		
		$this->db->where('t.rundfahrt_id', $iRundfahrt);
		$this->db->join('rz_user u', 'u.id=t.user_id');
		$this->db->join('rollen r', 'r.rolle_id=t.rolle_id');
		$aUser = $this->db->get('teilnahme t')->result_array();
		
		foreach($aUser as $k=>$v) {
			$this->db->where('ut.rundfahrt_id', $iRundfahrt);
			$this->db->where('ut.user_id', $v['user_id']);
			$this->db->join('rz_team rt', 'rt.rzteam_id=ut.rz_team_id');
			$aUser[$k]['team'] = $this->db->get('rz_user_team ut')->row_array();
		}
		
		return $aUser;
	}
	
	public function getTransfermarkt($iRundfahrt) {
		$this->db->where('tr.rundfahrt_id', $iRundfahrt);
		$this->db->join('team t', 't.team_id=tr.team_id');
		$this->db->order_by('tr.start_order', 'ASC');
		
		$aTeams = $this->db->get('team_rundfahrt tr')->result_array();
		
		foreach($aTeams as $k=>$v) {
			$this->db->join('fahrer f', 'f.fahrer_id=fr.fahrer_id');
			$this->db->where('fr.rundfahrt_id', $iRundfahrt);
			$this->db->where('f.fahrer_team_id', $v['team_id']);			
			$this->db->order_by('fr.fahrer_startnummer', 'ASC');
			$aTeams[$k]['fahrer'] = $this->db->get('fahrer_rundfahrt fr')->result_array();		
		}
		
		return $aTeams;
	}
	
	public function saveCredits($aData) {
		$this->db->where('rundfahrt_id', $aData['rundfahrt_id']);
		$this->db->where('fahrer_id', $aData['fahrer_id']);
		$this->db->update('fahrer_rundfahrt', array('fahrer_rundfahrt_credits'=>$aData['fahrer_rundfahrt_credits']));
	}
}