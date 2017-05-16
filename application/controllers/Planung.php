<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ----------------------------------------------------------
 * Copyright (c) 2016 Jonas Bay
 * ----------------------------------------------------------
 * @author		  Jonas Bay
 */

  
class Planung extends Frontend_my_controller 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('Planung_model', 'model');
	}
	
	public function index($iPlanung = 0) {
		$aData = array();
		
		$aData['aPlanung'] = $this->model->getPlanung();
		
		$aData['iPlanung'] = $iPlanung;
		
		foreach($aData['aPlanung'] as $k=>$v) {
			$aData['aPlanung'][$k]['aData'] = $this->model->getPlanungsData($v['id']);
		}
			
		$this->renderPage('planung', $aData, array(), array());
	}
	
	public function createNewPlanung() {
		$iPlanungId = $this->model->saveRecord('planung', array('planung_name'=>$this->input->post('name'), 'user_id'=>$this->session->userdata('user_id'), 'rundfahrt_id'=>$this->config->item('iAktuelleRundfahrt')), -1);
		
		$aKader = $this->model->getKader();
		
		foreach($aKader as $k=>$v) {
			$v['planung_id'] = $iPlanungId;
			$this->model->saveRecord('planung_kader', $v, -1);
		}
	}
	
	public function resetPlanung() {
		$this->model->removeKaderPlanung($this->input->post('id'));
		
		$aKader = $this->model->getKader();
		
		foreach($aKader as $k=>$v) {
			$v['planung_id'] = $this->input->post('id');
			$this->model->saveRecord('planung_kader', $v, -1);
		}
	}
	
	public function kaderuebertragUp() {
		$this->model->kaderuebertragUp($this->input->post('etappen_nr'),$this->input->post('planung_id'));
	}
	
	public function kaderuebertragAll() {
		$this->model->kaderuebertragAll($this->input->post('etappen_nr'),$this->input->post('planung_id'));
	}
	
	public function saveKaderDay() {
		$this->model->saveKaderDay($this->input->post('etappen_nr'),$this->input->post('planung_id'));
	}
	
	public function savePlanung() {
		$this->model->savePlanung($this->input->post('planung_id'));
	}
	
	public function kaderuebertrag() {
		$this->model->kaderuebertrag($this->input->post('etappen_nr'),$this->input->post('planung_id'));
	}
	
	public function renamePlanung() {
		$this->model->saveRecord('planung', array('planung_name'=>$this->input->post('new_name')), $this->input->post('id'), 'id');
	}	
	
	public function changeFahrer() {
		$this->model->updateKader($this->input->post('fahrerposition'), $this->input->post('fahrerid'), $this->input->post('planung'), $this->input->post('etappennr'));
	}
	
	public function removePlanung() {
		$this->model->removePlanung($this->input->post('id'));
	}
}