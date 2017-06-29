<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Login extends CI_Controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Login_model', 'model');
	}
	
	public function index() {
		$this->load->helper('form_helper');
		$this->load->helper('cookie');
		$this->load->library(array('form_validation', 'auth'));
		
		$aData['aTeilnehmer'] = $this->model->getRows('rz_user', 'active=1', array('sort_field' => 'rzname', 'sort_order' => 'ASC'));
		
		$aData['sUsername'] = get_cookie('reifezeit', TRUE);
		
		$aData['sError'] = '';
		
		$aRules = array(
			array('field' => 'password',
				  'rules' => 'trim|required')
			);

		$this->form_validation->set_message('required', 'Bitte gib ein Passwort ein!');
		$this->form_validation->set_rules($aRules);
		
		if ($this->form_validation->run() === true) {
			if ($this->auth->try_login($this->input->post('username'), $this->input->post('password'))) {
				set_cookie('reifezeit', $this->input->post('username'), '172800', '', '/');
				redirect('index');
			} else {
				$aData['sError'] = 'Passwort/Benutzernamen stimmen nicht überein!';
			}
		} else {
			$aData['sError'] = validation_errors();
		}

		
		$this->load->view('login_view', $aData);
	}
	
	public function new_profil() {
		$this->load->helper('form_helper');
		
		$this->load->view('new_profil_view', $aData);
	}
	
	public function saveNewProfile() {
		$aUser = array(	'name' => $this->input->post('sattlerei_user'),
					'rzname' => $this->input->post('rz_user'),
					'email' => $this->input->post('email'),
					'active' => 1,
					'pass' => md5($this->input->post('password')));
		$this->model->saveRecord('rz_user', $aUser, -1, 'id');
		redirect(base_url() . 'login');
	}
	
	public function logout() {
		$this->session->sess_destroy();
		redirect(base_url());
	}
}