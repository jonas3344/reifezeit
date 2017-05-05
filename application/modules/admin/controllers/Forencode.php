<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Forencode extends Admin_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Forencode_model', 'model');
	}

	public function index() {
		$aData = array();
		$this->load->library('Resultaterz', $this->config->item('iAktuelleEtappe')-1);
		$this->load->helper('forum_helper');
		$this->load->helper('time_helper');
		
		$a = new Resultaterz($this->config->item('iAktuelleEtappe')-1);
		
		$aResultData['etappe'] = $this->model->getOneRow('etappen', 'etappen_id=' . ($this->config->item('iAktuelleEtappe')-1));
		$aResultData['stage_result'] = $a->getTagesWertung();
		$aResultData['stage_team'] = $a->getTeam();
		$aResultData['overall'] = $a->getGesamtWertung();
		$aResultData['overall_points'] = $a->getGesamtPunkte();
		$aResultData['overall_berg'] = $a->getGesamtBerg();
		$aResultData['overall_team'] = $a->getTeamGesamt();
		$aResultData['teilnehmer'] = $this->model->getTeilnehmerForum();
		$aResultData['teams'] = $this->model->getTeamsForum();
		
		if ($aResultData['etappe']['etappen_nr'] > 1) {
			$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->where('etappen_nr', $aResultData['etappe']['etappen_nr']-1);
			$aOldEtappe = $this->db->get('etappen')->row_array();
			
			$resultate_old = new ResultateRZ($aOldEtappe['etappen_id']);
			
			$overall_old = $resultate_old->getGesamtWertung();
			$overall_berg_old = $resultate_old->getGesamtBerg();
			$overall_punkte_old = $resultate_old->getGesamtPunkte();
			
			$aExFuehrung['id_fuhrender_gesamt'] = array_keys($overall_old)[0];
			$aExFuehrung['id_fuhrender_punkte'] = array_keys($overall_punkte_old)[0];
			if (array_values($overall_berg_old)[0]['berg'] > 0) {
				$aExFuehrung['id_fuhrender_berg'] = array_keys($overall_berg_old)[0];
			} else {
				$aExFuehrung['id_fuhrender_berg'] = 999;
			}
			
		}
		
		
		$aData = createResultKaderpost($aResultData, $aExFuehrung);
		
		$this->renderPage('forencode', $aData, array(), array());
	}
	
	public function ruhmeshalle() {
		$aData = array();
		$aCodeData = array();
		
		$this->load->library('Resultaterz', $this->config->item('iAktuelleEtappe'));
		$this->load->helper('forum_helper');
		
		$aEtappen = $this->model->getPastEtappen();
		$aCodeData['aTeilnehmer'] = $this->model->getTeilnehmerForum();
		$aCodeData['aTeams'] = $this->model->getTeamsForum();
		
		foreach($aEtappen as $k=>$v) {
			$a = new Resultaterz($v['etappen_id']);
			
			$stage_result = $a->getTagesWertung();
			$stage_team = $a->getTeam();
			$overall = $a->getGesamtWertung();
			$overall_points = $a->getGesamtPunkte();
			$overall_berg = $a->getGesamtBerg();
			$overall_team = $a->getTeamGesamt();
			
			$aCodeData['aEtappen'][$v['etappen_nr']]['aStage'] = $v;
			$aCodeData['aEtappen'][$v['etappen_nr']]['aFirst'] = array_values($stage_result)[0];
			$aCodeData['aEtappen'][$v['etappen_nr']]['aLeader'] = array_keys($overall)[0];
			$aCodeData['aEtappen'][$v['etappen_nr']]['aTeamStage'] = array_keys($stage_team)[0];
			$aCodeData['aEtappen'][$v['etappen_nr']]['aTeamOverall'] =array_keys($overall_team)[0];
			
			if (array_values($overall_points)[0]['punkte'] > 0) {
				$aCodeData['aEtappen'][$v['etappen_nr']]['aPoints'] = array_keys($overall_points)[0];
			} else {
				$aCodeData['aEtappen'][$v['etappen_nr']]['aPoints'] = "-";
			}
			
			if (array_values($overall_berg)[0]['berg'] > 0) {
				$aCodeData['aEtappen'][$v['etappen_nr']]['aBerg'] = array_keys($overall_berg)[0];
			} else {
				$aCodeData['aEtappen'][$v['etappen_nr']]['aBerg'] = "-";
			}
		}
		
		$aData['sOutput'] = createRuhmeshalle($aCodeData);	
		$this->renderPage('ruhmeshalle', $aData, array(), array());
	}
}