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
		return $this->db->get($sTable)->result_array()[0];
	}
	
	public function getRows($sTable, $sWhere, $aSort = array()) {
		if (count($aSort) > 0) {
			$this->db->order_by($aSort['sort_field'], $aSort['sort_order']);
		}
		$this->db->where($sWhere);
		return $this->db->get($sTable)->result_array();
	}
	
	public function saveRecord($sTable, $aData, $iId, $sIdField) {
		if ($iId == -1) {
			$this->db->insert($sTable, $aData);
			$iId = $this->db->insert_id();
		} else {
			$this->db->where($sIdField, $iId);
			$this->db->update($sTable, $aData);
		}
		return $iId;
	}
	
	public function getLatestSort($sTable, $sSortField) {
		
		$this->db->order_by($sSortField, 'DESC');
		$aData = $this->db->get($sTable)->row_array();
		
		return $aData[$sSortField];
	}
}