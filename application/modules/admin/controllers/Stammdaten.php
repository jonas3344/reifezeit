<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Stammdaten extends Admin_my_controller 
{
	
	public function rundfahrt() {
		
		$aData = array();
		
		$this->renderPage('rundfahrt', $aData);
	}
	
}