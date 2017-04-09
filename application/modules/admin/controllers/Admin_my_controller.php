<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Admin_my_controller extends CI_Controller 
{
	
	protected $aHeaderFiles = array('aCssFiles' => array('bootstrap.css', 'bootstrap-theme.css', 'admin.css'),
									'aJsFiles' => array('jquery.min.js', 'bootstrap.js'));
	protected $sView;
	protected $iAktuelleRundfahrt;
	protected $sAktuelleRundfahrt;
	protected $iAktuelleEtappe;
	protected $iFreigabeTransfermarkt;								
	
	public function __construct() 
	{
    	parent::__construct();
		
		$this->load->library(array('auth', 'session'));
		$this->auth->check('backend');
		
		// Load Config
		$this->load->model('MY_model');
		$aConfig = $this->MY_model->getOneRow('config', 'id=1');
		$this->iAktuelleRundfahrt = $aConfig['aktuelle_rundfahrt'];
		$this->iAktuelleEtappe = $aConfig['aktuelle_etappe'];
		$this->iFreigabeTransfermarkt = $aConfig['freigabe_transfermarkt'];
		$aRundfahrt = $this->MY_model->getOneRow('rundfahrt', 'rundfahrt_id=' . $this->iAktuelleRundfahrt);
		$this->sAktuelleRundfahrt = $aRundfahrt['rundfahrt_bezeichnung'];
	}
	
	public function renderPage($sView, $aData, $aJsFiles, $aCssFiles) {
		$this->sView = $sView;
		$this->loadJs($aJsFiles);
		$this->loadCss($aCssFiles);
		
		$aHeader['aCss'] = $this->aHeaderFiles['aCssFiles'];
		$this->load->view('header_view', $aHeader);
		
		$this->load->view('nav_view');
		
		$this->load->view($sView . '_view', $aData);
		
		$aFooter['aJs'] = $this->aHeaderFiles['aJsFiles'];
		$this->load->view('footer_view', $aFooter);
		
	}

	public function loadCss($aCssFiles = array()) {
		// Load additional Css-Files
		if (count($aCssFiles) > 0) {
			foreach($aCssFiles as $k=>$v) {
				$this->aHeaderFiles['aCssFiles'][] = $v;
			}
		}
	}
	
	public function loadJs($aJsFiles = array()) {
		// Load additional JS-Files
		if (count($aJsFiles) > 0) {
			foreach($aJsFiles as $k=>$v) {
				$this->aHeaderFiles['aJsFiles'][] = $v;
			}
		}
		
		// Load js-File for view, if it exists
		$sUrl = FCPATH . 'js/admin/' . $this->sView . '.js';
		if (is_file($sUrl)) {
			$this->aHeaderFiles['aJsFiles'][] = 'admin/' . $this->sView . '.js';
			echo "gugus";
		}
	}
}