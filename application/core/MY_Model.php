<?php  !defined('BASEPATH') and exit('No direct script access allowed');
	
	
class MY_Model extends CI_Model {
	
	public function getTable($sTable, $aSort = array()) {
		if (count($aSort) > 0) {
			$this->db->order_by($aSort['sort_field'], $aSort['sort_order']);
		}
		return $this->db->get($sTable)->result_array();
	}
	
	public function getOneRow($sTable, $sWhere) {
		$this->db->where($sWhere);
		return $this->db->get($sTable)->row_array();
	}
	
	public function getRows($sTable, $sWhere, $aSort = array()) {
		if (count($aSort) > 0) {
			$this->db->order_by($aSort['sort_field'], $aSort['sort_order']);
		}
		$this->db->where($sWhere);
		return $this->db->get($sTable)->result_array();
	}
	
	public function saveRecord($sTable, $aData, $iId, $sIdField = 'id') {
		if ($iId == -1) {
			$this->db->insert($sTable, $aData);
			$iId = $this->db->insert_id();
		} else {
			$this->db->where($sIdField, $iId);
			$this->db->update($sTable, $aData);
		}
		return $iId;
	}
	
	public function deleteRecord($sTable, $iId, $sIdField = 'id') {
		$this->db->where($sIdField, $iId);
		$this->db->delete($sTable);
	}
	
	public function getLatestSort($sTable, $sWhere, $sSortField) {
		$this->db->where($sWhere);
		$this->db->order_by($sSortField, 'DESC');
		$aData = $this->db->get($sTable)->row_array();
		
		return $aData[$sSortField];
	}
	
	public function getTeilnehmer($out = true, $sSortField = 'rzname') {
		$this->db->join('rz_user u', 'u.id=t.user_id');
		$this->db->where('t.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		if ($out == true) {
			$this->db->where('t.out', 0);
		}
		$this->db->order_by($sSortField, 'ASC');
		return $this->db->get('teilnahme t')->result_array();
		
	}
	
	public function _getEtappenNr($iEtappe) {
		$aEtappe = $this->getOneRow('etappen', 'etappen_id=' . $iEtappe);
		return $aEtappe['etappen_nr'];
	}
	
	public function getTeilnahme($iUser) {
		$this->db->where('user_id', $iUser);
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		return $this->db->get('teilnahme')->row_array();
	}
	
	public function getCreditBase($iEtappe) {
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aUser = $this->db->get('teilnahme')->row_array();
		$aRolle = $this->getOneRow('rollen', 'rolle_id=' . $aUser['rolle_id']);
		
		$aEtappe = $this->getOneRow('etappen', 'etappen_id=' . $iEtappe);
		
		$iCredit = 0;
		switch($aEtappe['etappen_klassifizierung']) {
			case 1:
				$iCredit = $aRolle['credit_sprint'];
				break;
			case 2:
				$iCredit = $aRolle['credit_normal'];
				break;
			case 3:
				$iCredit = $aRolle['credit_zf'];
				break;
			case 4:
				$iCredit = $aRolle['credit_berg'];
				break;
			case 5:
				$iCredit = $aRolle['credit_bzf'];
				break;
			case 6:
				$iCredit = $aRolle['credit_mzf'];
				break;
			
		}
		return $iCredit;
	}
	
	public function getShortlists($bShared = false) {
		if ($bShared == false) {
			$this->db->select('id, name, share_to_team');
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$aShortlists = $this->db->get('shortlists')->result_array();
		} else {
			$this->db->select('rz_team_id');
			$this->db->from('rz_user_team');
			$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$aTeam = $this->db->get()->row_array();
			
			$this->db->select('user_id');
			$this->db->from('rz_user_team');
			$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->where('rz_team_id', $aTeam['rz_team_id']);
			$aTeamMembers = $this->db->get()->result_array();
			
			$aShortlists = array();
			
			foreach($aTeamMembers as $k=>$v) {
				if ($v['user_id'] != $this->session->userdata('user_id')) {
					$this->db->select('s.id, s.name, s.share_to_team, u.name as username, u.rzname as rzname');
					$this->db->where('s.user_id', $v['user_id']);
					$this->db->where('s.share_to_team', 1);
					$this->db->join('rz_user u', 's.user_id=u.id');
					$aTemp = $this->db->get('shortlists s')->result_array();
					foreach($aTemp as $kT=>$vT) {
						$aShortlists[] = $vT;
					}
				}
			}
		}
		return $aShortlists;
	}
	
	public function checkValueinDb($sTable, $aValues) {
		foreach($aValues as $k=>$v) {
			$this->db->where($k, $v);
		}
		$oQuery = $this->db->get($sTable);
		if ($oQuery->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
		
	}
}