<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Administration_model extends MY_Model 
{
	public function getTeilnehmerForAdmin($iRundfahrt) {
		
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
	
	public function getTransfermarktForCSV() {
		$this->db->select('f.fahrer_id, fr.fahrer_startnummer, f.fahrer_vorname, f.fahrer_name, f.fahrer_nation, t.team_short, fr.fahrer_rundfahrt_credits');
		$this->db->join('fahrer f', 'f.fahrer_id=fr.fahrer_id');
		$this->db->join('team t', 'f.fahrer_team_id=t.team_id');
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->order_by('fr.fahrer_startnummer', 'ASC');
		$this->db->order_by('f.fahrer_team_id', 'ASC');
		return $this->db->get('fahrer_rundfahrt fr');
	}
	
	public function getOneTeilnehmer($iTeilnehmer) {
		
		if ($this->checkValueinDb('rz_user_team', array('rundfahrt_id' => $this->config->item('iAktuelleRundfahrt'), 'user_id'=>$iTeilnehmer))) {
			$this->db->where('ut.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		}
		$this->db->select('t.user_id, ut.rz_team_id, t.rolle_id, u.name, u.rzname');
		$this->db->where('t.user_id', $iTeilnehmer);
		$this->db->where('t.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->join('rz_user u', 'u.id=t.user_id');
		$this->db->join('rollen r', 'r.rolle_id=t.rolle_id');
		$this->db->join('rz_user_team ut', 'ut.user_id=t.user_id', 'LEFT');
		$this->db->join('rz_team rt', 'rt.rzteam_id=ut.rz_team_id', 'LEFT');
		$aTemp = $this->db->get('teilnahme t')->row_array();
		return $aTemp;
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
	
	public function saveStartnummer($aData) {
		$this->db->where('rundfahrt_id', $aData['rundfahrt_id']);
		$this->db->where('fahrer_id', $aData['fahrer_id']);
		$this->db->update('fahrer_rundfahrt', array('fahrer_startnummer'=>$aData['fahrer_startnummer']));
	}
	
	public function getTeamsForAdding($iRundfahrt) {
		$aTeams = $this->getRows('team', 'team_active=1');
		
		$aTeamsRundfahrt = $this->getRows('team_rundfahrt', 'rundfahrt_id=' . $iRundfahrt);
		
		foreach($aTeams as $kT=>$aTeam) {
			foreach($aTeamsRundfahrt as $kR=>$aTeamIn) {
				if ($aTeam['team_id'] == $aTeamIn['team_id']) {
					unset($aTeams[$kT]);
				}
			}
		}
		return $aTeams;
	}
	
	public function getFahrerTeam($iTeamid, $iRundfahrt) {
		$this->db->where('fahrer_active', 1);
		$this->db->where('fahrer_team_id', $iTeamid);
		$this->db->order_by('fahrer_name', 'ASC');
		
		$aAlleFahrer = $this->db->get('fahrer')->result_array();
		
		$this->db->where('f.fahrer_team_id', $iTeamid);
		$this->db->where('fr.rundfahrt_id', $iRundfahrt);
		$this->db->join('fahrer f', 'f.fahrer_id=fr.fahrer_id');
		$aFahrerBereitsDrin = $this->db->get('fahrer_rundfahrt fr')->result_array();
		
		foreach($aAlleFahrer as $k=>$aFahrer) {
			foreach($aFahrerBereitsDrin as $aSelectedFahrer) {
				if ($aFahrer['fahrer_id'] == $aSelectedFahrer['fahrer_id']) {
					unset($aAlleFahrer[$k]);
				}
			}
		}
		
		return $aAlleFahrer;
	}
	
	public function removeFahrerFromTransfermarkt($iFahrerid, $iRundfahrt) {
		$this->db->where('fahrer_id', $iFahrerid);
		$this->db->where('rundfahrt_id', $iRundfahrt);
		$this->db->delete('fahrer_rundfahrt');
		
	}
	
	public function removeTeamFromTransfermarkt($iTeamid, $iRundfahrt) {
		$this->db->select('f.fahrer_id');
		$this->db->join('fahrer_rundfahrt fr', 'fr.fahrer_id=f.fahrer_id');
		$this->db->where('f.fahrer_team_id', $iTeamid);
		$this->db->where('fr.rundfahrt_id', $iRundfahrt);
		$aFahrer = $this->db->get('fahrer f')->result_array();
		
		foreach($aFahrer as $k=>$v) {
			$this->removeFahrerFromTransfermarkt($v['fahrer_id'], $iRundfahrt);
		}
		
		$this->db->where('team_id', $iTeamid);
		$this->db->where('rundfahrt_id', $iRundfahrt);
		$this->db->delete('team_rundfahrt');
	}
	
	public function saveTeamOrder($aIds, $iRundfahrt) {
		if (count($aIds) > 0) {
			foreach($aIds as $k=>$v) {
				$this->db->where('team_id', $v);
				$this->db->where('rundfahrt_id', $iRundfahrt);
				$this->db->update('team_rundfahrt', array('start_order' => ($k+1)));
			}
		}
	}
	
	public function setFahrerOut($iFahrer) {
		$this->db->where('fahrer_id', $iFahrer);
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->update('fahrer_rundfahrt', array('ausgeschieden'=>1));
	}
	
	public function changeTeamOfTeilnehmer($iUser, $iTeam) {
		$this->db->where('user_id', $iUser);
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->update('rz_user_team', array('rz_team_id'=>$iTeam));
	}
	
	public function changeRolleOfTeilnehmer($iUser, $iRolle) {
		// Get Infos on Rolle
		$aRolle = $this->getOneRow('rollen', 'rolle_id=' . $iRolle);
		
		$this->db->where('user_id', $iUser);
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->update('teilnahme', array('rolle_id' => $iRolle, 'creditabgabe' => $aRolle['creditabgabe'], 'creditempfang'=>$aRolle['creditannahme']));
		
		echo $this->db->last_query();
	}
	
	public function updateTransfermarkt($aFahrer) {
		foreach($aFahrer as $k=>$v) {
			$this->db->where('fahrer_id', $v['fahrer_id']);
			$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->update('fahrer_rundfahrt', $v);
		}
	}
	
	public function getKapitaene($iRundfahrt) {
		$this->db->select('id, name, rzname');
		$this->db->from('rz_user');
		$this->db->order_by('name ASC');
		$alleUser = $this->db->get()->result_array();
		
		$this->db->select('user_id');
		$this->db->from('rundfahrt_kapitaen');
		$this->db->where('rundfahrt_id', $iRundfahrt);
		$kapitaene = $this->db->get()->result_array();
		
		$kapitaenearray = array();
		
		foreach($alleUser as $k=>$v) {
			foreach($kapitaene as $kK=>$vK) {
				if ($v['id'] == $vK['user_id']) {
					$kapitaenearray[] = $v;
					unset($alleUser[$k]);
				}
				
			}
			
		}
		
		return array('user' => $alleUser, 'kapitaene' => $kapitaenearray);
	}
	
	public function savekapitaen($user) {
		$this->db->insert('rundfahrt_kapitaen', array('rundfahrt_id' => $this->config->item('iAktuelleRundfahrt'), 'user_id' => $user));
		
		$this->db->select('name');
		$this->db->from('rz_user');
		$this->db->where('id', $user);
		return $this->db->get()->row_array();
	}
	
	public function getFavoritenGk() {
		$rundfahrt = substr($this->config->item('sAktuelleRundfahrt'), 0, -5);
		$jahr = substr($this->config->item('sAktuelleRundfahrt'), -4);
		
		$this->db->select('id');
		$this->db->from('h_rundfahrten');
		$this->db->where('complete', 1);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(9);
		$checkRundfahrten = $this->db->get()->result_array();
		
		$points = array(1=>array(1=>10, 2=>8, 3=>6, 4=>4, 5=>3, 6=>2, 7=>1), 
						2=>array(1=>10, 2=>8, 3=>6, 4=>4, 5=>3, 6=>2, 7=>1), 
						3=>array(1=>15, 2=>12, 3=>11, 4=>9, 5=>8, 6=>6, 7=>4, 8=>3, 9=>2, 10=>1), 
						4=>array(1=>7, 2=>5, 3=>3, 4=>2, 5=>1), 
						5=>array(1=>7, 2=>5, 3=>3, 4=>2, 5=>1), 
						6=>array(1=>10, 2=>8, 3=>6, 4=>4, 5=>3, 6=>2, 7=>1), 
						7=>array(1=>7, 2=>5, 3=>3, 4=>2, 5=>1), 
						8=>array(1=>5, 2=>3, 3=>1), 
						9=>array(1=>5, 2=>3, 3=>1));
						
		$factor = array(1=>1, 2=>0.9, 3=>0, 4=>0, 5=>0.3, 6=>0.75, 7=>0, 8=>0.6);
		
		$aResults = array();
		$i=1;
		foreach($checkRundfahrten as $k=>$v) {
			$this->db->select('user_id, rang_gw');
			$this->db->from('h_teilnahme');
			$this->db->where('rundfahrt_id', $v['id']);
			$aResults[$i] = $this->db->get()->result_array();
			$i++;
		}
		
		$allePunkte = array();
		$i=1;
		foreach($aResults as $k=>$v) {
			foreach($v as $kR=>$vV) {
				$allePunkte[$vV['user_id']]['punkte'] += $points[$i][$vV['rang_gw']];
			}
			$i++;
		}
		
		asort($allePunkte);
		
		$orderByPoints = array();
		foreach($allePunkte as $k=>$v) {
			$aUser = $this->_getUserData($k);
			if (count($aUser) > 0) {
				
				$punkte = $v['punkte'] * $factor[$aUser['rolle_id']];
// 				echo $punkte . ' = ' . $v['punkte'] . ' * ' . $factor[$aUser['rolle_id']] . '<br>';
				$aUser['punkte'] = $punkte;
				$orderByPoints[] = $aUser;
			}
			
		}
		
		usort($orderByPoints, function($a, $b) {
			//echo $a['punkte'] . ' und ' . $b['punkte'] . ' ergibt ' . ($a['punkte'] - $b['punkte']) . '<br>';
		    return $a['punkte'] > $b['punkte'] ? 1 : -1;
		});
		
		foreach($orderByPoints as $k=>$v) {
			if ($v['punkte'] == 0) {
				unset($orderByPoints[$k]);
			}
		}
		$orderByPoints = array_reverse($orderByPoints, true);
				
		
		return $orderByPoints;
	}
	
	public function getFavoritenPunkte() {
		$rundfahrt = substr($this->config->item('sAktuelleRundfahrt'), 0, -5);
		$jahr = substr($this->config->item('sAktuelleRundfahrt'), -4);
		
		$this->db->select('id');
		$this->db->from('h_rundfahrten');
		$this->db->where('complete', 1);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(9);
		$checkRundfahrten = $this->db->get()->result_array();
		
		$points = array(1=>array(1=>7, 2=>5, 3=>3, 4=>2, 5=>1), 
						2=>array(1=>7, 2=>5, 3=>3, 4=>2, 5=>1), 
						3=>array(1=>10, 2=>8, 3=>6, 4=>4, 5=>3, 6=>2, 7=>1), 
						4=>array(1=>7, 2=>5, 3=>3, 4=>2, 5=>1), 
						5=>array(1=>5, 2=>3, 3=>2, 4=>1), 
						6=>array(1=>5, 2=>3, 3=>2, 4=>1), 
						7=>array(1=>5, 2=>3, 3=>2, 4=>1), 
						8=>array(1=>3, 2=>2, 3=>1), 
						9=>array(1=>3, 2=>2, 3=>1));
		// 1 = Kapitän, 2 = Rundfahrer, 3 = Etappenjäger, 4 = Helfer, 5 = Neo, 6 = Bergfex, 7 = Sprinter, 8 = ZF		
		$factor = array(1=>0.7, 2=>0.7, 3=>0.9, 4=>0.9, 5=>0, 6=>0, 7=>1, 8=>0.8);
		
		$aResults = array();
		$i=1;
		foreach($checkRundfahrten as $k=>$v) {
			$this->db->select('user_id, rang_punkte');
			$this->db->from('h_teilnahme');
			$this->db->where('rundfahrt_id', $v['id']);
			$aResults[$i] = $this->db->get()->result_array();
			$i++;
		}
		
		$allePunkte = array();
		$i=1;
		foreach($aResults as $k=>$v) {
			foreach($v as $kR=>$vV) {
				$allePunkte[$vV['user_id']]['punkte'] += $points[$i][$vV['rang_punkte']];
			}
			$i++;
		}
		
		$etappensieger = $this->_getEtSieger(1);
		
		foreach($etappensieger as $k=>$v) {
			$allePunkte[$v]['punkte'] = $allePunkte[$v]['punkte'] + 1;
		}
		
		
		asort($allePunkte);
		
		$orderByPoints = array();
		foreach($allePunkte as $k=>$v) {
			$aUser = $this->_getUserData($k);
			if (count($aUser) > 0) {
				
				$punkte = $v['punkte'] * $factor[$aUser['rolle_id']];
// 				echo $punkte . ' = ' . $v['punkte'] . ' * ' . $factor[$aUser['rolle_id']] . '<br>';
				$aUser['punkte'] = $punkte;
				$orderByPoints[] = $aUser;
			}
			
		}
		
		usort($orderByPoints, function($a, $b) {
		    return $a['punkte'] > $b['punkte'] ? 1 : -1;
		});
		foreach($orderByPoints as $k=>$v) {
			if ($v['punkte'] == 0) {
				unset($orderByPoints[$k]);
			}
		}
		$orderByPoints = array_reverse($orderByPoints, true);
				
		
		return $orderByPoints;	
	}
	
	public function getFavoritenBerg() {
		$rundfahrt = substr($this->config->item('sAktuelleRundfahrt'), 0, -5);
		$jahr = substr($this->config->item('sAktuelleRundfahrt'), -4);
		
		$this->db->select('id');
		$this->db->from('h_rundfahrten');
		$this->db->where('complete', 1);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(9);
		$checkRundfahrten = $this->db->get()->result_array();
		
		$points = array(1=>array(1=>7, 2=>5, 3=>3, 4=>2, 5=>1), 
						2=>array(1=>7, 2=>5, 3=>3, 4=>2, 5=>1), 
						3=>array(1=>10, 2=>8, 3=>6, 4=>4, 5=>3, 6=>2, 7=>1), 
						4=>array(1=>7, 2=>5, 3=>3, 4=>2, 5=>1), 
						5=>array(1=>5, 2=>3, 3=>2, 4=>1), 
						6=>array(1=>5, 2=>3, 3=>2, 4=>1), 
						7=>array(1=>5, 2=>3, 3=>2, 4=>1), 
						8=>array(1=>3, 2=>2, 3=>1), 
						9=>array(1=>3, 2=>2, 3=>1));
		// 1 = Kapitän, 2 = Rundfahrer, 3 = Etappenjäger, 4 = Helfer, 5 = Neo, 6 = Bergfex, 7 = Sprinter, 8 = ZF		
		$factor = array(1=>0.8, 2=>0.9, 3=>0, 4=>0.9, 5=>0, 6=>1, 7=>0, 8=>0.8);
		
		$aResults = array();
		$i=1;
		foreach($checkRundfahrten as $k=>$v) {
			$this->db->select('user_id, rang_berg');
			$this->db->from('h_teilnahme');
			$this->db->where('rundfahrt_id', $v['id']);
			$aResults[$i] = $this->db->get()->result_array();
			$i++;
		}
		
		$allePunkte = array();
		$i=1;
		foreach($aResults as $k=>$v) {
			foreach($v as $kR=>$vV) {
				$allePunkte[$vV['user_id']]['punkte'] += $points[$i][$vV['rang_berg']];
			}
			$i++;
		}
		
		$etappensieger = $this->_getEtSieger(4);
		
		foreach($etappensieger as $k=>$v) {
			$allePunkte[$v]['punkte'] = $allePunkte[$v]['punkte'] + 1;
		}
		
		asort($allePunkte);
		
		$orderByPoints = array();
		foreach($allePunkte as $k=>$v) {
			$aUser = $this->_getUserData($k);
			if (count($aUser) > 0) {
				
				$punkte = $v['punkte'] * $factor[$aUser['rolle_id']];
// 				echo $punkte . ' = ' . $v['punkte'] . ' * ' . $factor[$aUser['rolle_id']] . '<br>';
				$aUser['punkte'] = $punkte;
				$orderByPoints[] = $aUser;
			}
			
		}
		
		usort($orderByPoints, function($a, $b) {
		    return $a['punkte'] > $b['punkte'] ? 1 : -1;
		});
		
		foreach($orderByPoints as $k=>$v) {
			if ($v['punkte'] == 0) {
				unset($orderByPoints[$k]);
			}
		}
		$orderByPoints = array_reverse($orderByPoints, true);
		
		return $orderByPoints;
	}
	
	private function _getEtSieger($type) {
		$this->db->select('id');
		$this->db->from('h_rundfahrten');
		$this->db->where('complete', 1);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(6);
		$checkRundfahrten = $this->db->get()->result_array();
		
		$alldata = array();
		foreach($checkRundfahrten as $k=>$v) {
			$this->db->select('es.user_id');
			$this->db->from('h_etsieger es');
			$this->db->join('h_etappen h', 'h.id=es.etappen_id');
			$this->db->where('h.rundfahrt_id', $v['id']);
			$this->db->where('h.etappenart', $type);
			$alldata[] = $this->db->get()->result_array();
		}
		
		$allwins = Array();
		foreach($alldata as $k=>$v) {
			foreach($v as $kV=>$vV) {
				$allwins[] = $vV['user_id'];
			}
		}
		return $allwins;
		
	}
	
	
	private function _getUserData($user_id) {
		$this->db->select('u.rzname, u.id, t.rolle_id, r.rolle_bezeichnung');
		$this->db->from('rz_user u');
		$this->db->join('teilnahme t', 't.user_id=u.id');
		$this->db->join('rollen r', 't.rolle_id=r.rolle_id');
		$this->db->where('u.id', $user_id);
		$this->db->where('t.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$result = $this->db->get();
		return $result->row_array();
	}
}