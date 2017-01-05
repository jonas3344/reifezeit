<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017  Jonas Bay
 * ----------------------------------------------------------
 *
 * @author		  Jonas Bay
 */

  
class Login extends CI_Controller 
{
	public function index()
	{	
		$this->load->helper('form');
		$this->load->library(array('form_validation', 'auth'));
		
		$aRules = array(
					array('field' => 'username',
						  'rules' => 'trim|required'),
					array('field' => 'password',
						  'rules' => 'trim|required')
					);

		$this->form_validation->set_message('required', 'Bitte alle *-Felder ausfüllen');
		$this->form_validation->set_rules($aRules);
		
		if ($this->form_validation->run() === true) {
			if ($this->auth->try_login($this->input->post('username'), $this->input->post('password'), 'backend')) {
				redirect('admin/index');
			} else {
				$aData['sError'] = 'Passwort/Benutzernamen stimmen nicht überein!';
			}
		} else {
			$aData['sError'] = validation_errors();
		}
				
		$this->load->view('login_view', $aData);
	}
	
	
}