<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Dopingtest extends Admin_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Dopingtest_model', 'model');
	}
	
	public function index($iEtappe = 0) {
		$aData = array();
		
		if ($iEtappe == 0) {
			$iEtappe = $this->iAktuelleEtappe;
		}
		
		$aData['iAktuelleEtappe'] = $iEtappe;
		$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->iAktuelleRundfahrt);
		$aData['aDopingtest'] = $this->model->getDopingtest($iEtappe);
		
		$this->renderPage('dopingtest', $aData, array(), array());
	}
	

}