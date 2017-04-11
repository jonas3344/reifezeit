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
			// Kader
			$this->db->select('fahrer1, fahrer2, fahrer3, fahrer4, fahrer5, einsatz_creditpool, gewonnene_bonuscredits');
			$this->db->where('etappen_id', $iEtappe);
			$this->db->where('user_id', $v['id']);
			$aKader = $this->db->get('kader')->row_array();
			$aData['teilnehmer'][$k]['einsatz_creditpool'] = $aKader['einsatz_creditpool'];
			$aData['teilnehmer'][$k]['gewonnene_bonuscredits'] = $aKader['gewonnene_bonuscredits'];
			unset($aKader['gewonnene_bonuscredits']);
			unset($aKader['einsatz_creditpool']);
			$iUsedCredits = 0;
			$iCountChanges = 0;
			foreach($aKader as $iFahrerid) {
				$aTemp = $this->_getFahrerDetails($iFahrerid);
				$iUsedCredits += $aTemp['fahrer_rundfahrt_credits'];
				$aTemp['change'] = $this->_getKaderChange($aData['etappe']['etappen_nr'], $iFahrerid, $v['id']);
				if ($aTemp['change'] == true) {
					$iCountChanges++;
				}
				$aData['teilnehmer'][$k]['kader'][] = $aTemp;
			}
			// Creditabgaben
			$aData['teilnehmer'][$k]['ca'] = $this->_getCa($iEtappe, $v['id']);
			
			$iCreditBase = $this->_getCreditBase($aData['etappe']['etappen_klassifizierung'], $v['rolle_id']);
			$iCreditBase += $aData['teilnehmer'][$k]['einsatz_creditpool'];
			$iCreditBase += $aData['teilnehmer'][$k]['gewonnene_bonuscredits'];
			$iCreditBase += $aData['teilnehmer'][$k]['ca'];
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
	
	private function _getKaderChange($iEtappenNr, $iFahrerid, $iUser) {
		$this->db->select('etappen_id');
		$this->db->where('etappen_nr', $iEtappenNr-1);
		$this->db->where('etappen_rundfahrt_id', $this->config->item('iAktuelleRundfahrt'));
		$aEtappe = $this->db->get('etappen')->row_array();
		
		$this->db->select('fahrer1, fahrer2, fahrer3, fahrer4, fahrer5');
		$this->db->where('user_id', $iUser);
		$this->db->where('etappen_id', $aEtappe['etappen_id']);
		$aKaderOld = $this->db->get('kader')->row_array();
		
/*
		echo $iFahrerid;
		echo "<pre>";
		print_r($aKaderOld);
		echo "</pre>";
		echo in_array($iFahrerid, $aKaderOld) . "-<br>";
*/
		if (in_array($iFahrerid, $aKaderOld)) {
			return false;
		} else {
			return true;
		}
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
				$iCredit = $aRolle['credit_mzrf'];
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