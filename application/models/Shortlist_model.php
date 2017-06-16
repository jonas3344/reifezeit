<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Shortlist_model extends MY_Model 
{
	
	public function checkFahrerShortlist($iFahrer, $iShortlist) {
		$this->db->select('id');
		$this->db->where('shortlist_id', $iShortlist);
		$this->db->where('fahrer_id', $iFahrer);
		$aFahrer = $this->db->get('shortlists_fahrer')->row_array();
		if (count($aFahrer) == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public function getFahrerForShortlist($iShortlist) {
		$this->db->select('t.team_short, f.fahrer_id, f.fahrer_name, f.fahrer_vorname, f.fahrer_nation, fr.fahrer_startnummer, fr.fahrer_rundfahrt_credits');
		$this->db->join('fahrer f', 'sf.fahrer_id=f.fahrer_id');
		$this->db->join('team t', 'f.fahrer_team_id=t.team_id');
		$this->db->join('fahrer_rundfahrt fr', 'fr.fahrer_id=f.fahrer_id');
		$this->db->from('shortlists_fahrer sf');
		$this->db->where('sf.shortlist_id', $iShortlist);
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('fr.ausgeschieden', 0);
		$this->db->order_by('fr.fahrer_rundfahrt_credits', 'DESC');
		return $this->db->get()->result_array();
		
	}
	
	public function removeFahrerFromShortlist($iShortlist, $iFahrer) {
		echo $iShortlist . '_' . $iFahrer;
		$this->db->where('shortlist_id', $iShortlist);
		$this->db->where('fahrer_id', $iFahrer);
		$this->db->delete('shortlists_fahrer');
	}
	
	public function changeShare($iShortlist, $iValue) {
		$this->saveRecord('shortlists', array('share_to_team' => $iValue), $iShortlist, 'id');
	}
}