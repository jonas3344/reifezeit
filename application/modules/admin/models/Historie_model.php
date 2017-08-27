<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Historie_model extends MY_Model 
{
	public function getEtappenId($iRundfahrt, $iEtappenNr) {
		$this->db->select('id');
		$this->db->where('etappen_nr', $iEtappenNr);
		$this->db->where('rundfahrt_id', $iRundfahrt);
		$aTemp = $this->db->get('h_etappen')->row_array();
		return $aTemp['id'];
	}
	
	/*
		Holt die Historie-Id aufgrund der Rundfahrt-Id der Rundfahrt	
	*/
	
	public function getRundfahrt($iRundfahrt) {
		$aRundfahrt = $this->getOneRow('rundfahrt', 'rundfahrt_id=' . $iRundfahrt);
		
		$sRundfahrt = substr($aRundfahrt['rundfahrt_bezeichnung'], 0, 1);
		$this->db->select('id');
		$this->db->from('h_rundfahrten');
		$this->db->where('jahr', $aRundfahrt['rundfahrt_jahr']);
		$this->db->where('bezeichnung LIKE', $sRundfahrt . '%');
		$aRundfahrtH = $this->db->get()->row_array();

		return $aRundfahrtH['id'];
	}
	
	public function getTeilnehmerForHistory($iRundfahrt) {
		$this->db->join('rz_user_team ut', 'ut.user_id=t.user_id');
		$this->db->where('t.rundfahrt_id', $iRundfahrt);
		$this->db->where('ut.rundfahrt_id', $iRundfahrt);
		return $this->db->get('teilnahme t')->result_array();
	}
}