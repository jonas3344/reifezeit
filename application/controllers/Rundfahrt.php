<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Rundfahrt extends Frontend_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Rundfahrt_model', 'model');
	}
	
	public function transfermarkt($iSort = 1) {
		$this->load->model('Shortlist_model');
		$aData = array();
		$aData['aShortlists'] = $this->model->getShortlists();
		$aData['bView'] = ($this->config->item('iFreigabeTransfermarkt') == 1) ? true : false;

		
		if ($iSort == 1) {
			$aData['aData'] = $this->model->getFahrerByTeams();
			$this->renderPage('transfermarkt', $aData, array(), array());
		} else if (($iSort == 2) || ($iSort == 3)) {
			$aData['aData'] = $this->model->getFahrerBySort($iSort);
			$this->renderPage('transfermarkt_sort', $aData, array('frontend/transfermarkt.js'), array());
		}	
	}
	
	public function etappen() {
		$aData = array();
		
		$aData['aEtappen'] = $this->model->getEtappen();
		
		$this->renderPage('etappen', $aData, array('ekko-lightbox.js'), array('ekko-lightbox.css', 'portlets.css'));
	}
	
	public function resultate($iEtappe = 0) {
		$aData = array();
		$this->load->helper('time_helper');
		
		if ($iEtappe == 0) {
			$iEtappe = $this->model->getLatestStageResult();
		}
		
		$aData['iEtappe'] = $iEtappe;
		$aData['aEtappen'] = $this->model->getEtappen();
		
		$aData['aResultate'] = $this->model->getResultate($iEtappe);
		
		$this->renderPage('resultate', $aData, array(), array('portlets.css'));
	}
	
	public function getEtappenId() {
		echo $this->model->getEtappenId($this->input->post('etappen_nr'));
	}
}