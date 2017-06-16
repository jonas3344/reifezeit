<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Shortlist extends Frontend_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Shortlist_model', 'model');
	}
	
	public function index($iShortlist = 0) {
		$aData = array();
		
		$aData['aShortlists'] = $this->model->getShortlists();
		$aData['aSharedShortlists'] = $this->model->getShortlists(true);
		$aData['iShortlist'] = $iShortlist;

		foreach($aData['aShortlists'] as $k=>$v) {
			$aData['iShortlist'] = ($aData['iShortlist'] == 0) ? $v['id'] : $aData['iShortlist'];
			$aData['aShortlists'][$k]['aFahrer'] = $this->model->getFahrerForShortlist($v['id']);
		}
		
		foreach($aData['aSharedShortlists'] as $k=>$v) {
			$aData['aSharedShortlists'][$k]['aFahrer'] = $this->model->getFahrerForShortlist($v['id']);
		}
		$this->renderPage('shortlist', $aData, array(), array());
	}
	
	public function forencode($iShortlist) {
		$this->load->helper('forum_helper');
		$aData['aFahrer'] = $this->model->getFahrerForShortlist($iShortlist);
		
		$aData['aShortlist'] = $this->model->getOneRow('shortlists', 'id=' . $iShortlist);
		$aData['aUser'] = $this->model->getOneRow('rz_user', 'id=' . $aData['aShortlist']['user_id']);
		
		$aData['sOutput'] = forumShortlist($aData['aShortlist'], $aData['aFahrer'], $aData['aUser']);
		
		$this->renderPage('shortlist_forencode', $aData, array('clipboard.min.js'), array());
	}
	
	public function createNewShortlist() {
		$this->model->saveRecord('shortlists', array(	'name' => $this->input->post('name'), 
														'user_id' => $this->session->userdata('user_id'), 
														'share_to_team' => 0, 
														'rundfahrt_id' => $this->config->item('iAktuelleRundfahrt')), -1);
	}
	
	public function addFahrerToShortlist() {
		$bAdd = $this->model->checkFahrerShortlist($this->input->post('fahrer_id'), $this->input->post('shortlist'));
		if ($bAdd == true) {
			echo 'nok';
		} else {
			$this->model->saveRecord('shortlists_fahrer', array('shortlist_id' => $this->input->post('shortlist'), 'fahrer_Id' => $this->input->post('fahrer_id')), -1);
			echo 'ok';
		}
	}
	
	public function removeFahrerFromShortlist() {
		$this->model->removeFahrerFromShortlist($this->input->post('shortlist'), $this->input->post('fahrer'));
	}
	
	public function deleteShortlist() {
		$this->model->deleteRecord('shortlists', $this->input->post('shortlist'), 'id');
	}
	
	public function changeShare() {
		if ($this->input->post('value') == 1) {
			$this->model->changeShare($this->input->post('shortlist'), 0);
			echo '0';
		} else {
			$this->model->changeShare($this->input->post('shortlist'), 1);
			echo '1';
		}
	}
}