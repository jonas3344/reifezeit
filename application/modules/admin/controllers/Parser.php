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
		$this->load->model('Parser_model', 'model');
	}
	
	public function index($iEtappe = 0) {
		$aData = array();
		
		$aData['iEtappe'] = ($iEtappe == 0) ? $this->config->item('iAktuelleEtappe') : $iEtappe;
		
		$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
		
		$this->renderPage('parser', $aData, array(), array());
	}
	
	public function parserResult($iEtappe) {
		$aData = array();
		$this->load->library('parserrz', array('iParser' => $this->input->post('parser'), 'iEtappe' => $iEtappe));
		
		$aAusreisser = array('iAusreisser' => $this->input->post('ausreisser'), 'iFirstHauptfeld' => $this->input->post('firstHauptfeld'));
		
		$aData['sOutput'] = $this->parserrz->parseResult($this->input->post('result'), $this->input->post('type'), $aAusreisser);
		
		$this->renderPage('parser_result', $aData, array(), array());	
	}
	
	public function finishStage() {
		$aData = array();
		
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('erster', 'erster', 'required');

		if ($this->form_validation->run() == FALSE) {
			$aData['iAktuelleEtappe'] = $this->model->_getEtappenNr($this->config->item('iAktuelleEtappe'));
			$aData['iEtappe'] = $this->model->_getEtappenNr($this->config->item('iAktuelleEtappe'))+1;
			$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
			$this->renderPage('finish_stage', $aData, array(), array());
		} else {
			$aData['iNaechsteEtappe'] = $this->input->post('etappen');
			$aEtappe = $this->model->getOneRow('etappen', 'etappen_id=' . $this->input->post('etappe_aktuell'));
			$this->config->set_item('iAktuelleEtappe', $aData['iNaechsteEtappe']);
			
			// Aktuelle Etappe updaten
			$this->model->saveRecord('config', array('aktuelle_etappe' => $aData['iNaechsteEtappe']), 1, 'id');
			
			$aWinner['iFirst'] = $this->input->post('erster');
			$aWinner['iSecond'] = $this->input->post('zweiter');
			$aWinner['iThird'] = $this->input->post('dritter');
			
			$this->model->saveBc($this->config->item('iAktuelleEtappe'), $aEtappe, $aWinner);
			$this->renderPage('finish_stage_success', array(), array(), array());
		}
	}
	
	public function deleteStage($iEtappe = 0) {
		$aData = array();
		
		$aData['iEtappe'] = ($iEtappe == 0) ? $this->model->getLatestStageResult() : $iEtappe;
		
		$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
		
		$this->renderPage('delete_stage', $aData, array(), array());
	}
	
	public function finishRundfahrt() {
		$aData = array();
		
		$this->renderPage('finish_rundfahrt', $aData, array(), array());
	}
	
	public function finishRundfahrtToDb() {
		$aEtappen = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field' => 'etappen_nr', 'sort_order' => 'DESC'));
		
		foreach($aEtappen as $k=>$v) {
			
		}	
		
		$this->load->library('Resultaterz', $aEtappen[0]['etappen_id']);	
		
		$this->model->finishRundfahrt($this->resultaterz->getGesamtWertung(), $this->resultaterz->getGesamtPunkte(), $this->resultaterz->getGesamtBerg());
	}
	
	public function pcsParser() {
		$this->load->helper('html_dom_helper');
		$this->load->helper('file');
		
/*
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, 'https://www.procyclingstats.com/race/la-roue-tourangelle/2018/result');  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
		$data = curl_exec($curl);  
		curl_close($curl);
*/
		
		$data = read_file('temp.txt');
		
		$cCharAt = stripos($data, '<div class="resultCont id180834 show">');
		$data = substr($data, $cCharAt);
		$cCharEnd = stripos($data, '<div class="div300r"');
		$data = substr($data, 0, $cCharEnd);
		
		//var_dump($data);
		
		$html = str_get_html($data);
		
		$aTables = array();
		$rows = ['rang', 'namen', 'team', 'ptsuci', 'ptspcs', 'rueckstand'];
		$i=0;
		$p=0;
		
		foreach($html->find('.basic') as $element) {
			foreach($element->find('tr') as $row) {
				foreach($row->find('td') as $td) {
					$aTables[$p][$rows[$i]] = trim($td->text());
					$i++;
				}
				$i=0;
				$p++;
			}	
		}
		
		$aResult = array();
		
		foreach($aTables as $k=>$v) {
			$aResult[$k]['rang'] = strip_tags($v['rang']);
			$aResult[$k]['namen'] = substr($v['namen'], 0, strpos($v['namen'], '  '));
			$aResult[$k]['vornamen'] = trim(substr($v['namen'], strpos($v['namen'], '  ')));
			
			// Try to find faher in db
			$namen = strip_tags($aResult[$k]['namen']);
			$vornamen = strip_tags($aResult[$k]['vornamen']);
			
			$this->db->select('fahrer_id');
			$this->db->from('fahrer');
			$this->db->where('fahrer_name LIKE', $namen);
			$this->db->where('fahrer_vorname LIKE', $vornamen);
			$fahrer = $this->db->get('')->result_array();
			$aResult[$k]['fahrer_id'] = $fahrer[0]['fahrer_id'];
			
			
			unset($aTables[$k]['ptsuci']);
			unset($aTables[$k]['ptspcs']);
			if ($v['rang'] == 1) {
				$rueckstand = 0;
			} else if (strip_tags($v['rang']) != 'DNF' && strip_tags($v['rang']) != 'DNS') {
				$rueckstand = strip_tags(trim(substr($v['rueckstand'], strpos($v['rueckstand'], '  '))));
				//echo ord($rueckstand[0]);
				$temp = chr(44) . chr(44) . ' ';
				if ($rueckstand == $temp) {
					$rueckstand = 0;
				}
			}
			$aResult[$k]['team'] = $v['team'];
			$aResult[$k]['rueckstand'] = $rueckstand;
		}
		
		echo "<pre>";
		print_r($aResult);
		echo "</pre>";
	}
	
	/*
		AJAX
	*/
	
	public function getFahrerInfo() {		
		$aFahrer = $this->model->getFahrerInfo($this->input->post('startnummer'));
		
		echo $aFahrer['fahrer_vorname'] . ' '  . $aFahrer['fahrer_name'] . ' ' . $aFahrer['team_short'];
	}
	
	public function deleteStageResults() {
		var_dump($this->input->post('etappe'));
		$this->model->deleteStageResults($this->input->post('etappe'));
	}
}