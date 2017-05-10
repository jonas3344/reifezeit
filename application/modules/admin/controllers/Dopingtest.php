<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Dopingtest extends Admin_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Dopingtest_model', 'model');
	}
	
	public function index($iEtappe = 0) {
		$aData = array();
		
		if ($iEtappe == 0) {
			$iEtappe = $this->iAktuelleEtappe;
		}
		
		$aData['iAktuelleEtappe'] = $iEtappe;
		$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->iAktuelleRundfahrt, array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
		$aData['aDopingtest'] = $this->model->getDopingtest($iEtappe);
		
		$this->renderPage('dopingtest', $aData, array(), array());
	}
	
	public function forumtabelle($iEtappe) {
		$aData = array();
		$this->load->helper(array('forum_helper', 'time_helper'));
		$this->load->library('Wechsel', $iEtappe);
		
		
		$aKader = $this->model->getDopingtest($iEtappe);
		
		
		
		if($aKader['etappe']['etappen_nr'] >1){
			$this->load->library('Resultaterz', $iEtappe);
			$aGesamt = $this->resultaterz->getGesamtForKader();
			
			foreach($aKader['teilnehmer'] as $k=>$v) {
				$aKader['teilnehmer'][$k]['gw'] = $aGesamt[$v['user_id']]['zeit'];
			}
		} else {
			foreach($aKader['teilnehmer'] as $k=>$v) {
				$aKader['teilnehmer'][$k]['gw'] = 0;
			}
		}
		
		usort($aKader['teilnehmer'], '_cmpGw');
		
		$aCa = $this->model->getCaPerStage($iEtappe);
		
		$aWechsel['ein'] = $this->wechsel->returnSummarizedChanges('ein');
		$aWechsel['aus'] = $this->wechsel->returnSummarizedChanges('aus');
		
		usort($aWechsel['ein'], '_cmpWechsel');
		usort($aWechsel['aus'], '_cmpWechsel');
		
		$aWechsel['ein'] = $this->model->updateWechselWithData($aWechsel['ein']);
		$aWechsel['aus'] = $this->model->updateWechselWithData($aWechsel['aus']);
		
		$aData['sOutput'] = createForumKaderpost($aKader, $aCa, $aWechsel);
		
		
		$this->renderPage('forumtabelle', $aData, array(), array());
	}
	
	public function doper($iEtappe) {
		$aData = array();
		
		$aData['aDoper'] = $this->model->getDoper();
		
		$aData['aTeilnehmer'] = $this->model->getTeilnehmer(true);
		$aData['iEtappe'] = $iEtappe;
				
		$this->renderPage('doper', $aData, array(), array());
	}
	
	public function setKaderLastStage() {
		$this->model->setKaderLastStage($this->input->post('user'), $this->input->post('etappen_id'));
	}
	
	public function freeChange($iUser, $iEtappe) {
		$aData = array();
		
		$aData['aFahrer'] = $this->model->getFahrerForDropdown(1);
		$aData['aUser'] = $this->model->getOneRow('rz_user', 'id=' . $this->db->escape($iUser));
		$aData['aEtappe'] = $this->model->getOneRow('etappen', 'etappen_id=' . $this->db->escape($iEtappe));
		
		$aData['aKader'] = $this->model->getSingleKader($iUser, $iEtappe);
		
		var_dump($aData['aKader']);
		
		$this->renderPage('free_change', $aData, array(), array());
	}
	
	public function freeChangeSubmit() {
		$this->model->freeChangeSubmit($this->input->post('fahrer'), $this->input->post('fahrer_id'), $this->input->post('user'), $this->input->post('etappen_id'));
	}
	
	public function InsertDoper() {
		
		// Check ob das erste Mal
		$iDoper = $this->input->post('doperid');
		
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('user_id', $iDoper);
		
		$aCheck = $this->db->get('dopingfall')->row_array();
		
		if (count($aCheck) > 0) {
			$this->model->dsqUser($this->input->post('doperid'), $this->input->post('etappenid'));
			echo 'out';
		} else {
			$this->model->saveRecord('dopingfall', array('user_id'=>$this->input->post('doperid'), 'etappen_id'=>$this->input->post('etappenid'), 'rundfahrt_id'=>$this->config->item('iAktuelleRundfahrt')), -1);
			echo 'ok';
		}
	}
}