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
		$this->db->order_by('ht.rundfahrt_id', 'ASC');
		$aHistory = $this->db->get('h_teilnahme ht')->result_array();
		return $aHistory;
	}
	
	public function getTeamHistorie($iUser) {
		$this->db->where('ht.team_id', $iUser);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$aHistory = $this->db->get('h_team_teilnahme ht')->result_array();
		
		foreach($aHistory as $k=>$v) {
			$this->db->select('u.id, u.name');
			$this->db->from('h_teilnahme ht');
			$this->db->where('ht.team_id', $v['team_id']);
			$this->db->where('ht.rundfahrt_id', $v['rundfahrt_id']);
			$this->db->join('rz_user u', 'u.id=ht.user_id');
			$this->db->order_by('u.id', 'ASC');
			$aHistory[$k]['aFahrer'] = $this->db->get()->result_array();
		}
		return $aHistory;
	}
	
	public function getTeams($iUser) {
		$this->db->select('rzteam_name, rzteam_id');
		$this->db->where('ht.user_id', $iUser);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$this->db->join('rz_team rt', 'ht.team_id=rt.rzteam_id');
		$this->db->group_by('ht.team_id');
		$aHistory = $this->db->get('h_teilnahme ht')->result_array();
		return $aHistory;
	}
	
	public function getFahrerForTeam($iTeam) {
		$this->db->from('h_teilnahme ht');
		$this->db->where('ht.team_id', $iTeam);
		$this->db->join('rz_user u', 'u.id=ht.user_id');
		$this->db->group_by('u.id');
		$aFahrer = $this->db->get()->result_array();
		
		foreach($aFahrer as $k=>$v) {
			$this->db->select('t.id');
			$this->db->where('t.user_id', $v['user_id']);
			$this->db->where('t.team_id', $iTeam);
			$aTemp = $this->db->get('h_teilnahme t')->result_array();
			$aFahrer[$k]['anzahl'] = count($aTemp);
		}
		
		$this->array_sort_by_column($aFahrer, 'anzahl', SORT_DESC);
		
		return $aFahrer;
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
	
	public function getTeamGesamtErfolge($iTeam) {
		$aSucess = array();
		$this->db->where('ht.team_id', $iTeam);
		$this->db->where('ht.rang', 1);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$aSuccess['aTeamGesamt'] = $this->db->get('h_team_teilnahme ht')->result_array();
		
		$this->db->where('ht.team_id', $iTeam);
		$this->db->where('ht.rang_gw', 1);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$aSuccess['aEinzelGesamt'] = $this->db->get('h_teilnahme ht')->result_array();
		
		$this->db->where('ht.team_id', $iTeam);
		$this->db->where('ht.rang_punkte', 1);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$aSuccess['aEinzelPunkte'] = $this->db->get('h_teilnahme ht')->result_array();
		
		$this->db->where('ht.team_id', $iTeam);
		$this->db->where('ht.rang_berg', 1);
		$this->db->join('h_rundfahrten hr', 'ht.rundfahrt_id=hr.id');
		$aSuccess['aEinzelBerg'] = $this->db->get('h_teilnahme ht')->result_array();
		
		return $aSuccess;
	}
	
	public function getEtappenSiege($iUser) {
		$this->db->select('e.etappen_nr, r.bezeichnung, r.jahr, ek.klassifizierung_name, ek.klassifizierung_id');
		$this->db->from('h_etsieger es');
		$this->db->where('es.user_id', $iUser);
		$this->db->join('h_etappen e', 'es.etappen_id=e.id');
		$this->db->join('h_rundfahrten r', 'r.id=e.rundfahrt_id');
		$this->db->join('etappen_klassifizierungen ek', 'e.etappenart=ek.klassifizierung_id');
		return $this->db->get()->result_array();
	}
	
	public function getTeamEtappenSiege($iTeam) {
		$this->db->select('e.etappen_nr, r.bezeichnung, r.jahr, ek.klassifizierung_name, ek.klassifizierung_id');
		$this->db->from('h_etsieger_team es');
		$this->db->where('es.team_id', $iTeam);
		$this->db->join('h_etappen e', 'es.etappen_id=e.id');
		$this->db->join('h_rundfahrten r', 'r.id=e.rundfahrt_id');
		$this->db->join('etappen_klassifizierungen ek', 'e.etappenart=ek.klassifizierung_id');
		return $this->db->get()->result_array();
	}
	
	public function getTeamEtappenSiegeEinzeln($iTeam) {
		$aTeilnahmen = $this->getRows('h_team_teilnahme', 'team_id=' . $this->db->escape($iTeam));
		
		$iCount = 0;
		
		$aEts = array();
		
		foreach($aTeilnahmen as $k=>$v) {
			$this->db->select('e.etappen_nr, r.bezeichnung, r.jahr, ek.klassifizierung_name, ek.klassifizierung_id, ru.name, ru.id');
			$this->db->from('h_etsieger es');
			$this->db->where('t.team_id', $iTeam);
			$this->db->where('t.rundfahrt_id', $v['rundfahrt_id']);
			$this->db->where('e.rundfahrt_id', $v['rundfahrt_id']);
			$this->db->join('h_etappen e', 'es.etappen_id=e.id');
			
			$this->db->join('h_teilnahme t', 't.user_id=es.user_id');
			$this->db->join('rz_user ru', 'ru.id=t.user_id');
			$this->db->join('h_rundfahrten r', 'r.id=e.rundfahrt_id');
			$this->db->join('etappen_klassifizierungen ek', 'e.etappenart=ek.klassifizierung_id');
			$this->db->order_by('e.id');
			$aTemp = $this->db->get()->result_array();
			//echo $this->db->last_query();
			foreach($aTemp as $p=>$l) {
				$aEts[] = $l;
			}
		}
		
		return $aEts;
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
	
	public function getRundfahrtData($iRundfahrt) {
		$aData = array();
		// Get Teilnehmer
		$this->db->from('h_teilnahme t');
		$this->db->where('t.rundfahrt_id', $iRundfahrt);
		$this->db->join('rz_user u', 'u.id=t.user_id');
		$this->db->join('rz_team rt', 'rt.rzteam_id=t.team_id', 'LEFT');
		$this->db->order_by('t.rang_gw');
		$aUser = $this->db->get()->result_array();
		
		foreach($aUser as $k=>$v) {
			if ($v['avatar'] == "") {
				$iTemp = $v['user_id']%4;
				$sTemp = ($iTemp == 0) ? 1 : $iTemp;
				$v['avatar'] = 'default/' . $sTemp . '.png';
			}
			
			if (is_numeric($v['rang_gw'])) {
				$v['rang_gw'] = intval($v['rang_gw']);
			} else {
				$v['rang_gw'] = '999' . $v['rang_gw'];
			}
			
			$aData['aUser'][$v['user_id']] = $v;
			if ($v['rang_gw'] == 1) {
				$aData['aFirst'] = $v;
			} else if ($v['rang_gw'] == 2) {
				$aData['aSecond'] = $v;
			} else if ($v['rang_gw'] == 3) {
				$aData['aThird'] = $v;
			}
			if ($v['rang_punkte'] == 1) {
				$aData['aPoints'] = $v;
			}
			if ($v['rang_berg'] == 1) {
				$aData['aBerg'] = $v;
			}
		}
		
		$this->array_sort_by_column($aData['aUser'], 'rang_gw');
		
		$this->db->from('h_team_teilnahme t');
		$this->db->where('t.rundfahrt_id', $iRundfahrt);
		$this->db->join('rz_team rt', 'rt.rzteam_id=t.team_id', 'LEFT');
		$this->db->order_by('t.rang');
		$aData['aTeams'] = $this->db->get()->result_array();
		
		$this->db->from('h_etappen e');
		$this->db->where('e.rundfahrt_id', $iRundfahrt);
		$this->db->join('etappen_klassifizierungen ek', 'ek.klassifizierung_id=e.etappenart');
		$aData['aStages'] = $this->db->get()->result_array();
		
		foreach($aData['aStages'] as $k=>$v) {
			$this->db->from('h_etsieger es');
			$this->db->where('es.etappen_id', $v['id']);
			$this->db->join('rz_user u', 'es.user_id=u.id');
			$aData['aStages'][$k]['aWinner'] = $this->db->get()->row_array();
			
			$this->db->from('h_etsieger_team es');
			$this->db->where('es.etappen_id', $v['id']);
			$this->db->join('rz_team t', 'es.team_id=t.rzteam_id');
			$aData['aStages'][$k]['aWinnerTeam'] = $this->db->get()->row_array();
			
			$this->db->from('h_leader l');
			$this->db->where('l.etappen_id', $v['id']);
			$this->db->where('l.type', 1);
			$this->db->join('rz_user u', 'l.user_id=u.id');
			$aData['aStages'][$k]['aLeader'] = $this->db->get()->row_array();
			
			$this->db->from('h_leader l');
			$this->db->where('l.etappen_id', $v['id']);
			$this->db->where('l.type', 2);
			$this->db->join('rz_user u', 'l.user_id=u.id');
			$aData['aStages'][$k]['aPoints'] = $this->db->get()->row_array();
			
			$this->db->from('h_leader l');
			$this->db->where('l.etappen_id', $v['id']);
			$this->db->where('l.type', 3);
			$this->db->join('rz_user u', 'l.user_id=u.id');
			$aData['aStages'][$k]['aBerg'] = $this->db->get()->row_array();
			
		}
		
		return $aData;
	}
	
	private function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
	    $sort_col = array();
	    foreach ($arr as $key=> $row) {
	        $sort_col[$key] = $row[$col];
	    }
	
	    array_multisort($sort_col, $dir, $arr);
	}

}