<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Kader extends Frontend_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Kader_model', 'model');
	}
	
	public function tag($iEtappe = 0) {
		$aData = array();
		$this->load->helper('time_helper');
		
		$aData['iEtappe']  = ($iEtappe == 0) ? $this->config->item('iAktuelleEtappe') : $iEtappe;
		$this->load->library('wechsel', $aData['iEtappe']);
		
		$aData['aWechsel'] = $this->wechsel->getChangeByRider($this->session->userdata('user_id'));
		
		$aData['aEtappe'] = $this->model->getEtappe($aData['iEtappe']);
		$aData['aEtappen'] = $this->model->getEtappen();
		
		$aData['aKader'] = $this->model->getKader($aData['iEtappe']);
		
		$aData['aUser'] = $this->model->getUser();
		
		$aData['aShortlists'] = $this->model->getShortlists();
		
		$iTime = _create_timestamp($aData['aEtappe']['etappen_datum'], $aData['aEtappe']['etappen_eingabeschluss']);
		if (time() > $iTime) {
			$aData['bEdit'] = false;
		} else {
			if ($aData['aUser']['out'] == 0) {
				$aData['bEdit'] = true;
			} else {
				$aData['bEdit'] = false;
			}
			
		}
		
		$aData['iDoping'] = $this->model->getDoping($aData['iEtappe']);
		
		$aData['aAbgabe'] = $this->model->getCA('ab', $aData['iEtappe']);
		$aData['aAnnahme'] = $this->model->getCA('an', $aData['iEtappe']);
		
		$iBase = 0;
		switch ($aData['aEtappe']['etappen_klassifizierung']) {
			case 1:
				$iBase += $aData['aUser']['credit_sprint'];
				break;
			case 2:
				$iBase += $aData['aUser']['credit_normal'];
				break;	
			case 3:
				$iBase += $aData['aUser']['credit_zf'];
				break;
			case 4:
				$iBase += $aData['aUser']['credit_berg'];
				break;
			case 5:
				$iBase += $aData['aUser']['credit_bzf'];
				break;
			case 6:
				$iBase += $aData['aUser']['credit_mzf'];
				break;
		}
		
		$aData['iBase'] = $iBase;
			
		$iBase -= count($aData['aAbgabe']);
		$iBase += count($aData['aAnnahme']);
		$iBase += $aData['aKader']['gewonnene_bonuscredits'];
		$iBase += $aData['aKader']['einsatz_creditpool'];
		$iBase += $aData['aKader']['creditmoves'];
		$iBase -= $aData['iDoping'];
		$iBase += $aData['aKader']['ca_malus'];
		
		$aData['iCredits'] = $iBase;
		
		$this->renderPage('kader_tag_neu', $aData, array(), array());
	}
	
	public function kaderuebersicht() {
		$aData = array();
		
		$aData['aKader'] = $this->model->getAlleKader();
		$aData['aDoping'] = $this->model->getDopingAll();
		$aData['aCa'] = $this->model->getAllCa();
		$aData['aUser'] = $this->model->getUser();
				
		$this->renderPage('kaderuebersicht', $aData, array(), array());
	}
	
	public function insertCreditmove() {
		$bAnswer = $this->model->insertCreditmove($this->input->post('etappen_id'));
		if ($bAnswer == true) {
			echo 'ok';
		} else {
			echo 'nok';
		}
	}
	
	public function moveCredit($iEtappe) {
		$aData = array();
		
		$aData['aEtappe'] = $this->model->getEtappe($iEtappe);
		
		$this->renderPage('move_credit', $aData, array(), array());
	}
	
	public function machtwechsel($iEtappe) {
		$aData = array();
		
		$aData['aTeamMembers'] = $this->model->getTeamMembers();
		$aData['iEtappe'] = $iEtappe;
		
		$this->renderPage('machtwechsel', $aData, array(), array());
	}
	
	public function anziehen($iEtappe) {
		$aData = array();
		
		$aData['aTeamMembers'] = $this->model->getTeamMembers();
		$aData['iEtappe'] = $iEtappe;
		
		$this->renderPage('anziehen', $aData, array(), array());
	}
	
	public function insertMachtwechsel($sType) {
		$bAnswer = $this->model->insertMachtwechsel($this->input->post('etappen_id'), $this->input->post('empfaenger'), $sType);
		if ($bAnswer == true) {
			echo 'ok';
		} else {
			echo 'nok';
		}
	}
	
	public function creditAbgabe($iEtappe) {
		$aData = array();
		
		$aData['aEtappe'] = $this->model->getEtappe($iEtappe);
		
		$aData['aTeamMembers'] = $this->model->getTeamMembers();
		
		$aData['bCaCheck'] = $this->model->checkAvailableCa();
		
		$this->renderPage('creditabgabe', $aData, array(), array());
	}
	
	public function eintragFex($iEtappe = 0) {
		$aData = array();
		
		$aData['iEtappe'] = ($iEtappe == 0) ? $this->config->item('iAktuelleEtappe') : $iEtappe;
		
		$aUser = $this->model->getUser();
		
		if ($aUser['rolle_id'] == 3) {
			$aData['aOptions'] = array(2,4);
		} else if ($aUser['rolle_id'] == 6) {
			$aData['aOptions'] = array(1,2,3,4,5);
		}
		
		$aData['aFex'] = $this->model->getEingesetzteFexPuntke();
		
		$aEtappen = $this->model->getEtappen();
		
		$iEtappenNr = $this->model->_getEtappenNr($this->config->item('iAktuelleEtappe'));
		
		foreach($aEtappen as $k=>$v) {
			if ($v['etappen_nr'] < $iEtappenNr) {
				unset($aEtappen[$k]);
			}
		}
		
		$aData['aEtappen'] = $aEtappen;
		$this->renderPage('eintragfex', $aData, array(), array());
	}
	
	public function resetKader() {
		$this->model->resetKader($this->input->post('etappe'));
		echo 'ok';
	}
	
	public function addFex() {
		$this->model->addFex($this->input->post('punkte'), $this->input->post('etappe'));
		echo 'ok';
	}
	
	public function insertCreditabgabe() {
		$bPossible = $this->model->reduceCa($this->session->userdata('user_id'), $this->input->post('empfaenger'));
		if ($bPossible == true) {
			$this->model->saveRecord('creditabgabe', array('abgeber'=> $this->session->userdata('user_id'), 'empfaenger'=>$this->input->post('empfaenger'), 'etappen_id'=> $this->input->post('etappen_id')), -1);
			echo 'ok';
		} else {
			echo 'nok';
		}
		
	}
	
	public function removeBc() {
		$this->model->removeBc($this->input->post('abgabeId'));
	}
	
	public function removeFex() {
		$this->model->removeFex($this->input->post('etappenid'));
	}
	
	public function getFahrerForDropdown($iSort, $iShortlist) {
		echo json_encode($this->model->getFahrerForDropdown($iSort, $iShortlist));
		
	}
	
	public function saveKader() {
/*        $iTime = _create_timestamp($aData['aEtappe']['etappen_datum'], $aData['aEtappe']['etappen_eingabeschluss']);
        if (time() > $iTime) {
            $aData['bEdit'] = false;
        } else {
            if ($aData['aUser']['out'] == 0) {
                $aData['bEdit'] = true;
            } else {
                $aData['bEdit'] = false;
            }

        }*/
		echo $this->model->saveKader($this->input->post('etappe'), $this->input->post('position'), $this->input->post('fahrer_id'));
	}
	
}