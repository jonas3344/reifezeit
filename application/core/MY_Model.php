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
}