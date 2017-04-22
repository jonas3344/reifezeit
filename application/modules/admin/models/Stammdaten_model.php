<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Stammdaten_model extends MY_Model 
{

	public function getEtappen($rundfahrt_id) {
		$this->db->where('e.etappen_rundfahrt_id', $rundfahrt_id);
		$this->db->join('etappen_klassifizierungen ek', 'ek.klassifizierung_id=e.etappen_klassifizierung');
		$this->db->order_by('e.etappen_nr', 'ASC');
		return $this->db->get('etappen e')->result_array();
	}
	
	public function getEk() {
		$aEk = $this->getTable('etappen_klassifizierungen');
		
		$aReturn = array();
		
		foreach($aEk as $k=>$v) {
			$aReturn[$v['klassifizierung_id']] = $v['klassifizierung_name'];
		}
		return $aReturn;
	}
	
	public function getLatestEtappenNr($iRundfahrt) {
		$this->db->where('etappen_rundfahrt_id', $iRundfahrt);
		$this->db->order_by('etappen_nr', 'DESC');
		$aEtappe = $this->db->get('etappen')->row_array();
		return $aEtappe['etappen_nr'];
		
	}
	
	public function getFahrer() {
		$this->db->join('team t', 'f.fahrer_team_id=t.team_id');
		$this->db->where('f.fahrer_active', 1);
		return $this->db->get('fahrer f')->result_array();
	}
}