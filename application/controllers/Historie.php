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
		
		$iTemp = $iUser%4;
		$aData['iAvatar'] = ($iTemp == 0) ? 1 : $iTemp;
		
		$this->renderPage('timeline', $aData, array(), array('timeline.css', 'portlets.css'));
		
	}
	
	public function top10() {
		$aData = array();
		
		$this->renderPage('top10', $aData, array(), array('portlets.css', 'top10.css'));
	}
	
}