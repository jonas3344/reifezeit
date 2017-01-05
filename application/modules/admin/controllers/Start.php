<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Start extends Admin_my_controller 
{
	
	public function index() {
		
		$aData = array();
		
		$this->renderPage('start', $aData);
	}
	
}