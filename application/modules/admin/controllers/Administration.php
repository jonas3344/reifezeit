<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Administration extends Admin_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Administration_model', 'model');
	}
	
	public function teilnehmer() {
		$aData = array();
		
		$aData['aTeilnehmer'] = $this->model->getTeilnehmer($this->iAktuelleRundfahrt);
		
		$this->renderPage('teilnehmer', $aData, array('bootstrap-table.js', 'bootstrap-table-de-DE.js'), array('bootstrap-table.css'));
	}
	
	public function transfermarkt() {
		$aData = array();
		
		$aData['iFreigabeTransfermarkt'] = $this->iFreigabeTransfermarkt;
		
		$aData['aTeams'] = $this->model->getTransfermarkt($this->iAktuelleRundfahrt);
		
		$this->renderPage('transfermarkt', $aData, array('bootstrap-editable.js'), array('bootstrap-editable.css'));
	}
	
	public function addTeam() {
		$aData = array();
		
		$aData['aTeams'] = $this->model->getTeamsForAdding($this->iAktuelleRundfahrt);
		
		$this->renderPage('add_team', $aData, array(), array());
	}
	
	public function addFahrerTransfermarkt($iTeamid) {
		$aData = array();
		
		$aData['aTeam'] = $this->model->getOneRow('team', 'team_id=' . $iTeamid);
		$aData['aFahrer'] = $this->model->getFahrerTeam($iTeamid, $this->iAktuelleRundfahrt);
		
		$this->renderPage('add_fahrer_transfermarkt', $aData, array(), array());
	}
	
	public function openTransfermarkt() {		
		if ($this->iFreigabeTransfermarkt == 1) {
			$aData['freigabe_transfermarkt'] = 0;
		} else {
			$aData['freigabe_transfermarkt'] = 1;
		}
		
		$this->model->saveRecord('config', $aData, 1, 'id');
		echo 'ok';
	}
	
	public function setFahrerCredits() {
		$aData['fahrer_id'] = $this->input->post('pk');
		$aData['rundfahrt_id'] = $this->iAktuelleRundfahrt;
		$aData['fahrer_rundfahrt_credits'] = $this->input->post('value');
		
		$this->model->saveCredits($aData);
	}
	
	public function setFahrerStartnummer() {
		$aData['fahrer_id'] = $this->input->post('pk');
		$aData['rundfahrt_id'] = $this->iAktuelleRundfahrt;
		$aData['fahrer_startnummer'] = $this->input->post('value');
		
		$this->model->saveStartnummer($aData);
	}
	
	public function addTeamToTransfermarkt() {
		$iSort = $this->model->getLatestSort('team_rundfahrt', 'rundfahrt_id=' . $this->iAktuelleRundfahrt, 'start_order');
		
		$aData['rundfahrt_id'] = $this->iAktuelleRundfahrt;
		$aData['team_id'] = $this->input->post('teamid');
		$aData['start_order'] = $iSort+1;
		
		$this->model->saveRecord('team_rundfahrt', $aData, -1, 'team_rundfahrt_id');
		
		echo 'ok';
	}
	
	public function addFahrerToTransfermarkt() {
		$aData = array(	'ausgeschieden' => 0,
						'fahrer_id' => $this->input->post('fahrerid'),
						'rundfahrt_id' => $this->iAktuelleRundfahrt,
						'fahrer_startnummer' => 0,
						'fahrer_rundfahrt_credits' => 0);
		
		$this->model->saveRecord('fahrer_rundfahrt', $aData, -1, 'fahrer_rundfahrt_id');
		echo 'ok';
		
	}
	
	public function removeFahrerFromTransfermarkt() {
		$this->model->removeFahrerFromTransfermarkt($this->input->post('fahrerid'), $this->iAktuelleRundfahrt);
		echo 'ok';
	}
}