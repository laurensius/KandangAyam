<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_device extends CI_Model{
    
    function simpan_data_suhu_kelembaban($data_suhu_kelembaban){
        $this->db->insert("t_suhu_kelembaban",$data_suhu_kelembaban);
    }

    function simpan_data_log($data_log){
       $this->db->insert("t_log",$data_log);
    }

    function update_state($device,$ubah_state_ke){
        $query = $this->db->query("update t_state set ".$device."='".$ubah_state_ke."'");
    }

    function last_data(){
        $query = $this->db->query("Select * from t_suhu_kelembaban order by id desc limit 30");
        return $query->result();
    }

    function current_state(){
        $query = $this->db->query("Select * from t_state");
        return $query->result();
    }

    function change_mode($mode){
        $query = $this->db->query("update t_state set mode='".$mode."'");
    }

    function today_log(){
        $query = $this->db->query("Select * from t_log where datetime like '". date("Y-m-d") ."%' order by id desc");
        return $query->result();
    }

    
    
}
