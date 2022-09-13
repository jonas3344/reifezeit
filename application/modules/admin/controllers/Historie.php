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
	
	public function submit() {
		$aData = array();
		
		$aData['aRundfahrten'] = $this->model->getRows('h_rundfahrten', 'complete=0');
		
		$this->renderPage('historie_submit', $aData, array(), array());
	}
	
	public function submitData() {
		$iRundfahrtHId = $this->model->getRundfahrt($this->config->item('iAktuelleRundfahrt'));
		
		$aSavedData = array();
		$aSavedData['h_team_teilnahme'] = array();
		
		$aEtappen = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
		
		foreach($aEtappen as $k=>$v) {
			$aSavedData['h_etappen'][$v['etappen_id']] = array('etappen_nr'=>$v['etappen_nr'], 'etappenart'=>$v['etappen_klassifizierung'], 'rundfahrt_id'=>$iRundfahrtHId); 
			
		}
		
		foreach($aSavedData['h_etappen'] as $k=>$v) {
			$aEtappenIds[$v['etappen_nr']] = $this->model->saveRecord('h_etappen', $v, -1);
		}
		
		
		
		$aTeilnehmer = $this->model->getTeilnehmerForHistory($this->config->item('iAktuelleRundfahrt'));
		
		foreach($aTeilnehmer as $k=>$v) {
			$aSavedData['h_teilnahme'][$v['user_id']] = array('user_id' => $v['user_id'], 'team_id'=>$v['rz_team_id'], 'rundfahrt_id'=>$iRundfahrtHId);
			if (!array_key_exists($v['rz_team_id'], $aSavedData['h_team_teilnahme'])) {
				$aSavedData['h_team_teilnahme'][$v['rz_team_id']] = array('team_id' => $v['rz_team_id'], 'rundfahrt_id' => $iRundfahrtHId);
			}
			if ($v['out'] == 1) {
				$aSavedData['h_teilnahme'][$v['user_id']]['rang_gw'] = 'DNF' . $aSavedData['h_etappen'][$v['out_etappen_id']]['etappen_nr'];
				$aSavedData['h_teilnahme'][$v['user_id']]['rang_punkte'] = '-';
				$aSavedData['h_teilnahme'][$v['user_id']]['rang_berg'] = '-';
			} else {
				$aSavedData['h_teilnahme'][$v['user_id']]['rang_gw'] = $v['endklassierung_gesamt'];
				$aSavedData['h_teilnahme'][$v['user_id']]['rang_punkte'] = $v['endklassierung_punkte'];
				$aSavedData['h_teilnahme'][$v['user_id']]['rang_berg'] = $v['endklassierung_berg'];
			}
		}
		
		$this->load->library('Resultaterz', 112);
		
		$aResultData = array();
		
		foreach($aEtappen as $k=>$v) {
			$a = new Resultaterz($v['etappen_id']);
			
			$aResultData['stage_result'] = $a->getTagesWertung();
			$aResultData['stage_team'] = $a->getTeam();
			$aResultData['overall'] = $a->getGesamtWertung();
			$aResultData['overall_points'] = $a->getGesamtPunkte();
			$aResultData['overall_berg'] = $a->getGesamtBerg();
			$aResultData['overall_team'] = $a->getTeamGesamt();
			
			foreach($aResultData['stage_result'] as $kR=>$vR) {
				if ($vR['rang'] == 1) {
					$aSavedData['h_etsieger'][] = array('etappen_id' => $aEtappenIds[$v['etappen_nr']], 'user_id' => $kR);
				} else {
					break;
				}
			}
			
			foreach($aResultData['stage_team'] as $kR=>$vR) {
				if ($vR['zeit'] == 0) {
					$aSavedData['h_etsieger_team'][] = array('etappen_id' => $aEtappenIds[$v['etappen_nr']], 'team_id' => $kR);
				} else {
					break;
				}
			}
			
			foreach($aResultData['overall'] as $kR=>$vR) {
				if ($vR['rang'] == 1) {
					$aSavedData['h_leader'][] = array('etappen_id' => $aEtappenIds[$v['etappen_nr']], 'user_id' => $kR, 'type' => 1);
				} else {
					break;
				}
			}
			
			foreach($aResultData['overall_points'] as $kR=>$vR) {
				if ($vR['rang'] == 1) {
					$aSavedData['h_leader'][] = array('etappen_id' => $aEtappenIds[$v['etappen_nr']], 'user_id' => $kR, 'type' => 2);
				} else {
					break;
				}
			}
			
			foreach($aResultData['overall_berg'] as $kR=>$vR) {
				if ($vR['rang'] == 1) {
					$aSavedData['h_leader'][] = array('etappen_id' => $aEtappenIds[$v['etappen_nr']], 'user_id' => $kR, 'type' => 3);
				} else {
					break;
				}
			}
			
			
			
			if ($v['etappen_nr'] == 18) {
				foreach($aSavedData['h_team_teilnahme'] as $l=>$p) {
					if (!array_key_exists($l, $aResultData['overall_team'])) {
						$aSavedData['h_team_teilnahme'][$l]['rang'] = "DNF";
					} else {
						$aSavedData['h_team_teilnahme'][$l]['rang'] = $aResultData['overall_team'][$l]['rang'];
					}
					
				}
			}

		}
		
		echo "<pre>";
		print_r($aSavedData);
		echo "</pre>";
		
		$sql = '';
		
		unset($aSavedData['h_etappen']);
		
		print_r($aEtappenIds);
		
		print_r($aSavedData);
		
		foreach($aSavedData as $k=>$v) {
			foreach($v as $kV=>$aValues) {
				$this->model->saveRecord($k, $aValues, -1);
			}
			
			
		}
		
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