<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Historie_model extends MY_Model 
{
	public function getHistorie($iUser) {	
		$this->db->where('ht.user_id', $iUser);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$this->db->join('rz_team rt', 'ht.team_id=rt.rzteam_id', 'LEFT');
		$this->db->join('h_team_teilnahme tt', 'ht.rundfahrt_id=tt.rundfahrt_id AND tt.team_id=ht.team_id', 'LEFT');
		$aHistory = $this->db->get('h_teilnahme ht')->result_array();
		return $aHistory;
	}
	
	public function getTeams($iUser) {
		$this->db->select('rzteam_name');
		$this->db->where('ht.user_id', $iUser);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$this->db->join('rz_team rt', 'ht.team_id=rt.rzteam_id');
		$this->db->group_by('ht.team_id');
		$aHistory = $this->db->get('h_teilnahme ht')->result_array();
		return $aHistory;
	}
	
	public function getGesamtErfolge($iUser) {
		$aSucess = array();
		$this->db->where('ht.user_id', $iUser);
		$this->db->where('ht.rang_gw', 1);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$aSuccess['aGesamt'] = $this->db->get('h_teilnahme ht')->result_array();
		
		$this->db->where('ht.user_id', $iUser);
		$this->db->where('ht.rang_punkte', 1);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$aSuccess['aPunkte'] = $this->db->get('h_teilnahme ht')->result_array();
		
		$this->db->where('ht.user_id', $iUser);
		$this->db->where('ht.rang_berg', 1);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$aSuccess['aBerg'] = $this->db->get('h_teilnahme ht')->result_array();
		
		return $aSuccess;
	}
	
	public function getEtappenSiege($iUser) {
		$this->db->select('e.etappen_nr, r.bezeichnung, r.jahr');
		$this->db->from('h_etsieger es');
		$this->db->where('es.user_id', $iUser);
		$this->db->join('h_etappen e', 'es.etappen_id=e.id');
		$this->db->join('h_rundfahrten r', 'r.id=e.rundfahrt_id');
		return $this->db->get()->result_array();
	}
	
	public function getLeadertrikots($iUser, $iReturnType = 1) {
		$this->db->select('l.user_id, l.type, e.etappen_nr, r.bezeichnung, r.jahr');
		$this->db->from('h_leader l');
		$this->db->join('h_etappen e', 'e.id=l.etappen_id');
		$this->db->join('h_rundfahrten r', 'r.id=e.rundfahrt_id');
		$this->db->where('l.user_id', $iUser);
		$aTemp = $this->db->get()->result_array();
		
		$aLeader = array();
		
		if ($iReturnType == 1) {
			foreach($aTemp as $k=>$v) {
				$aLeader[$v['type']][] = $v;
			}
		} else {
			$aLeader = $aTemp;
		}

		return $aLeader;
	}
	
	public function collectTimelineData($iUser) {
		$aTimelineData = array();
	
		
		// Erste Rundfahrt
		$this->db->select('r.bezeichnung, r.jahr, r.id');
		$this->db->from('h_teilnahme t');
		$this->db->where('t.user_id', $iUser);
		$this->db->join('h_rundfahrten r', 'r.id=t.rundfahrt_id');
		$this->db->order_by('r.id', 'ASC');
		$this->db->limit(1);
		$aFirst = $this->db->get()->row_array();
		
		
		$aTimelineData[$aFirst['jahr']]['aFirst'] = $aFirst;
		

		$this->db->select('e.etappen_nr, r.id, r.jahr, r.bezeichnung');
		$this->db->from('h_etsieger es');
		$this->db->join('h_etappen e', 'es.etappen_id=e.id');
		$this->db->join('h_rundfahrten r', 'r.id=e.rundfahrt_id');
		$this->db->where('es.user_id', $iUser);
		$this->db->order_by('r.id', 'ASC');
		$this->db->order_by('e.etappen_nr', 'ASC');
		
		$aEtsiege = $this->db->get()->result_array();
		
		foreach($aEtsiege as $k=>$v) {
			$aData = array('rundfahrt' => $v['bezeichnung'] . ' ' . $v['jahr'],
							'text' => 'Etappensieg ' . $v['etappen_nr'] . '.Etappe',
							'art' => 4);
			$aTimelineData[$v['jahr']][$v['id']][] = $aData;
		}
		
				// Gesamterfolge
		$this->db->select('r.bezeichnung, r.jahr, t.rang_gw, t.rang_punkte, t.rang_berg, t.user_id, r.id');
		$this->db->from('h_teilnahme t');
		$this->db->join('h_rundfahrten r', 'r.id=t.rundfahrt_id');
		$this->db->where('t.user_id = ' . $iUser . ' AND (t.rang_gw=1 OR t.rang_punkte=1 OR t.rang_berg=1)');
		$this->db->order_by('r.id', 'ASC');
		$aErfolge = $this->db->get()->result_array();
		
		foreach($aErfolge as $k=>$v) {
			if ($v['rang_gw'] == 1) {
				$aData = array('rundfahrt' => $v['bezeichnung'] . ' ' . $v['jahr'],
								'text' => 'Gesamtsieg',
								'art' => 1);
			} else if ($v['rang_punkte'] == 1) {
				$aData = array('rundfahrt' => $v['bezeichnung'] . ' ' . $v['jahr'],
								'text' => 'Punktesieger',
								'art' => 2);
			} else if ($v['rang_berg'] == 1) {
				$aData = array('rundfahrt' => $v['bezeichnung'] . ' ' . $v['jahr'],
								'text' => 'Bergpreissieger',
								'art' => 3);
			}
			$aTimelineData[$v['jahr']][$v['id']][] = $aData;
		}
		
		
		ksort($aTimelineData);
		
		//$aTimelineData['aEtSiege'] = $aEtsiege;
		
		return $aTimelineData;
	}
	
	public function getTeilnahmen() {
		$this->db->select('u.name, u.id, COUNT(ht.id) AS anzahl');
		$this->db->from('h_teilnahme ht');
		$this->db->join('rz_user u', 'u.id=ht.user_id');
		$this->db->group_by('ht.user_id');
		$this->db->order_by('anzahl', 'DESC');
		$this->db->order_by('u.id', 'ASC');
		$aTemp = $this->db->get()->result_array();
		
		$aTeilnahmen = array();
		$aTeilnahmen[] = $aTemp[0];
		$aTeilnahmen[0]['rang'] = 1;
		$iRang = 1;
		$iPrev = $aTemp[0]['anzahl'];
		unset($aTemp[0]);
		foreach($aTemp as $k=>$v) {
			$iRang++;
			if ($v['anzahl'] < $iPrev) {			
				$v['rang'] = $iRang;
				$iPrev = $v['anzahl']	;		
			} else {
				$v['rang'] = '-';
			}
			$aTeilnahmen[] = $v;
		}
		
		return $aTeilnahmen;
	}
	
	public function getEtappenSiegeForList() {
		$this->db->select('u.name, u.id, COUNT(ets.id) AS anzahl');
		$this->db->from('h_etsieger ets');
		$this->db->join('rz_user u', 'u.id=ets.user_id');
		$this->db->group_by('ets.user_id');
		$this->db->order_by('anzahl', 'DESC');
		$this->db->order_by('u.id', 'ASC');
		$aTemp = $this->db->get()->result_array();
		
		$aEts = array();
		$aEts[] = $aTemp[0];
		$aEts[0]['rang'] = 1;
		$iRang = 1;
		$iPrev = $aTemp[0]['anzahl'];
		unset($aTemp[0]);
		foreach($aTemp as $k=>$v) {
			$iRang++;
			if ($v['anzahl'] < $iPrev) {			
				$v['rang'] = $iRang;
				$iPrev = $v['anzahl']	;		
			} else {
				$v['rang'] = '-';
			}
			$aEts[] = $v;
		}
		
		return $aEts;
	}
	
	public function getDataForGwTable($type) {
		$this->db->select('u.name, u.id, COUNT(ht.id) AS anzahl');
		$this->db->from('h_teilnahme ht');
		$this->db->join('rz_user u', 'u.id=ht.user_id');
		$this->db->where('ht.' . $type . '=1');
		$this->db->group_by('ht.user_id');
		$this->db->order_by('anzahl', 'DESC');
		$this->db->order_by('u.id', 'ASC');
		$aTemp = $this->db->get()->result_array();
		
		$aTeilnahmen = array();
		$aTeilnahmen[] = $aTemp[0];
		$aTeilnahmen[0]['rang'] = 1;
		$iRang = 1;
		$iPrev = $aTemp[0]['anzahl'];
		unset($aTemp[0]);
		foreach($aTemp as $k=>$v) {
			$iRang++;
			if ($v['id'] == $this->session->userdata('user_id')) {
				$v['session_user'] = 1;
			} else {
				$v['session_user'] = 0;
			}
			if ($v['anzahl'] < $iPrev) {			
				$v['rang'] = $iRang;
				$iPrev = $v['anzahl']	;		
			} else {
				$v['rang'] = '-';
			}
			$aTeilnahmen[] = $v;
		}
		
		return $aTeilnahmen;
	}
	
	public function getDataForTrikotTable($type) {
		$this->db->select('u.name, u.id, COUNT(l.id) AS anzahl');
		$this->db->from('h_leader l');
		$this->db->join('rz_user u', 'u.id=l.user_id');
		$this->db->where('l.type=' . $type);
		$this->db->group_by('l.user_id');
		$this->db->order_by('anzahl', 'DESC');
		$this->db->order_by('u.id', 'ASC');
		$aTemp = $this->db->get()->result_array();
		
		$aTeilnahmen = array();
		$aTeilnahmen[] = $aTemp[0];
		$aTeilnahmen[0]['rang'] = 1;
		$iRang = 1;
		$iPrev = $aTemp[0]['anzahl'];
		unset($aTemp[0]);
		foreach($aTemp as $k=>$v) {
			$iRang++;
			if ($v['id'] == $this->session->userdata('user_id')) {
				$v['session_user'] = 1;
			} else {
				$v['session_user'] = 0;
			}
			if ($v['anzahl'] < $iPrev) {			
				$v['rang'] = $iRang;
				$iPrev = $v['anzahl']	;		
			} else {
				$v['rang'] = '-';
			}
			$aTeilnahmen[] = $v;
		}
		
		return $aTeilnahmen;
	}
}