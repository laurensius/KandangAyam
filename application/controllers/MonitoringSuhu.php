<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MonitoringSuhu extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->POST_MODE_DEFAULT = "POST_MODE_DEFAULT";
        $this->POST_MODE_WITH_STATE = "POST_MODE_WITH_STATE";
        $this->STATE_SET_ON = "SET ON";
        $this->STATE_SET_OFF = "SET OFF";
        $this->STATE_SET_NULL = "NULL";
        $this->STATE_ON = "1";
        $this->STATE_OFF = "0";
        $this->USER_AGENT_AUTO = "AUTO MACHINE";
        $this->USER_AGENT_WEB = "WEB APPS";
        $this->USER_AGENT_MOBILE = "ANDROID APPS";
        $this->DEVICE_LAMPU = "lampu";
        $this->DEVICE_KIPAS = "kipas";
        $this->load->model('mod_device');        
        $this->load->model("mod_user");
        
    }

    public function index(){
        if($this->session->userdata("session_aalsystem_code")){
            $this->load->view("aal/header");
            $this->load->view("aal/body_monitoring");
            $this->load->view("aal/footer");
        }else{
            $this->load->view("aal/login");
        }
    }

    public function monitoring_suhu(){
        if($this->session->userdata("session_aalsystem_code")){
            $this->load->view("aal/header");
            $this->load->view("aal/body_monitoring");
            $this->load->view("aal/footer");
        }else{
            $this->load->view("aal/login");
        }
    }



    public function system_log(){
        if($this->session->userdata("session_aalsystem_code")){
            $this->load->view("aal/header");
            $this->load->view("aal/body_log");
            $this->load->view("aal/footer");
        }else{
            $this->load->view("aal/login");
        }
    }

    public function bulb_control(){
        if($this->session->userdata("session_aalsystem_code")){
            $this->load->view("aal/header");
            $this->load->view("aal/body_bulb_control");
            $this->load->view("aal/footer");
        }else{
            $this->load->view("aal/login");
        }
    }

    public function fan_control(){
        if($this->session->userdata("session_aalsystem_code")){
            $this->load->view("aal/header");
            $this->load->view("aal/body_fan_control");
            $this->load->view("aal/footer");
        }else{
            $this->load->view("aal/login");
        }
    }

    
    public function login(){
        $this->load->view("aal/login");
    }
    
    public function logout(){
        if($this->session->userdata("session_aalsystem_code")){
            $this->session->sess_destroy();
            $return = array(
                "status_cek" => null,
                "message" => "Anda baru saja logout.",
                "message_severity" => "success",
                "data_user" => null
            );
        }else{
            $return = array(
                "status_cek" => null,
                "message" => "Silahkan login",
                "message_severity" => "info",
                "data_user" => null
            );
        }
        $this->load->view("aal/login",$return);
    }
    
    public function verifikasi(){
        if($this->input->post()!=null){
            $data = array(
            "username" => $this->input->post('username'),
            "password" => $this->input->post('password'));
            $resultcek = $this->get_user_by_username($data);
            if($resultcek==null){
                $return = array(
                    "status_cek" => "NOT FOUND",
                    "message" => "Username tidak terdaftar",
                    "message_severity" => "danger",
                    "data_user" => null
                );
            }else{
                $return = $this->matching($data,$resultcek);
            } 
        }else{
            $return = array(
                "status_cek" => "NO DATA POSTED",
                "message" => "Tidak ada data dikirim ke server",
                "message_severity" => "danger",
                "data_user" => null
            );
        }
        $this->load->view("aal/login",$return);
        // echo json_encode(array("Hasil_Verifikasi"=>$return));
    }
    
    public function get_user_by_username($data){
        return $this->mod_user->get_user_by_username($data);
    }
    
    public function matching($data,$resultcek){
        if($data["username"] == $resultcek[0]->username && $data["password"] == $resultcek[0]->password){
            $status_cek = "MATCH";
            $message = "Username dan password sesuai";
            $this->buat_session($resultcek);
            redirect(site_url()."/monitoringsuhu/monitoring_suhu/");
        }else{
            $status_cek = "NOT MATCH";
            $message = "Username dan password tidak sesuai";
        }
        $return = array(
            "status_cek" => $status_cek,
            "message" => $message,
            "message_severity" => "warning",
            "data_user" => $resultcek
        );
        return $return;
    }
    
    public function buat_session($resultcek){
        $waktu = date("Y-m-d H:i:s");
        $this->update_login_timestamp($resultcek[0]->id,array("last_login" => $waktu));
        $data_session = array(
            "session_aalsystem_code"=>"SeCuRe".date("YmdHis")."#".date("YHmids"),
            "session_aalsystem_id"=>$resultcek[0]->id,
            "session_aalsystem_username"=>$resultcek[0]->username,
            "session_aalsystem_nama_lengkap"=>$resultcek[0]->nama_lengkap,
            "session_aalsystem_last_login"=>$waktu
        );
        $this->session->set_userdata($data_session);
    }
    
    public function update_login_timestamp($id,$data){
        $this->mod_user->update_login_timestamp($id,$data);
    }
        
        

	



}
