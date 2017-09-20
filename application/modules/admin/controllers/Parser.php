<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Parser extends Admin_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Parser_model', 'model');
	}
	
	public function index($iEtappe = 0) {
		$aData = array();
		
		$aData['iEtappe'] = ($iEtappe == 0) ? $this->config->item('iAktuelleEtappe') : $iEtappe;
		
		$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
		
		$this->renderPage('parser', $aData, array(), array());
	}
	
	public function parserResult($iEtappe) {
		$aData = array();
		$this->load->library('parserrz', array('iParser' => $this->input->post('parser'), 'iEtappe' => $iEtappe));
		
		$aAusreisser = array('iAusreisser' => $this->input->post('ausreisser'), 'iFirstHauptfeld' => $this->input->post('firstHauptfeld'));
		
		$aData['sOutput'] = $this->parserrz->parseResult($this->input->post('result'), $this->input->post('type'), $aAusreisser);
		
		$this->renderPage('parser_result', $aData, array(), array());	
	}
	
	public function finishStage() {
		$aData = array();
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('erster', 'erster', 'required');

		if ($this->form_validation->run() == FALSE) {
			$aData['iAktuelleEtappe'] = $this->model->_getEtappenNr($this->config->item('iAktuelleEtappe'));
			$aData['iEtappe'] = $this->model->_getEtappenNr($this->config->item('iAktuelleEtappe'))+1;
			$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
			$this->renderPage('finish_stage', $aData, array(), array());
		} else {
			$aData['iNaechsteEtappe'] = $this->input->post('etappen');
			$aEtappe = $this->model->getOneRow('etappen', 'etappen_id=' . $this->input->post('etappe_aktuell'));
			$this->config->set_item('iAktuelleEtappe', $aData['iNaechsteEtappe']);
			
			// Aktuelle Etappe updaten
			$this->model->saveRecord('config', array('aktuelle_etappe' => $aData['iNaechsteEtappe']), 1, 'id');
			
			$aWinner['iFirst'] = $this->input->post('erster');
			$aWinner['iSecond'] = $this->input->post('zweiter');
			$aWinner['iThird'] = $this->input->post('dritter');
			
			$this->model->saveBc($this->config->item('iAktuelleEtappe'), $aEtappe, $aWinner);
			$this->renderPage('finish_stage_success', array(), array(), array());
		}
	}
	
	public function deleteStage($iEtappe = 0) {
		$aData = array();
		
		$aData['iEtappe'] = ($iEtappe == 0) ? $this->model->getLatestStageResult() : $iEtappe;
		
		$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
		
		$this->renderPage('delete_stage', $aData, array(), array());
	}
	
	public function finishRundfahrt() {
		$aData = array();
		
		$this->renderPage('finish_rundfahrt', $aData, array(), array());
	}
	
	public function finishRundfahrtToDb() {
		$aEtappen = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field' => 'etappen_nr', 'sort_order' => 'DESC'));
		
		foreach($aEtappen as $k=>$v) {
			
		}
		
		
		
		$this->load->library('Resultaterz', $aEtappen[0]['etappen_id']);
		
		
		$this->model->finishRundfahrt($this->resultaterz->getGesamtWertung(), $this->resultaterz->getGesamtPunkte(), $this->resultaterz->getGesamtBerg());
	}
	
	/*
		AJAX
	*/
	
	public function getFahrerInfo() {		
		$aFahrer = $this->model->getFahrerInfo($this->input->post('startnummer'));
		
		echo $aFahrer['fahrer_vorname'] . ' '  . $aFahrer['fahrer_name'] . ' ' . $aFahrer['team_short'];
	}
	
	public function deleteStageResults() {
		var_dump($this->input->post('etappe'));
		$this->model->deleteStageResults($this->input->post('etappe'));
	}
}