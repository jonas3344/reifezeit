<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Parser extends Admin_my_controller 
{
	public function __construct() {
		parent::__construct();
		//$this->load->model('Dopingtest_model', 'model');
	}
	
	public function index() {
		$aData = array();
		
		$this->renderPage('parser', $aData, array(), array());
	}
	
	public function parserResult() {
		$aData = array();
		$this->load->library('parserrz', 1);
		
		$aData['sOutput'] = $this->parserrz->parseResult($this->input->post('result'), 1);
		
		$this->renderPage('parser_result', $aData, array(), array());	
	}
	
}