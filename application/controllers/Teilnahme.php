<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Teilnahme extends Frontend_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Teilnahme_model', 'model');
	}
	
	public function anmelden() {
		$aData = array();
		$this->load->library('form_validation');
		
		if ($this->form_validation->run() === true) {
			
		} else {
			$aData['mTeilnahme'] = $this->model->checkTeilnahme();
			if ($aData['mTeilnahme'] == false) {
				$aData['aRollen'] = $this->model->getRollen();
			}
		}
		
		$this->renderPage('teilnahme', $aData, array(), array());
	}
	
	public function teilnehmer() {
		$aData = array();
		
		$aData['aTeilnehmer'] = $this->model->getTeilnehmerForList();
		
		$this->renderPage('teilnehmer', $aData, array('bootstrap-table.js', 'bootstrap-table-de-DE.js'), array('bootstrap-table.css'));
	}
}