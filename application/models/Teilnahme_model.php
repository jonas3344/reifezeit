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
		$aRollen = $this->getTable('rollen');
		
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
		
		if ($bIsNeo == true) {
			foreach($aRollen as $i_key => $a_r) {
				if ($a_r['rolle_bezeichnung'] != 'Neoprofi') {
					unset($aRollen[$i_key]);
				}
			}
		} else {
			foreach($aRollen as $i_key => $a_r) {
				if ($a_r['rolle_bezeichnung'] == 'Neoprofi') {
					unset($aRollen[$i_key]);
				}
			}
		}
		
		return $aRollen;	
	}
	
	public function insertAnmeldung($iRolle, $iTeam) {
		$aRolle = $this->getOneRow('rollen', 'rolle_id=' . $iRolle);
		$aData = array(
				'rolle_id'=>$iRolle, 
				'user_id'=>$this->session->userdata('user_id'), 
				'rundfahrt_id'=>$this->config->item('iAktuelleRundfahrt'),
				'creditabgabe' => $aRolle['creditabgabe'],
				'creditempfang' => $aRolle['creditannahme'],
				'creditmoves' => 1
		);
		
		$this->saveRecord('teilnahme', $aData, -1);
		
		$this->saveRecord('rz_user_team', array('rz_team_id'=>$iTeam, 'user_id'=>$this->session->userdata('user_id'), 'rundfahrt_id'=>$this->config->item('iAktuelleRundfahrt')), -1);
		
		$aEtappen = $this->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'));
		
		foreach($aEtappen as $e) {
			$this->saveRecord('kader', array('etappen_id'=>$e['etappen_id'], 'user_id'=>$this->session->userdata('user_id')), -1);
		}
		echo 'ok';
	}
	
	public function getHistorie() {
		$aHistory = $this->getRows('rundfahrt', 'rundfahrt_id<' . $this->config->item('iAktuelleRundfahrt'));
		
		foreach($aHistory as $k=>$v) {
			$this->db->join('rz_user u', 'u.id=t.user_id');
			$this->db->join('rz_user_team ut', 'ut.user_id=t.user_id');
			$this->db->join('rz_team rt', 'rt.rzteam_id=ut.rz_team_id');
			$this->db->join('rollen r', 'r.rolle_id=t.rolle_id');
			$this->db->where('t.rundfahrt_id', $v['rundfahrt_id']);
			$this->db->where('ut.rundfahrt_id', $v['rundfahrt_id']);
			$this->db->where('t.user_id', $this->session->userdata('user_id'));
			$aHistory[$k]['aTeilnahme'] = $this->db->get('teilnahme t')->row_array();
		}
		return $aHistory;
	}
	
	public function getTeilnahmeData($iId) {
		$this->db->where('user_id', $iId);
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aReturn = $this->db->get('teilnahme')->row_array();
		
		$this->db->join('etappen e', 'e.etappen_id=k.etappen_id');
		$this->db->where('k.user_id', $iId);
		$this->db->where('e.etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aKader = $this->db->get('kader k')->result_array();
		
		$iFex = 0;
		foreach($aKader as $k=>$v) {
			$iFex += $v['einsatz_creditpool'];
		}
		
		$aReturn['fex'] = $iFex;
		
		return $aReturn;
	}
}