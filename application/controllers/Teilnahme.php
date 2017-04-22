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
				$aData['aTeams'] = $this->model->getTable('rz_team');
			}
		}
		
		$this->renderPage('teilnahme', $aData, array(), array());
	}
	
	public function teilnehmer() {
		$aData = array();
		
		$aData['aTeilnehmer'] = $this->model->getTeilnehmerForList();
		
		$this->renderPage('teilnehmer', $aData, array('bootstrap-table.js', 'bootstrap-table-de-DE.js'), array('bootstrap-table.css'));
	}
	
	public function historie() {
		$aData = array();
		
		
		$aData['aHistory'] = $this->model->getHistorie();
		
/*
		echo "<pre>";
		print_r($aData);
		echo "</pre>";
*/
		$this->renderPage('historie', $aData, array(), array());
	}
	
	public function insertAnmeldung() {
		$this->model->insertAnmeldung($this->input->post('rolle'), $this->input->post('team'));
	}
}