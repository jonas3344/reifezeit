<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Historie extends Admin_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Historie_model', 'model');
	}
	
	public function parser() {
		$aData = array();
		
		$aData['aRundfahrten'] = $this->model->getRows('h_rundfahrten', 'complete=0');
		
		$this->renderPage('historie_parser', $aData, array(), array());
	}
	
	public function parseData() {
		$iRundfahrt = $this->input->post('rundfahrt');
		$sAction = $this->input->post('action');
		$sData = $this->input->post('data');
		
		$aInsert = array();
		
		if ($sAction == 'teilnehmer') {
			
			$aData = explode("\n", $sData);
			
			foreach ($aData as $k=>$v) {
				
				$aZeile = explode("\t", $v);
				
				$aTemp = array(	'user_id' => $aZeile[1],
								'rundfahrt_id' => $aZeile[0],
								'team_id' => $aZeile[2],
								'rang_gw' => $aZeile[3],
								'rang_punkte' => $aZeile[4],
								'rang_berg' => trim($aZeile[5]));
								
				$aInsert['h_teilnahme'][] = $aTemp;	
			}
		} else if ($sAction == 'team') {
			$aData = explode("\n", $sData);
			
			foreach ($aData as $k=>$v) {
				
				$aZeile = explode("\t", $v);
				
				$aTemp = array(	'team_id' => $aZeile[1],
								'rundfahrt_id' => $aZeile[0],
								'rang' => trim($aZeile[2]));
								
				$aInsert['h_team_teilnahme'][] = $aTemp;	
			}
			
		} else if ($sAction == 'etappen') {
			$aData = explode("\n", $sData);
			
			foreach ($aData as $k=>$v) {
				
				$aZeile = explode("\t", $v);
				
				$aTemp = array(	'etappen_nr' => $aZeile[1],
								'rundfahrt_id' => $aZeile[0],
								'etappenart' => $aZeile[2]);
								
				$iEtappenId = $this->model->saveRecord('h_etappen', $aTemp, -1, 'id');
				
				$aTemp = array(	'etappen_id' => $iEtappenId,
								'team_id' => $aZeile[4]);
				
				$aInsert['h_etsieger_team'][] = $aTemp;
				
				$aTemp = array(	'etappen_id' => $iEtappenId,
								'user_id' => $aZeile[3]);
				
				$aInsert['h_etsieger'][] = $aTemp;
				
				$aTemp = array(	'etappen_id' => $iEtappenId,
								'user_id' => $aZeile[5],
								'type' => 1);
				
				$aInsert['h_leader'][] = $aTemp;
				
				$aTemp = array(	'etappen_id' => $iEtappenId,
								'user_id' => $aZeile[6],
								'type' => 2);
				
				$aInsert['h_leader'][] = $aTemp;
				
				$aTemp = array(	'etappen_id' => $iEtappenId,
								'user_id' => trim($aZeile[7]),
								'type' => 3);
				
				$aInsert['h_leader'][] = $aTemp;
				
			}
			
		} else if ($sAction == 'zusatz') {
			$aData = explode("\n", $sData);
			
			foreach ($aData as $k=>$v) {
				
				$aZeile = explode("\t", $v);

				$iEtappenId = $this->model->getEtappenId($aZeile[0], $aZeile[1]);
				
				if (strlen($aZeile[4]) > 0) {
					$aTemp = array(	'etappen_id' => $iEtappenId,
								'team_id' => $aZeile[4]);
				
					$aInsert['h_etsieger_team'][] = $aTemp;
				}
				
				if (strlen($aZeile[3]) > 0) {
					$aTemp = array(	'etappen_id' => $iEtappenId,
								'user_id' => $aZeile[3]);
				
					$aInsert['h_etsieger'][] = $aTemp;
				}
				
				if (strlen($aZeile[5]) > 0) {
					$aTemp = array(	'etappen_id' => $iEtappenId,
								'user_id' => $aZeile[5],
								'type' => 1);
				
					$aInsert['h_leader'][] = $aTemp;
				}
				
				if (strlen($aZeile[6]) > 0) {
					$aTemp = array(	'etappen_id' => $iEtappenId,
								'user_id' => $aZeile[6],
								'type' => 2);
				
					$aInsert['h_leader'][] = $aTemp;
				}
				
				if (strlen($aZeile[7]) > 0) {
					$aTemp = array(	'etappen_id' => $iEtappenId,
								'user_id' => $aZeile[7],
								'type' => 3);
				
					$aInsert['h_leader'][] = $aTemp;
				}
			}
			
		}
		
		foreach($aInsert as $k=>$v) {
			foreach($v as $aInsertData) {
				$this->model->saveRecord($k, $aInsertData, -1);
			}
			
		}
	}
	
}