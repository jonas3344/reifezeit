<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Profil extends Frontend_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Profil_model', 'model');
	}
	
	public function index() {
		$aData = array();
		$this->load->helper('form_helper');
		
		$aData['aUser'] = $this->model->getOneRow('rz_user', 'id=' . $this->session->userdata('user_id'));
		
		$this->renderPage('profil', $aData, array('bootstrap-editable.js'), array('bootstrap-editable.css'));
	}
	
	public function setRzName() {
		$this->model->saveRecord('rz_user', array('rzname'=>$this->input->post('value')), $this->session->userdata('user_id'), 'id');
	}
	
	public function changePw() {
		$aData['aUser'] = $this->model->getOneRow('rz_user', 'id=' . $this->session->userdata('user_id'));
		
		if ($aData['aUser']['pass'] == md5($this->input->post('oldPw'))) {
			$this->model->saveRecord('rz_user', array('pass'=>md5($this->input->post('newPw'))), $this->session->userdata('user_id'), 'id');
			echo 'Dein neues Passwort wurde gespeichert!';
		} else {
			echo 'Dein altes Passwort ist nicht korrekt!';
		}
	}
}