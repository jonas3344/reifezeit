<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Rundfahrt_model extends MY_Model 
{
	
	public function getFahrerByTeams() {
		
		$this->db->join('team t', 't.team_id=tr.team_id');
		$this->db->where('tr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->order_by('tr.start_order', 'ASC');
		$aData = $this->db->get('team_rundfahrt tr')->result_array();
		
		foreach($aData as $k=>$v) {
			$this->db->join('fahrer f', 'f.fahrer_id=fr.fahrer_id');
			$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->where('f.fahrer_team_id', $v['team_id']);
			$this->db->order_by('fr.fahrer_startnummer', 'ASC');
			$aData[$k]['fahrer'] = $this->db->get('fahrer_rundfahrt fr')->result_array();
		}
		
		return $aData;
	}
	
	public function getFahrerBySort($iSort) {
		$this->db->join('fahrer f', 'f.fahrer_id=fr.fahrer_id');
		$this->db->join('team t', 't.team_id=f.fahrer_team_id');
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		if ($iSort == 2) {
			$this->db->order_by('fr.fahrer_rundfahrt_credits', 'DESC');
		} else if ($iSort == 3) {
			$this->db->order_by('f.fahrer_name', 'ASC');
		}
		$aData = $this->db->get('fahrer_rundfahrt fr')->result_array();
		return $aData;
	}
	
	public function getEtappen() {		
		$this->db->join('etappen_klassifizierungen ek', 'ek.klassifizierung_id=e.etappen_klassifizierung');
		$this->db->where('e.etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->order_by('e.etappen_nr', 'ASC');
		$aTemp = $this->db->get('etappen e')->result_array();
		
		$aEtappen = array();
		
		foreach($aTemp as $k=>$v) {
			$aEtappen[$v['etappen_id']] = $v;
		}
		
		return $aEtappen;
	}
	
	public function getResultate($iEtappe) {
		// Etappenresultate
		$this->db->join('fahrer f', 'f.fahrer_id=r.fahrer_id');
		$this->db->join('team t', 'f.fahrer_team_id=t.team_id');
		$this->db->where('r.etappen_id', $iEtappe);
		$this->db->order_by('r.rang', 'ASC');
		$aResult['aEtappe'] = $this->db->get('resultate r')->result_array();
		
		// Punkte
		$this->db->join('fahrer f', 'f.fahrer_id=r.fahrer_id');
		$this->db->join('team t', 'f.fahrer_team_id=t.team_id');
		$this->db->where('r.etappen_id', $iEtappe);
		$this->db->order_by('r.punkte', 'DESC');
		$aResult['aPunkte'] = $this->db->get('resultate_punkte r')->result_array();
		
		// Bergpunkte
		$this->db->join('fahrer f', 'f.fahrer_id=r.fahrer_id');
		$this->db->join('team t', 'f.fahrer_team_id=t.team_id');
		$this->db->where('r.etappen_id', $iEtappe);
		$this->db->order_by('r.bergpunkte', 'DESC');
		$aResult['aBerg'] = $this->db->get('resultate_berg r')->result_array();
		
		return $aResult;
	}
	
	public function getLatestStageResult() {
		$this->db->select('etappen_id');
		$this->db->from('resultate');
		$this->db->order_by('etappen_id', 'DESC');
		$aTemp = $this->db->get()->row_array();
		return $aTemp['etappen_id'];
	}
	
	public function getEtappenId($iEtappen_nr) {
		$this->db->select('etappen_id');
		$this->db->from('etappen');
		$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('etappen_nr', $iEtappen_nr);
		$aTemp = $this->db->get()->row_array();
		
		return $aTemp['etappen_id'];
	}
	
}