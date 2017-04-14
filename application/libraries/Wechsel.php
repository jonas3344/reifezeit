<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016  Jonas Bay - jonas.bay@bluewin.ch 
 * ----------------------------------------------------------
 *
 * @author		  Jonas Bay
 */
 
class Wechsel {
	protected $CI;
	protected $aEtappe;
	protected $aPastStage;
	protected $aAlleKaderAktuell;
	protected $aAlleKaderGestern;
	protected $aAlleWechsel;
	protected $aAlleAuswechsel;
	
	public function __construct($iEtappe) {
		$this->CI =& get_instance();
		
		$this->CI->db->where('etappen_id', $iEtappe);
		$this->aEtappe = $this->CI->db->get('etappen')->row_array();
		
		if ($this->aEtappe['etappen_nr'] > 1) {
			$this->CI->db->where('etappen_rundfahrt_id', $this->CI->config->item('iAktuelleRundfahrt'));
			$this->CI->db->where('etappen_nr', $this->aEtappe['etappen_nr']-1);
			$this->aPastStage = $this->CI->db->get('etappen')->row_array();
		} else {
			$this->aPastStage = null;
		}
		
		$this->CI->db->where('etappen_id', $this->aEtappe['etappen_id']);
		$this->aAlleKaderAktuell = $this->CI->db->get('kader')->result_array();
		$this->aAlleKaderAktuell = $this->_arrayNewIndex($this->aAlleKaderAktuell);
		
		if ($this->aPastStage != null) {
			$this->CI->db->where('etappen_id', $this->aPastStage['etappen_id']);
			$this->aAlleKaderGestern = $this->CI->db->get('kader')->result_array();
			$this->aAlleKaderGestern = $this->_arrayNewIndex($this->aAlleKaderGestern);
		}
		
	}
	
	public function getChangeByRider($iUser) {
		if ($this->aPastStage == null) {
			$return[] = $this->aAlleKaderAktuell[$iUser]['fahrer1'];
			$return[] = $this->aAlleKaderAktuell[$iUser]['fahrer2'];
			$return[] = $this->aAlleKaderAktuell[$iUser]['fahrer3'];
			$return[] = $this->aAlleKaderAktuell[$iUser]['fahrer4'];
			$return[] = $this->aAlleKaderAktuell[$iUser]['fahrer5'];
		} else {
			$aKaderNeu = $this->_returnToFahrer($this->aAlleKaderAktuell[$iUser]);
			$aKaderAlt = $this->_returnToFahrer($this->aAlleKaderGestern[$iUser]);
			
			$return = array_diff($aKaderNeu, $aKaderAlt);
		}
		
		return $return;
	}
	
	public function getAllChanges() {
		if ($this->aPastStage == null) {
			foreach($this->aAlleKaderAktuell as $k=>$a) {
				$return[$k]['fahrer1'] = $a['fahrer1'];
				$return[$k]['fahrer2'] = $a['fahrer2'];
				$return[$k]['fahrer3'] = $a['fahrer3'];
				$return[$k]['fahrer4'] = $a['fahrer4'];
				$return[$k]['fahrer5'] = $a['fahrer5'];
			}
		} else {
			foreach($this->aAlleKaderAktuell as $k=>$a) {
				$aKaderNeu = $this->_returnToFahrer($a);
				$aKaderAlt = $this->_returnToFahrer($this->aAlleKaderGestern[$k]);
				$return[$k] = array_diff($aKaderNeu, $aKaderAlt);
				
				$returnA[$k] = array_diff($aKaderAlt, $aKaderNeu);
			}
		}
	
		$this->aAlleWechsel = $return;
		$this->aAlleAuswechsel = $returnA;
		return $return;

	}
	
	
	public function returnSummarizedChanges($art) {
		// Wenn GetAllChanges noch nicht aufgerufen wurde, holen wir dies nach
		if (!isset($this->aAlleWechsel)) {
			$this->getAllChanges();
		}
		
		$aWechselZusammen = array();
		
		if ($art == 'ein') {
			$wechsel = $this->aAlleWechsel;
		} else if ($art == 'aus') {
			$wechsel = $this->aAlleAuswechsel;
		}
		
		$p=0;
		foreach($wechsel as $k=>$a) {
			foreach ($a as $z=>$l) {
				$found = false;
				foreach($aWechselZusammen as $u=>$r) {
					//echo $r['fahrer_id'] . "==" . $l . "<br>";
					if ($r['fahrer_id'] == $l) {
						$aWechselZusammen[$u]['count'] += 1;
						$found = true;
					}
				}
				if ($found == false) {
					$aWechselZusammen[$p]['fahrer_id'] = $l;
					$aWechselZusammen[$p]['count'] = 1;
					$p++;
				}
			}
		}
		return $aWechselZusammen;
	}
	
	private function _arrayNewIndex($aArray) {
		foreach($aArray as $k=>$a) {
			$aNew[$a['user_id']] = $a;
		}
		return $aNew;
	}
	
	private function _returnToFahrer($kader) {
		for ($i=0;$i<5;$i++) {
			$aReturn[] = $kader['fahrer' . ($i+1)];
		}
		return $aReturn;
	}
}