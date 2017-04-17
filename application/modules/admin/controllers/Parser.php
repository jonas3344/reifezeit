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
		
		$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'));
		
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

		if ($this->form_validation->run() == FALSE) {
			$aData['iEtappe'] = $this->config->item('iAktuelleEtappe');
		
			$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'));
			$this->renderPage('finish_stage', $aData, array(), array());
		} else {
			$aData['iNaechsteEtappe'] = $this->input->post('etappen');
			$aEtappe = $this->getOneRow('etappen', 'etappen_id=' . $this->config->item('iAktuelleEtappe'));
			$this->config->set_item('iAktuelleEtappe', $aData['iNaechsteEtappe']);
			
			// Aktuelle Etappe updaten
			$this->model->db->saveRecord('config', array('aktuelle_etappe' => $aData['iNaechsteEtappe']), 1, 'id');
			
			$aWinner['iFirst'] = $this->input->post('erster');
			$aWinner['iSecond'] = $this->input->post('zweiter');
			$aWinner['iThird'] = $this->input->post('dritter');
			
			$this->model->saveBc($this->config->item('iAktuelleEtappe', $aEtappe, $aWinner));
			$this->renderPage('finish_stage_success', array(), array(), array());
		}
	}
	
	/*
		AJAX
	*/
	
	public function getFahrerInfo() {		
		$aFahrer = $this->model->getFahrerInfo($this->input->post('startnummer'));
		
		echo $aFahrer['fahrer_vorname'] . ' '  . $aFahrer['fahrer_name'] . ' ' . $aFahrer['team_short'];
	}
	
}