 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->POST_MODE_DEFAULT = "POST_MODE_DEFAULT";
		$this->POST_MODE_WITH_STATE = "POST_MODE_WITH_STATE";
		$this->STATE_SET_ON = "SET_ON";
		$this->STATE_SET_OFF = "SET_OFF";
		$this->STATE_SET_NULL = "NULL";
		$this->STATE_ON = "1";
		$this->STATE_OFF = "0";
		$this->USER_AGENT_AUTO = "AUTO MACHINE";
		$this->USER_AGENT_WEB = "WEB APPS";
		$this->USER_AGENT_MOBILE = "ANDROID APPS";
		$this->DEVICE_LAMPU = "lampu";
		$this->DEVICE_KIPAS = "kipas";
		$this->load->model('mod_device');
	}

	public function index(){
		echo "Anda tidak diperkenankan mengakses URL ini. Terima kasih";
	}

	//POST ke server : suhu, kelembaban, post_mode, state_lampu, state_kipas
	public function post_data(){
		// if($this->input->post('data')!=null){
		if($this->uri->segment(3)!=null && $this->uri->segment(4)!=null && $this->uri->segment(5)!=null && $this->uri->segment(6)!=null && $this->uri->segment(7)!=null){
			$data = $this->uri->segment(3) ."#".  $this->uri->segment(4) ."#". $this->uri->segment(5) ."#". $this->uri->segment(6) ."#". $this->uri->segment(7);
			$explode = explode("#", $data);
			$datetime = date("Y-m-d H:i:s");
			$data_suhu_kelembaban = array(
				"id" => "",
				"datetime" => $datetime,
				"suhu" => $explode[0],
				"kelembaban" => $explode[1]);
			if($explode[2]==$this->POST_MODE_DEFAULT){
				$this->mod_device->simpan_data_suhu_kelembaban($data_suhu_kelembaban);
				echo "#Success POST_MODE_DEFAULT".$this->response_to_arduino();
			}else
			if($explode[2]==$this->POST_MODE_WITH_STATE){
				$this->mod_device->simpan_data_suhu_kelembaban($data_suhu_kelembaban);
				if($explode[3]==$this->STATE_SET_ON){
					$state_lampu = $this->STATE_ON;
				}else
				if($explode[3]==$this->STATE_SET_OFF){
					$state_lampu = $this->STATE_OFF;
				}
				if($explode[4]==$this->STATE_SET_ON){
					$state_kipas = $this->STATE_ON;
				}else
				if($explode[4]==$this->STATE_SET_OFF){
					$state_kipas = $this->STATE_OFF;
				}
				if($explode[3]!=$this->STATE_SET_NULL){
					$data_log_lampu = array(
						"id" => "",
						"datetime" => $datetime,
						"device" => $this->DEVICE_LAMPU,
						"activity" => $explode[3],
						"user_agent" => $this->USER_AGENT_AUTO);
					$this->mod_device->simpan_data_log($data_log_lampu);	
					$this->mod_device->update_state($this->DEVICE_LAMPU,$state_lampu);
				}
				if($explode[4]!=$this->STATE_SET_NULL){
					$data_log_kipas = array(
						"id" => "",
						"datetime" => $datetime,
						"device" => $this->DEVICE_KIPAS,
						"activity" => $explode[4],
						"user_agent" => $this->USER_AGENT_AUTO);
					$this->mod_device->simpan_data_log($data_log_kipas);
					$this->mod_device->update_state($this->DEVICE_KIPAS,$state_kipas);	
				}
				echo "#Success POST_MODE_WITH_STATE".$this->response_to_arduino();
			}
		}else{
			echo "#Anda tidak diperkenankan mengakses URL ini. Terima kasih".$this->response_to_arduino();	
		}
	}

	public function update_state(){
		if($this->input->post("ubah_state_ke")==$this->STATE_SET_ON){
			$state = $this->STATE_ON;
		}else
		if($this->input->post("ubah_state_ke")==$this->STATE_SET_OFF){
			$state = $this->STATE_OFF;
		}
		$data_log = array(
			"id" => "",
			"datetime" => date("Y-m-d H:i:s"),
			"device" => $this->input->post("device"),
			"activity" => $this->input->post("ubah_state_ke"),
			"user_agent" => $this->input->post("user_agent"));
		$this->mod_device->update_state($this->input->post("device"),$state);
		$this->mod_device->simpan_data_log($data_log);
	}

	public function change_mode(){
		if($this->uri->segment(3)!=null){
			$this->mod_device->change_mode($this->uri->segment(3));
		}
	}

	public function dataset(){
		$dataset = array(
			"last_data" => $this->mod_device->last_data(),
			"current_state" => $this->mod_device->current_state(),
			"today_log" => $this->mod_device->today_log()
			);
		header('Content-Type: application/json');
		echo json_encode(array("dataset"=>$dataset));
	}


	public function response_to_arduino(){
		$response =  $this->mod_device->current_state();
		foreach ($response as $val) {
			$return = "*".$val->lampu."*".$val->kipas."*".$val->mode."^";
		}
		return $return;
	}

	public function test(){
		header("Content-Type: application/vnd.ms-excel");
 		header('Content-Disposition: attachment; filename=report__'.date("d_m_Y").'.xls');
 		echo "sdfsd";
	}


// http://webcheatsheet.com/php/create_word_excel_csv_files_with_php.php

}
