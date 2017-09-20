<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Historie extends Frontend_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Historie_model', 'model');
	}
	
	public function index($iUser = 0) {
		$aData = array();
		
		if ($iUser == 0) {
			$iUser = $this->session->userdata('user_id');
		}
		
		$aData['aHistory'] = $this->model->getHistorie($iUser);
		$aData['aUser'] = $this->model->getOneRow('rz_user', 'id=' . $this->db->escape($iUser));
		
		$aData['aTeams'] = $this->model->getTeams($iUser);
		$aData['aSuccess'] = $this->model->getGesamtErfolge($iUser);
		$aData['aEs'] = $this->model->getEtappenSiege($iUser);
		$aData['aLeader'] = $this->model->getLeadertrikots($iUser, 2);
		$aData['iUser'] = $iUser;
		
		$this->renderPage('historie', $aData, array(), array('portlets.css'));
	}
	
	public function timeline($iUser = 0) {
		if ($iUser == 0) {
			$iUser = $this->session->userdata('user_id');
		}
		
		$aData['aTimelineData'] = $this->model->collectTimelineData($iUser);
		
		$aData['aHistory'] = $this->model->getHistorie($iUser);
		$aData['aUser'] = $this->model->getOneRow('rz_user', 'id=' . $this->db->escape($iUser));
		
		$aData['aTeams'] = $this->model->getTeams($iUser);
		$aData['aSuccess'] = $this->model->getGesamtErfolge($iUser);
		
		$aData['aEs'] = $this->model->getEtappenSiege($iUser);
		$aData['aLeader'] = $this->model->getLeadertrikots($iUser);
		$aData['iUser'] = $iUser;
		
		if ($aData['aUser']['avatar'] == "") {
			$iTemp = $iUser%4;
			$sTemp = ($iTemp == 0) ? 1 : $iTemp;
			$aData['sAvatar'] = 'default/' . $sTemp . '.png';
		} else {
			$aData['sAvatar'] = $aData['aUser']['avatar'];
		}
		
		$this->renderPage('timeline', $aData, array(), array('timeline.css', 'portlets.css'));
		
	}
	
	public function top10() {
		$aData = array();
		
		$aData['aTeilnahmen'] = $this->model->getTeilnahmen();
		$aData['aEts'] = $this->model->getEtappenSiegeForList();
		
		$this->renderPage('top10', $aData, array(), array('portlets.css', 'top10.css'));
	}
	
	public function teams($iTeam) {
		$aData = array();
		
		$aData['aTeam'] = $this->model->getOneRow('rz_team', 'rzteam_id=' . $iTeam);
		$aData['aHistory'] = $this->model->getTeamHistorie($iTeam);
		$aData['aSuccess'] = $this->model->getTeamGesamtErfolge($iTeam);
		$aData['aEs'] = $this->model->getTeamEtappenSiege($iTeam);
		$aData['aFahrer'] = $this->model->getFahrerForTeam($iTeam);
		$aData['aEsEinzeln'] = $this->model->getTeamEtappenSiegeEinzeln($iTeam);
		
		//print_r($aData['aSuccess'])
		
		
		$this->renderPage('teams', $aData, array(), array('portlets.css', 'timeline.css'));
	}
	
	public function teamFull($iTeam) {
		$aData = array();
		
		$aData['aTeam'] = $this->model->getOneRow('rz_team', 'rzteam_id=' . $iTeam);
		$aData['aSuccess'] = $this->model->getTeamGesamtErfolge($iTeam);
		$aData['aHistory'] = $this->model->getTeamHistorie($iTeam);
		$aData['aEs'] = $this->model->getTeamEtappenSiege($iTeam);
		$aData['aEsEinzeln'] = $this->model->getTeamEtappenSiegeEinzeln($iTeam);
		$aData['iTeam'] = $iTeam;
		
		$this->renderPage('teams_full', $aData, array(), array('portlets.css'));
	}
	
	public function teams_list() {
		$aData = array();
		
		
		$this->renderPage('teams_list', $aData, array(), array('portlets.css', 'teams.css'));
		
	}
	
	public function rundfahrten_list() {
		$aData['aRundfahrten'] = $this->model->getRows('h_rundfahrten', 'complete=1', array('sort_field'=>'id', 'sort_order'=>'ASC'));
		$this->renderPage('rundfahrten_list', $aData, array(), array('portlets.css'));
		
	}
	
	public function rundfahrt($iRundfahrt) {
		$aData = array();
		
		$aData['aRundfahrt'] = $this->model->getOneRow('h_rundfahrten', 'id=' . $this->db->escape($iRundfahrt));
		$aData['aData'] = $this->model->getRundfahrtData($iRundfahrt);
		
		$this->renderPage('rundfahrt', $aData, array(), array('portlets.css'));
	}
	
	public function uploadAvatar() {
		$config['upload_path']          = './img/avatars/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']			= $this->session->userdata('user_id');
        
        $this->load->library('upload', $config);
		
		$aUser = $this->model->getOneRow('rz_user', 'id=' . $this->db->escape($this->session->userdata('user_id')));
		if ($aUser['avatar'] != '') {
			unlink(FCPATH . 'img/avatars/' . $aUser['avatar']);
		}
        
        if ( ! $this->upload->do_upload('avatar'))
        {
            $error = array('error' => $this->upload->display_errors());
            print_r($error);
        }
        else
        {
            $data = $this->upload->data();
			$this->model->saveRecord('rz_user', array('avatar'=>$data['file_name']), $this->session->userdata('user_id'), 'id');
            
        }
        redirect(base_url() . 'historie/timeline/' . $this->session->userdata('user_id'));
	}
	
	public function getDataForGwTable() {
		echo json_encode($this->model->getDataForGwTable($this->input->post('type')));
	}
	
	public function getDataForTrikotTable() {
		echo json_encode($this->model->getDataForTrikotTable($this->input->post('type')));
	}
	
}