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
		
		$aData['aTeilnehmer'] = $this->model->getTeilnehmerForAdmin($this->iAktuelleRundfahrt);
		
		$this->renderPage('teilnehmer', $aData, array('bootstrap-table.js', 'bootstrap-table-de-DE.js'), array('bootstrap-table.css'));
	}
	
	public function kapitaene() {
		
		$aData = $this->model->getKapitaene($this->iAktuelleRundfahrt);
		
/*
		echo '<pre>';
		print_r($aData);
		echo '</pre>';
*/
		
		$this->renderPage('kapitaene', $aData, array(), array());
	}
	
	public function setkapitaen() {
		$kapitaen = $this->input->post('user');
		
		echo json_encode($this->model->savekapitaen($kapitaen));
	}
	
	public function favoriten() {
		$aData = array();
		
		$aData['favoritenGk'] = $this->model->getFavoritenGk();
		$aData['favoritenPunkte'] = $this->model->getFavoritenPunkte();
		$aData['favoritenBerg'] = $this->model->getFavoritenBerg();
		
		$this->renderPage('favoriten', $aData, array(''), array(''));
	}
	
	public function transfermarkt() {
		$aData = array();
		
		$aData['iFreigabeTransfermarkt'] = $this->iFreigabeTransfermarkt;
		
		$aData['aTeams'] = $this->model->getTransfermarkt($this->iAktuelleRundfahrt);
		
		$this->renderPage('transfermarkt', $aData, array('bootstrap-editable.js'), array('bootstrap-editable.css'));
	}
	
	public function edit_teilnehmer($iTeilnehmer) {
		$aData = array();
		
		$aData['aTeilnehmer'] = $this->model->getOneTeilnehmer($iTeilnehmer);
		$aData['aTeams'] = $this->model->getTable('rz_team');
		$aData['aRollen'] = $this->model->getTable('rollen');
		
		var_dump($aData['aTeilnehmer']);
		
		$this->renderPage('edit_teilnehmer', $aData, array(), array());
	}
	
	public function addTeam() {
		$aData = array();
		
		$aData['aTeams'] = $this->model->getTeamsForAdding($this->iAktuelleRundfahrt);
		
		$this->renderPage('add_team', $aData, array(), array());
	}
	
	public function parseTransfermarkt() {
		$aData = array();
		
		$this->renderPage('parse_transfermarkt', $aData, array(), array());
	}
	
	public function addFahrerTransfermarkt($iTeamid) {
		$aData = array();
		
		$aData['aTeam'] = $this->model->getOneRow('team', 'team_id=' . $iTeamid);
		$aData['aFahrer'] = $this->model->getFahrerTeam($iTeamid, $this->iAktuelleRundfahrt);
		
		$this->renderPage('add_fahrer_transfermarkt', $aData, array(), array());
	}
	
	public function final_parse_transfermarkt() {
		$iRundfahrt = $this->config->item('iAktuelleRundfahrt');
		$sParseText = $this->input->post('fahrer');
		
		$aLines = explode("\n", $sParseText);
		$aFahrer = array();		
		
		foreach($aLines as $k=>$v) {
			$aItems = explode("\t", $v);
			$aFahrer[$aItems[0]]['fahrer_id'] = $aItems[0];
			$aFahrer[$aItems[0]]['fahrer_startnummer'] = $aItems[1];
			$aFahrer[$aItems[0]]['fahrer_rundfahrt_credits'] = $aItems[6];			
		}
		
		$this->model->updateTransfermarkt($aFahrer);
		redirect(base_url() . 'admin/administration/transfermarkt');

	}
	
	public function orderTeams() {
		$aData = array();
		
		$aData['aTeams'] = $this->model->getTransfermarkt($this->iAktuelleRundfahrt);
		
		$this->renderPage('order_teams', $aData, array(), array());
	}
	
	public function exportTransfermarkt() {
		$this->load->dbutil();
		$this->load->helper(array('file', 'download'));
		
		$aTransfermarkt = $this->model->getTransfermarktForCSV();
		$new_report = $this->dbutil->csv_from_result($aTransfermarkt);
		
		if (!write_file('transfermarkt.csv',$new_report)) {
			echo "fehler";	
		} else {
			force_download('transfermarkt.csv', NULL);
		}
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
	
	public function setFahrerOut() {
		$this->model->setFahrerOut($this->input->post('fahrerid'));
		echo 'ok';
	}
	
	public function removeFahrerFromTransfermarkt() {
		$this->model->removeFahrerFromTransfermarkt($this->input->post('fahrerid'), $this->iAktuelleRundfahrt);
		echo 'ok';
	}
	
	public function removeTeamFromTransfermarkt() {
		$this->model->removeTeamFromTransfermarkt($this->input->post('teamid'), $this->iAktuelleRundfahrt);
		echo 'ok';
	}
	
	public function saveOrder() {
		$aIds = $this->input->post(NULL, true);
		
		$this->model->saveTeamOrder($aIds['item'], $this->iAktuelleRundfahrt);
	}
	
	public function changeTeamOfTeilnehmer() {
		$this->model->changeTeamOfTeilnehmer($this->input->post('user_id'), $this->input->post('teamid'));
	}
	
	public function changeRolleOfTeilnehmer() {
		$this->model->changeRolleOfTeilnehmer($this->input->post('user_id'), $this->input->post('rolle'));
	}
}