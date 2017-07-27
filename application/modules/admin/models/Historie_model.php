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
}