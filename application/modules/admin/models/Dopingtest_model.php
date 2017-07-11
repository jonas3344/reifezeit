<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2017 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
  */
 
class Dopingtest_model extends MY_Model 
{
	public function getDopingtest($iEtappe) {
		$aData = array();
		
		$aData['etappe'] = $this->getOneRow('etappen', 'etappen_id=' . $iEtappe);
		
		$this->db->join('teilnahme t', 't.user_id=u.id');
		$this->db->where('t.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('t.out', 0);
		$this->db->where('t.out_etappen_id<'.$iEtappe);
		$aData['teilnehmer'] = $this->db->get('rz_user u')->result_array();
		
		foreach($aData['teilnehmer'] as $k=>$v) {
			// Team
			$this->db->join('rz_team t', 't.rzteam_id=ut.rz_team_id');
			$this->db->where('ut.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$this->db->where('ut.user_id', $v['id']);
			$aData['teilnehmer'][$k]['team'] = $this->db->get('rz_user_team ut')->row_array();
			
			// Kader
			$this->db->select('fahrer1, fahrer2, fahrer3, fahrer4, fahrer5, einsatz_creditpool, gewonnene_bonuscredits, ca_malus, creditmoves');
			$this->db->where('etappen_id', $iEtappe);
			$this->db->where('user_id', $v['id']);
			$aKader = $this->db->get('kader')->row_array();
			$aData['teilnehmer'][$k]['einsatz_creditpool'] = $aKader['einsatz_creditpool'];
			$aData['teilnehmer'][$k]['gewonnene_bonuscredits'] = $aKader['gewonnene_bonuscredits'];
			$aData['teilnehmer'][$k]['creditmoves'] = $aKader['creditmoves'];
			$aData['teilnehmer'][$k]['ca_malus'] = $aKader['ca_malus'];
			unset($aKader['ca_malus']);
			unset($aKader['creditmoves']);
			unset($aKader['gewonnene_bonuscredits']);
			unset($aKader['einsatz_creditpool']);
			$iUsedCredits = 0;
			$iCountChanges = 0;
			foreach($aKader as $iFahrerid) {
				if ($iFahrerid == 0) {
					$aTemp = array('fahrer_startnummer' => 0, 'fahrer_rundfahrt_credits'=> 0, 'fahrer_vorname'=>'','fahrer_name'=>'');
				} else {
					$aTemp = $this->_getFahrerDetails($iFahrerid);
				}
				
				$iUsedCredits += $aTemp['fahrer_rundfahrt_credits'];
				$aTemp['change'] = $this->_getKaderChange($aData['etappe']['etappen_nr'], $iFahrerid, $v['id']);
				if ($aTemp['change'] == true) {
					$iCountChanges++;
				}
				$aData['teilnehmer'][$k]['kader'][] = $aTemp;
			}
			$aTemp = $this->_getKaderYesterday($aData['etappe']['etappen_nr'], $v['id']);
			$i=0;
			foreach($aTemp as $kk=>$vk) {
				$aData['teilnehmer'][$k]['kaderOld'][$i] = $this->_getFahrerDetails($vk);
				$i++;
			}
			
/*
			echo "<pre>";
			print_r($aData['teilnehmer'][$k]['kaderOld']);
			echo "</pre>";
*/
			// Creditabgaben
			$aData['teilnehmer'][$k]['ca'] = $this->_getCa($iEtappe, $v['id']);
			
			$iCreditBase = $this->_getCreditBase($aData['etappe']['etappen_klassifizierung'], $v['rolle_id']);
			$iCreditBase += $aData['teilnehmer'][$k]['einsatz_creditpool'];
			$iCreditBase += $aData['teilnehmer'][$k]['gewonnene_bonuscredits'];
			$iCreditBase += $aData['teilnehmer'][$k]['ca'];
			$iCreditBase += $aData['teilnehmer'][$k]['ca_malus'];
			$iCreditBase += $aData['teilnehmer'][$k]['creditmoves'];
			$iCreditBase -= $this->_getDoping($v['id'], $iEtappe);
			
			$aData['teilnehmer'][$k]['iChanges'] = $iCountChanges;
			$aData['teilnehmer'][$k]['iCreditBase'] = $iCreditBase;
			$aData['teilnehmer'][$k]['iUsedCredits'] = $iUsedCredits;
			if ($iUsedCredits>$iCreditBase) {
				$aData['teilnehmer'][$k]['doped'] = true;
			} else {
				$aData['teilnehmer'][$k]['doped'] = false;
			}
		}
		
		return $aData;
	}
	
	public function getCaPerStage($iEtappe) {
		$this->db->where('etappen_id', $iEtappe);
		$aAbgabe = $this->db->get('creditabgabe')->result_array();
		
		if (count($aAbgabe) > 0) {
			foreach($aAbgabe as $k=>$v) {
				$this->db->where('id', $v['abgeber']);
				$aAbgabe[$k]['user_abgabe'] = $this->db->get('rz_user')->row_array();
				
				$this->db->where('id', $v['empfaenger']);
				$aAbgabe[$k]['user_empfang'] = $this->db->get('rz_user')->row_array();
				
			}
		}
		
		return $aAbgabe;
	}
	
	public function updateWechselWithData($aData) {
		foreach($aData as $k=>$v) {
			$this->db->select('f.fahrer_name, f.fahrer_vorname, t.team_short');
			$this->db->where('f.fahrer_id', $v['fahrer_id']);
			$this->db->join('team t', 't.team_id=f.fahrer_team_id');
			$aData[$k]['fahrer_infos'] = $this->db->get('fahrer f')->row_array();
		}
		return $aData;
	}
	
	public function getDoper() {
		$this->db->where('d.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->join('rz_user u', 'u.id=d.user_id');
		$this->db->join('etappen e', 'e.etappen_id=d.etappen_id');
		$this->db->order_by('d.etappen_id', 'ASC');
		return $this->db->get('dopingfall d')->result_array();
	}
	
	public function dsqUser($iUser, $iEtappe) {
		$this->model->saveRecord('dopingfall', array('user_id'=>$iUser, 'etappen_id'=>$iEtappe, 'rundfahrt_id'=>$this->config->item('iAktuelleRundfahrt')), -1);
		
		$this->db->where('user_id', $iUser);
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->update('teilnahme', array('out'=>1, 'out_etappen_id'=>$iEtappe));
	}
	
	public function setKaderLastStage($iUser, $iEtappe) {
		$iEtappenNr = $this->_getEtappenNr($iEtappe);
		
		$this->db->select('etappen_id');
		$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('etappen_nr', ($iEtappenNr-1));
		$aEtappe = $this->db->get('etappen')->row_array();
		
		$this->db->where('user_id', $iUser);
		$this->db->where('etappen_id', $aEtappe['etappen_id']);
		$aKaderOld = $this->db->get('kader')->row_array();
		
		$aUpdateData = array(	'fahrer1' => $aKaderOld['fahrer1'],
								'fahrer2' => $aKaderOld['fahrer2'],
								'fahrer3' => $aKaderOld['fahrer3'],
								'fahrer4' => $aKaderOld['fahrer4'],
								'fahrer5' => $aKaderOld['fahrer5']);
		
		$this->db->where('etappen_id', $iEtappe);
		$this->db->where('user_id', $iUser);
		$this->db->update('kader', $aUpdateData);
	}
	
	public function getFahrerForDropdown($iSort) {
		$this->db->select('fr.fahrer_startnummer, f.fahrer_id, f.fahrer_name, f.fahrer_vorname, t.team_short, fr.fahrer_rundfahrt_credits');
		$this->db->join('fahrer f', 'fr.fahrer_id=f.fahrer_id');
		$this->db->join('team t', 't.team_id=f.fahrer_team_id');
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		if ($iSort == 1) {
			$this->db->order_by('fr.fahrer_startnummer', 'ASC');
		} else if ($iSort == 2) {
			$this->db->order_by('fr.fahrer_rundfahrt_credits', 'DESC');
			$this->db->order_by('fr.fahrer_startnummer', 'ASC');
		}
		return $this->db->get('fahrer_rundfahrt fr')->result_array();
	}
	
	public function getSingleKader($iUser, $iEtappe) {
		
		$this->db->where('user_id', $iUser);
		$this->db->where('etappen_id', $iEtappe);
		return $this->db->get('kader')->row_array();
	}
	
	public function freeChangeSubmit($sFahrer, $iFahrer, $iUser, $iEtappe) {
		$this->db->where('user_id', $iUser);
		$this->db->where('etappen_id', $iEtappe);
		$this->db->update('kader', array($sFahrer=>$iFahrer));
	}
	
	private function _getKaderChange($iEtappenNr, $iFahrerid, $iUser) {
		if ($iEtappenNr > 1) {
			$this->db->select('etappen_id');
			$this->db->where('etappen_nr', $iEtappenNr-1);
			$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
			$aEtappe = $this->db->get('etappen')->row_array();
			
			$this->db->select('fahrer1, fahrer2, fahrer3, fahrer4, fahrer5');
			$this->db->where('user_id', $iUser);
			$this->db->where('etappen_id', $aEtappe['etappen_id']);
			$aKaderOld = $this->db->get('kader')->row_array();
			
			if (in_array($iFahrerid, $aKaderOld)) {
				return false;
			} else {
				return true;
			}
		} else if ($iEtappenNr == 1) {
			return true;
		}
	}
	
	private function _getKaderYesterday($iEtappenNr, $iUser) {
		$this->db->select('etappen_id');
		$this->db->where('etappen_nr', $iEtappenNr-1);
		$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aEtappe = $this->db->get('etappen')->row_array();
		
		$this->db->select('fahrer1, fahrer2, fahrer3, fahrer4, fahrer5');
		$this->db->where('user_id', $iUser);
		$this->db->where('etappen_id', $aEtappe['etappen_id']);
		return $this->db->get('kader')->row_array();
	}
	
	private function _getFahrerDetails($iFahrerid) {
		$this->db->join('fahrer_rundfahrt fr', 'fr.fahrer_id=f.fahrer_id');
		$this->db->where('f.fahrer_id', $iFahrerid);
		$this->db->where('fr.rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		return $this->db->get('fahrer f')->row_array();
	}
	
	private function _getCreditBase($iEtappentype, $iRolle) {
		$aRolle = $this->getOneRow('rollen', 'rolle_id=' . $iRolle);
		$iCredit = 0;
		switch($iEtappentype) {
			case 1:
				$iCredit = $aRolle['credit_sprint'];
				break;
			case 2:
				$iCredit = $aRolle['credit_normal'];
				break;
			case 3:
				$iCredit = $aRolle['credit_zf'];
				break;
			case 4:
				$iCredit = $aRolle['credit_berg'];
				break;
			case 5:
				$iCredit = $aRolle['credit_bzf'];
				break;
			case 6:
				$iCredit = $aRolle['credit_mzf'];
				break;
			
		}
		return $iCredit;
	}
	
	private function _getCa($iEtappe, $iUser) {
		$iCa = 0;
		
		$this->db->where('etappen_id', $iEtappe);
		$this->db->where('abgeber', $iUser);
		$aAbgabe = $this->db->get('creditabgabe')->result_array();
		
		$iCa -= count($aAbgabe);
		
		$this->db->where('etappen_id', $iEtappe);
		$this->db->where('empfaenger', $iUser);
		$aAbgabe = $this->db->get('creditabgabe')->result_array();
		
		$iCa += count($aAbgabe);
		
		return $iCa;
	}
	
	private function _getDoping($iUser, $iEtappe) {
		$this->db->where('user_id', $iUser);
		$this->db->where('rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$this->db->where('etappen_id<=' . $iEtappe);
		$aDoping = $this->db->get('dopingfall')->result_array();
		return count($aDoping);
	}
}