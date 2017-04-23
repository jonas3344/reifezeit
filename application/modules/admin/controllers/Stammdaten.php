<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Stammdaten extends Admin_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Stammdaten_model');
	}
	
	public function rundfahrt() {
		$aData = array();
		
		$aData['aRundfahrten'] = $this->Stammdaten_model->getTable('rundfahrt');
		
		$this->renderPage('rundfahrt', $aData, array('bootstrap-table.js', 'bootstrap-table-de-DE.js'), array('bootstrap-table.css'));
	}
	
	public function edit_rundfahrt($iId) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$aRules = array(
					array('field' => 'rundfahrt_bezeichnung',
						  'rules' => 'trim|required'),
					array('field' => 'rundfahrt_bezeichnung',
						  'rules' => 'trim|required')
					);

		$this->form_validation->set_message('required', 'Bitte alle *-Felder ausfüllen');
		$this->form_validation->set_rules($aRules);
		
		$aData = array();
		
		$aData['iId'] = $iId;
		
		if ($iId != -1) {
			$aData['aRundfahrt'] = $this->Stammdaten_model->getOneRow('rundfahrt', 'rundfahrt_id = ' . $iId);
			$aData['aRegeln'] = $this->Stammdaten_model->getOneRow('regeln', 'rundfahrt_id=' . $iId);
		} else {
			$aData['aRundfahrt']['rundfahrt_bezeichnung'] = "";
			$aData['aRundfahrt']['rundfahrt_jahr'] = "";
		}
		
		if ($this->form_validation->run() === true) {
			$this->Stammdaten_model->saveRecord('rundfahrt', array('rundfahrt_bezeichnung' => $this->input->post('rundfahrt_bezeichnung'), 'rundfahrt_jahr' => $this->input->post('rundfahrt_jahr')), $iId, 'rundfahrt_id');
			$this->Stammdaten_model->saveRecord('regeln', array('regeln' => $this->input->post('regeln')), $iId, 'rundfahrt_id');
			redirect('admin/stammdaten/rundfahrt');
		}
		
		$this->renderPage('rundfahrt_edit', $aData, array('bootstrap3-wysihtml5.all.min.js', 'bootstrap3-wysihtml5.min.js', 'commands.js', 'templates.js'), array('bootstrap3-wysihtml5.min.css'));
	}
	
	public function etappen() {	
		$aData = array();
		
		$aData['sRundfahrt'] = $this->sAktuelleRundfahrt;
		$aData['aEtappen'] = $this->Stammdaten_model->getEtappen($this->iAktuelleRundfahrt);
		
		$this->renderPage('etappen', $aData, array('bootstrap-table.js', 'bootstrap-table-de-DE.js'), array('bootstrap-table.css'));
	}
	
	public function edit_etappe($iId) {
		$aData = array();
		
		var_dump($this->Stammdaten_model->getLatestEtappenNr($this->iAktuelleRundfahrt));
		
		$aData['aEk'] = $this->Stammdaten_model->getEk();
		if ($iId == -1) {
			$aSavedData = array(	'etappen_rundfahrt_id' => $this->iAktuelleRundfahrt,
									'etappen_eingabeschluss' => '00:00',
									'etappen_distanz' => 0,
									'etappen_start_ziel' => 'xxxxx',
									'etappen_datum' => 'dd.mm.yyyy',
									'etappen_klassifizierung' => 1,
									'etappen_profil'=> 'xx/xx/xx.jpg',
									'etappen_nr' => ($this->Stammdaten_model->getLatestEtappenNr($this->iAktuelleRundfahrt)+1));
									
			$aData['iId'] = $this->Stammdaten_model->saveRecord('etappen', $aSavedData, $iId, 'etappen_id');
		} else {
			$aData['aEtappe'] = $this->Stammdaten_model->getOneRow('etappen', 'etappen_id=' . $iId);
			$aData['iId'] = $iId;
		}	
		
		$this->renderPage('edit_etappe', $aData, array('bootstrap-editable.js'), array('bootstrap-editable.css'));	
	}
	
	public function teams() {
		$aData = array();
		
		$aData['aTeams'] = $this->Stammdaten_model->getRows('team', 'team_active=1', array('sort_field'=>'team_name', 'sort_order'=>'ASC'));
		
		$this->renderPage('teams', $aData, array('bootstrap-table.js', 'bootstrap-table-de-DE.js'), array('bootstrap-table.css'));
	}
	
	public function edit_team($iId) {
		$aData = array();
		
		if ($iId == -1) {
			$iId = $this->Stammdaten_model->saveRecord('team', array('team_name'=>'Neues Team', 'team_short'=>'SHR', 'team_active'=>1), -1, 'team_id');
			redirect(base_url() . 'admin/stammdaten/edit_team/' . $iId);
		}
		
		$aData['aTeam'] = $this->Stammdaten_model->getOneRow('team', 'team_id=' . $iId);
		$aData['aRiders'] = $this->Stammdaten_model->getRows('fahrer', 'fahrer_team_id='.$iId, array('sort_field'=>'fahrer_name', 'sort_order'=>'ASC'));
		
		$this->renderPage('edit_team', $aData, array('bootstrap-editable.js'), array('bootstrap-editable.css'));
	}
	
	public function change_team($iId) {
		$aData['aRider'] = $this->Stammdaten_model->getOneRow('fahrer', 'fahrer_id=' . $iId);
		$aData['aTeams'] = $this->_rebuildArray($this->Stammdaten_model->getRows('team', 'team_active=1', array('sort_field'=>'team_name', 'sort_order'=>'ASC')), 'team_id');
		
		$this->renderPage('change_team', $aData, array(), array());
	}
	
	public function fahrer() {
		$aData = array();
		$aData['aRiders'] = $this->Stammdaten_model->getFahrer();
		
		$this->renderPage('fahrer', $aData, array('bootstrap-table.js', 'bootstrap-table-de-DE.js'), array('bootstrap-table.css'));
	}
	
	public function edit_fahrer($iId) {
		$aData = array();
		
		if ($iId == -1) {
			$iId = $this->Stammdaten_model->saveRecord('fahrer', array('fahrer_name'=>'Name', 'fahrer_vorname'=>'vorname', 'fahrer_nation'=>'SUI', 'fahrer_active'=>1, 'fahrer_team_id'=>0), -1, 'fahrer_id');
			redirect(base_url() . 'admin/stammdaten/edit_fahrer/' . $iId);
		}
		$aData['aFahrer'] = $this->Stammdaten_model->getOneRow('fahrer', 'fahrer_id=' . $iId);
		$aData['aTeams'] = $this->_rebuildArray($this->Stammdaten_model->getRows('team', 'team_active=1'), 'team_id');
		array_unshift($aData['aTeams'], array('team_name'=>'Kein Team', 'team_id'=>0));
		
		$this->renderPage('fahrer_edit', $aData, array('bootstrap-editable.js'), array('bootstrap-editable.css'));
	}
	
	public function rollen() {
		$aData = array();
		
		$aData['aRollen'] = $this->Stammdaten_model->getTable('rollen');
		$aData['aHeader'] = array_keys($aData['aRollen'][0]);
		
		$this->renderPage('rollen', $aData, array(), array());
	}
	
	public function rzUser() {
		$aData = array();
		
		$aData['aRzuser'] = $this->Stammdaten_model->getTable('rz_user');
		
		$this->renderPage('rz_user', $aData, array('bootstrap-table.js', 'bootstrap-table-de-DE.js'), array('bootstrap-table.css'));
	}
	
	public function parse_fahrer($iTeam) {
		$aData = array();
		
		$aData['aTeam'] = $this->Stammdaten_model->getOneRow('team', 'team_id=' . $this->db->escape($iTeam));
		
		$this->renderPage('parse_fahrer', $aData, array(), array());
	}
	
	public function parseFahrerResult($iTeam) {
		$aData = array();
		
		$aData['aTeam'] = $this->Stammdaten_model->getOneRow('team', 'team_id=' . $this->db->escape($iTeam));
		
		$sFahrer = $this->input->post('fahrer');
		$aFahrer = preg_split("/[\r\n]+/", $sFahrer, -1, PREG_SPLIT_NO_EMPTY);
				
		$aReturn = array();
		
		foreach($aFahrer as $k=>$v) {
			$aD = explode("\t", $v);

			$mCheck = $this->Stammdaten_model->checkFahrer($aD);
			
			if ($mCheck == false) {
				$aTemp = array('fahrer_vorname' => $aD[2], 'fahrer_name' => $aD[1], 'fahrer_nation' => $aD[3]);
				$aTemp['inDb'] = 0;
				$aReturn[] = $aTemp;
			} else {
				$mCheck['inDb'] = 1;
				$aReturn[] = $mCheck;
			}
		}
		
		$aData['aFahrer'] = $aReturn;
		
		$this->Stammdaten_model->resetTeam($iTeam);
		
		foreach($aReturn as $k=>$v) {
			if ($v['inDb'] == 1) {
				$this->Stammdaten_model->saveRecord('fahrer', array('fahrer_team_id' => $iTeam), $v['fahrer_id'], 'fahrer_id');
			}
		}
		
		$this->renderPage('parse_fahrer_finish', $aData, array(), array());
	}
	
	
	/* AJAX */
	
	public function addFahrerToDb() {
		$this->Stammdaten_model->saveRecord('fahrer', array('fahrer_team_id' => $this->input->post('fahrer_team_id'), 'fahrer_vorname' => trim($this->input->post('fahrer_vorname')), 'fahrer_name' => trim($this->input->post('fahrer_name')),'fahrer_nation' => trim($this->input->post('fahrer_nation')), 'fahrer_active' => 1), -1);
		echo 'ok';
	}
	
	public function deleteTeam() {
		$this->Stammdaten_model->saveRecord('team', array('team_active'=>0), $this->input->post('teamid'), 'team_id');
		echo 'ok';
		
	}
	
	public function setTeamname($iId) {
		$this->Stammdaten_model->saveRecord('team', array('team_name'=>$this->input->post('value')), $iId, 'team_id');
	}
	
	public function setTeamShort($iId) {
		$this->Stammdaten_model->saveRecord('team', array('team_short'=>$this->input->post('value')), $iId, 'team_id');
	}
	
	public function deleteRider() {
		$this->Stammdaten_model->saveRecord('fahrer', array('fahrer_team_id'=>0, 'fahrer_active'=>0), $this->input->post('fahrerid'), 'fahrer_id');
		echo 'ok';
	}
	
	public function changeTeam() {
		$this->Stammdaten_model->saveRecord('fahrer', array('fahrer_team_id'=>$this->input->post('teamid')), $this->input->post('fahrerid'), 'fahrer_id');
		echo 'ok';
	}
	
	public function setFahrerDetails() {
		switch($this->input->post('name')) {
			case 'fahrername':
				$aData = array('fahrer_name'=>$this->input->post('value'));
				break;
			case 'fahrervorname':
				$aData = array('fahrer_vorname'=>$this->input->post('value'));
				break;
			case 'fahrernation':
				$aData = array('fahrer_nation'=>$this->input->post('value'));
				break;
		}
		$this->Stammdaten_model->saveRecord('fahrer', $aData, $this->input->post('pk'), 'fahrer_id');
	}
	
	public function setEtappenDetails() {
		$aData = array();
		switch($this->input->post('name')) {
			case 'etappen_nr':
				$aData = array('etappen_nr' => $this->input->post('value'));
				break;
			case 'etappen_datum':
				$aData = array('etappen_datum' => $this->input->post('value'));
				break;
			case 'etappen_start_ziel':
				$aData = array('etappen_start_ziel' => $this->input->post('value'));
				break;
			case 'etappen_distanz':
				$aData = array('etappen_distanz' => $this->input->post('value'));
				break;
			case 'etappen_profil':
				$aData = array('etappen_profil' => $this->input->post('value'));
				break;
			case 'etappen_eingabeschluss':
				$aData = array('etappen_eingabeschluss' => $this->input->post('value'));
				break;
			case 'etappen_klassifizierung':
				$aData = array('etappen_klassifizierung' => $this->input->post('value'));
				break;
		}
		$this->Stammdaten_model->saveRecord('etappen', $aData, $this->input->post('pk'), 'etappen_id');
	}
	
	private function _rebuildArray($aArray, $sId) {
		$aNewArray = array();
		foreach($aArray as $k=>$v) {
			$aNewArray[$v[$sId]] = $v;
		}
		return $aNewArray;
	}
}