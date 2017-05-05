<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Planung_model extends MY_Model 
{
	public function getPlanung() {
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		return $this->db->get('planung')->result_array();
	}
	
	public function getKader() {
		$this->db->select('k.etappen_id, k.fahrer1, k.fahrer2, k.fahrer3, k.fahrer4, k.fahrer5');
		$this->db->join('etappen e', 'e.etappen_id=k.etappen_id');
		$this->db->order_by('e.etappen_nr', 'ASC');
		$this->db->where('e.etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('k.user_id', $this->session->userdata('user_id'));
		return $this->db->get('kader k')->result_array();
	}
	
	public function getPlanungsData($iId) {
		$aTempData = $this->getRows('planung_kader', 'planung_id=' . $iId);
		$aPlanungData['iAktuelleEtappeNr'] = $this->_getEtappenNr($this->config->item('iAktuelleEtappe'));
		$this->load->library('wechselplanung', $this->config->item('iAktuelleEtappe'));
		
		foreach($aTempData as $k=>$v) {
			$aFahrerId = $this->_getFahrerId($v);
			$aPlanungData['aKader'][$k]['aEtappe'] = $this->getOneRow('etappen', 'etappen_id=' . $v['etappen_id']);
			$this->wechselplanung->__construct($v['etappen_id'], $iId);
			
			$aWechsel = $this->wechselplanung->getChangeByRider($this->session->userdata('user_id'));

			foreach($aFahrerId as $kf=>$vf) {
				$this->db->where('f.fahrer_id', $vf);
				$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
				$this->db->join('fahrer_rundfahrt fr', 'fr.fahrer_id=f.fahrer_id');
				$this->db->join('team t', 't.team_id=f.fahrer_team_id');
				$aTemp = $this->db->get('fahrer f')->row_array();
				$aTemp['change'] = (in_array($vf, $aWechsel)) ? 1 : 0;
				$aPlanungData['aKader'][$k]['aFahrer'][] = $aTemp;
			}
			
		}
		
		return $aPlanungData;
	}
	
	public function removePlanung($iId) {
		$this->db->where('planung_id', $iId);
		$this->db->delete('planung_kader');
		
		$this->db->where('id', $iId);
		$this->db->delete('planung');
	}
	
	public function updateKader($sPosition, $iFahrerId, $iPlanungId, $iEtappenNr) {
		$this->db->select('etappen_id');
		$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('etappen_nr', $iEtappenNr);
		$aEtappe = $this->db->get('etappen')->row_array();
		
		$this->db->where('planung_id', $iPlanungId);
		$this->db->where('etappen_id', $aEtappe['etappen_id']);
		$this->db->update('planung_kader', array($sPosition=>$iFahrerId));
	}
	
	public function kaderuebertrag($iEtappeNr, $iPlanungId) {
		
		$this->db->where('etappen_nr', $iEtappeNr);
		$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aAlteEtappe = $this->db->get('etappen')->row_array();
		
		$this->db->where('etappen_nr', $iEtappeNr+1);
		$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aNeueEtappe = $this->db->get('etappen')->row_array();
		
		var_dump($aAlteEtappe);
		var_dump($aNeueEtappe);
		
		$this->db->where('planung_id', $iPlanungId);
		$this->db->where('etappen_id', $aAlteEtappe['etappen_id']);
		
		$aKader = $this->db->get('planung_kader')->row_array();
		
		$this->db->where('planung_id', $iPlanungId);
		$this->db->where('etappen_id', $aNeueEtappe['etappen_id']);
		$this->db->update('planung_kader', array(	'fahrer1' => $aKader['fahrer1'],
													'fahrer2' => $aKader['fahrer2'],
													'fahrer3' => $aKader['fahrer3'],
													'fahrer4' => $aKader['fahrer4'],
													'fahrer5' => $aKader['fahrer5']));
	}
	
	public function removeKaderPlanung($iId) {
		$this->db->where('planung_id', $iId);
		$this->db->delete('planung_kader');
	}
	
	private function _getFahrerId($aArray) {
		return array($aArray['fahrer1'], $aArray['fahrer2'], $aArray['fahrer3'], $aArray['fahrer4'], $aArray['fahrer5']);
	}
}