<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016  Jonas Bay - jonas.bay@bluewin.ch 
 * ----------------------------------------------------------
 *
 * @author		  Jonas Bay
 */
 
class Auth {
	
	protected $CI;
	protected $sLoginFrontend = 'rz/login';
	protected $sLoginBackend = 'admin/login';
  
	public function __construct() {
		$this->CI =& get_instance();
	}
	
	public function check($sSection = 'frontend') {
		$iStatus = $this->CI->session->userdata('login_' . $sSection);
		if ($sSection == 'frontend') {
			if ($iStatus != '1') {
				redirect($this->sLoginFrontend);
			}
		} else if ($sSection == 'backend') {
			if ($iStatus != '1') {
				redirect($this->sLoginBackend);
			}	
		}
	}
	
	public function try_login($sUsername, $sPassword, $sSection = 'frontend') {
		if ($sSection == 'frontend') {
			
		} else if ($sSection == 'backend') {
			if ($sUsername == 'spielleiter' && $sPassword == 'reifezeit') {
				$this->CI->session->set_userdata(array('login_backend' => '1'));
				return true;
			}
			else {
				return false;
			}
		}
	}
	
}