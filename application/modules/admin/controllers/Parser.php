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
	
	public function parserPcs($iEtappe = 0) {
		$aData = array();
		
		$aData['iEtappe'] = ($iEtappe == 0) ? $this->config->item('iAktuelleEtappe') : $iEtappe;
		
		$aData['aEtappen'] = $this->model->getRows('etappen', 'etappen_rundfahrt_id=' . $this->config->item('iAktuelleRundfahrt'), array('sort_field'=>'etappen_nr', 'sort_order'=>'ASC'));
		
		$this->renderPage('parserPcs', $aData, array(), array());
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
	
	public function pcsParser($iEtappe) {
		$url = $this->input->post('url');
		
		$aAusreisser = array('iAusreisser' => $this->input->post('ausreisser'), 'iFirstHauptfeld' => $this->input->post('firstHauptfeld'));
		
		$this->load->library('parserrz', array('iParser' => 3, 'iEtappe' => $iEtappe));
		
		$this->load->helper('html_dom_helper');
		$this->load->helper('file');
		
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
		$data = curl_exec($curl);  
		curl_close($curl);
		
		//$data = read_file('temp.txt');
		
		$cCharAt = stripos($data, '<div class="resultCont');
		$data = substr($data, $cCharAt);
		$cCharEnd = stripos($data, '<div class="res-right"');
		$data = substr($data, 0, $cCharEnd);

		//write_file('temp2.txt', $data);
		
		//var_dump($data);
		
		$html = str_get_html($data);
		
		$etappentyp = $this->model->getEtappenTyp($this->config->item('iAktuelleEtappe'));
		
		if ($etappentyp['etappen_klassifizierung'] == 3 || $etappentyp['etappen_klassifizierung'] == 5) {
			$rowsTime = ['rang', 'gc-pos', 'gc-rueck', 'startnummer', 'namen', 'age', 'team', 'ptsuci', 'ptspcs', 'avg', 'rueckstand'];
		} else {
			$rowsTime = ['rang', 'gc-pos', 'gc-rueck', 'startnummer', 'namen', 'age', 'team', 'ptsuci', 'ptspcs', 'rueckstand'];
		}
		
		$aTables = array();
		
/*
		var_dump($this->model->getEtappenNr($this->config->item('iAktuelleEtappe')));
		echo $this->db->last_query();
*/
		$etappen_nr = $this->model->getEtappenNr($this->config->item('iAktuelleEtappe'));
		if ($etappen_nr['etappen_nr'] == 1) {
			$rowsMtn = ['rang', 'startnummer', 'namen', 'age', 'team', 'berg'];
			$rowsPts = ['rang', 'startnummer', 'namen', 'age', 'team', 'points'];
			$rowsGk = ['rang', 'startnummer', 'namen', 'age', 'team', 'ptsuci', 'rueckstand'];
		} else {
			$rowsGk = ['rang', 'prv', 'updown', 'startnummer', 'namen', 'age', 'team', 'ptsuci', 'rueckstand'];
			$rowsPts = ['rang', 'prv', 'updown', 'startnummer', 'namen', 'age', 'team', 'points'];
			$rowsMtn = ['rang', 'prv', 'updown', 'startnummer', 'namen', 'age', 'team', 'berg'];
		}
/*
		//$rowsTime = ['rang', 'startnummer', 'namen', 'age', 'team', 'ptsuci', 'ptspcs', 'gc-pos', 'gc-rueck', 'rueckstand'];
		// Für TT.
		//$rowsTime = ['rang', 'startnummer', 'namen', 'age', 'team', 'ptsuci', 'ptspcs', 'avg', 'gc-pos', 'gc-rueck', 'rueckstand'];
		//$rowsTime = ['rang', 'startnummer', 'namen', 'age', 'team', 'ptsuci', 'ptspcs', 'avg', 'gc-pos', 'gc-rueck', 'rueckstand'];
		$rowsGk = ['rang', 'prv', 'updown', 'startnummer', 'namen', 'age', 'team', 'ptsuci', 'gc-pos', 'gc-rueck', 'rueckstand', 'timewonlost'];
		//$rowsGk = ['rang', 'startnummer', 'namen', 'age', 'team', 'ptsuci', 'gc-pos', 'gc-time', 'rueckstand', 'timewonlost'];
		$rowsPts = ['rang', 'prv', 'updown', 'startnummer', 'namen', 'age', 'team', 'points'];
		//$rowsPts = ['rang', 'startnummer', 'namen', 'age', 'team', 'points'];
		$rowsMtn = ['rang', 'prv', 'updown', 'startnummer', 'namen', 'age', 'team', 'berg'];
*/
		//$rowsMtn = ['rang', 'startnummer', 'namen', 'age', 'team', 'berg'];
		$c = 0;
		$i=0;
		$p=0;
		foreach($html->find('.resultCont') as $resultCont) {
			$resultContMarkup = $resultCont->makeup();
			$pos1 = strpos($resultContMarkup, 'data-id');
			$pos2 = strpos($resultContMarkup, '"', $pos1);
			$pos3 = strpos($resultContMarkup, '"', $pos2+1);
			$dataId = substr($resultContMarkup, $pos2+1, ($pos3-$pos2-1));
			if (is_numeric($dataId)) {
				foreach($resultCont->find('.basic') as $element) {
					foreach($element->find('tr') as $row) {
						foreach($row->find('td') as $td) {
							//var_dump($td->text());
							if ($c == 0) {
								//echo $td->text() . '-';
								$aTables['tag'][$p][$rowsTime[$i]] = trim($td->text());
							} else if ($c==1) {
								//echo $td->text() . '-';
								$aTables['gk'][$p][$rowsGk[$i]] = trim($td->text());
							}else if ($c==2) {
								//echo $td->text() . '-';
								$aTables['punkte'][$p][$rowsPts[$i]] = trim($td->text());
							} else if ($c == 4) {
								$aTables['berg'][$p][$rowsMtn[$i]] = trim($td->text());
							}
							
	// 						echo $c . '-' . $p . '<br>';
							$i++;
						}
	// 					echo '<br>';
						$i=0;
						$p++;
					}	
				}
				$p=0;
				$c++;
			}


		}
		
/*
		echo '<pre>';
		print_r($aTables);
		echo '</pre>';
*/
		
		$aResult = array();
		$i=0;
		foreach($aTables as $kC => $vC) {
			foreach($vC as $k=>$v) {
				$aResult[$kC][$k]['rang'] = strip_tags($v['rang']);
				$aResult[$kC][$k]['namen'] = substr($v['namen'], 0, strpos($v['namen'], '  '));
				$aResult[$kC][$k]['vornamen'] = trim(substr($v['namen'], strpos($v['namen'], '  ')));
				
				// Try to find faher in db
				$namen = strip_tags($aResult[$kC][$k]['namen']);
				$vornamen = trim(strip_tags($aResult[$kC][$k]['vornamen']));
				
				$this->db->select('f.fahrer_id');
				$this->db->from('fahrer f');
				$this->db->join('fahrer_rundfahrt fr', 'fr.fahrer_id=f.fahrer_id');
				$this->db->where('fr.fahrer_startnummer', $v['startnummer']);
				$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
				$fahrer = $this->db->get('')->row_array();
				$aResult[$kC][$k]['fahrer_id'] = $fahrer['fahrer_id'];
				if (count($fahrer) == 0) {
/*
					if ($namen == 'Majka') {
						$aResult[$kC][$k]['fahrer_id'] = 553;
					} else if ($namen == 'Cort') {
						$aResult[$kC][$k]['fahrer_id'] = 892;
					} else if ($namen == 'Rojas') {
						$aResult[$kC][$k]['fahrer_id'] = 923;
					} else {
*/
						echo 'Fahrer not found: ';
						echo '<pre>';
						var_dump($v);
						echo '</pre>';
						echo '<br>';
						echo $this->db->last_query() . '<br>';
// 					}
					
				}
				
				
				unset($aTables[$kC][$k]['ptsuci']);
				unset($aTables[$kC][$k]['ptspcs']);
				if ($kC == 'tag') {
					if ($v['rang'] == 1) {
						$rueckstand = 0;
					} else if (strip_tags($v['rang']) != 'DNF' && strip_tags($v['rang']) != 'DNS') {
						$rueckstand = strip_tags(trim(substr($v['rueckstand'], strpos($v['rueckstand'], ' '))));
						$temp = chr(44) . chr(44) . ' ';
						if ($rueckstand == $temp) {
							$rueckstand = 0;
						}
					}
					$aResult[$kC][$k]['rueckstand'] = $rueckstand;
				} else if ($kC == 'gk') {
					if ($v['rang'] == 1) {
						$rueckstand = 0;
					} else if (strip_tags($v['rang']) != 'DNF' && strip_tags($v['rang']) != 'DNS') {
						$rueckstand = strip_tags(trim(substr($v['rueckstand'], strpos($v['rueckstand'], ' '))));
						$temp = chr(44) . chr(44) . ' ';
						if ($rueckstand == $temp) {
							$rueckstand = 0;
						}
					}
					$aResult[$kC][$k]['rueckstand'] = $rueckstand;	
				} else if ($kC == 'punkte') {
					$aResult[$kC][$k]['punkte'] = $v['points'];
				} else if ($kC == 'berg') {
					$aResult[$kC][$k]['berg'] = $v['berg'];
				}

				$aResult[$kC][$k]['team'] = $v['team'];	
			}
			$i++;
		}
/*
		echo "<pre>";
		print_r($aResult);
		echo "</pre>";
*/
		
		$result = array('tag' => $aResult['tag'], 'gk' => $aResult['gk']);


		$aData['sOutputTime'] = $this->parserrz->parseResult($result, 1, $aAusreisser);
		$aData['sOutputPunkte'] = $this->parserrz->parseResult($aResult['punkte'], 2, $aAusreisser);

		$aData['sOutputBerg'] = $this->parserrz->parseResult($aResult['berg'], 3, $aAusreisser);
	}
	
	public function parseTest() {
		$this->db->select('fahrer_id, rueckstand');
		$this->db->from('temp_gk');
		$this->db->where('etappe', 136);
		$dataToday = $this->db->get()->result_array();
		
		$this->db->select('fahrer_id, rueckstand');
		$this->db->from('temp_gk');
		$this->db->where('etappe', 135);
		$dataYesterday = $this->db->get()->result_array();
		
		foreach($dataToday as $k=>$v) {
			foreach($dataYesterday as $kY=>$vY) {
				if ($v['fahrer_id'] == $vY['fahrer_id']) {
					$dataToday[$k]['rueckstand_gestern'] = $vY['rueckstand'];
					$dataToday[$k]['resultat'] = $v['rueckstand'] - $vY['rueckstand'];
				}
			} 
		}
		
		usort($dataToday, function($a, $b) {
			//echo $a['punkte'] . ' und ' . $b['punkte'] . ' ergibt ' . ($a['punkte'] - $b['punkte']) . '<br>';
		    return $a['resultat'] > $b['resultat'] ? 1 : -1;
		});
		
		$siegerzeit = $dataToday[0]['resultat'];
		
		foreach($dataToday as $k=>$v) {
			$dataToday[$k]['resultat'] = $dataToday[$k]['resultat'] + ($siegerzeit*-1);
		}
		
		echo '<pre>';
		print_r($dataToday);
		echo '</pre>';
		
		$i=1;
		foreach($dataToday as $k=>$v) {
			$data = array('etappen_id' => 136, 'fahrer_id' => $v['fahrer_id'], 'rueckstand' => $v['resultat'], 'rueckstandOhneBS' => $v['resultat'], 'rang'=>$i);
			$this->db->insert('resultate', $data);
			$i++;
		}
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