<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016  Jonas Bay - jonas.bay@bluewin.ch 
 * ----------------------------------------------------------
 *
 * @author		  Jonas Bay
 */
 
class Parser {
	
	protected $CI;
	// 1 = Giro, 2 = Tour/Vuelta
	protected $iParser;
	
	function __construct($iParser) {
		$this->CI =& get_instance();
		$this->iParser = $iParser;
	}
	
	public function parseResult($sResult, $iType) {
		
	}
	
	private function _parseASO() {
		
	}
	
	private function _parseGiro() {
		
	}
}
	