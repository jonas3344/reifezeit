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
}