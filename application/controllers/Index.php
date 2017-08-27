<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Index extends Frontend_my_controller 
{
	public function __construct() {
		parent::__construct();
		//$this->load->model('Parser_model', 'model');
	}
	
	public function index() {
		$aData = array();
		
		$this->renderPage('index', $aData, array(), array('portlets.css'));
	}
}