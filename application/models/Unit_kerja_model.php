<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_kerja_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Mendapatkan semua unit kerja
    public function get_all() {
        $this->db->order_by('nama', 'ASC');
        $query = $this->db->get('unit_kerja');
        return $query->result();
    }
    
    // Mendapatkan unit kerja berdasarkan ID
    public function get_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('unit_kerja');
        return $query->row();
    }
    
    // Menambahkan unit kerja baru
    public function add($data) {
        $this->db->insert('unit_kerja', $data);
        return $this->db->insert_id();
    }
    
    // Mengupdate unit kerja
    public function update($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('unit_kerja', $data);
        return $this->db->affected_rows();
    }
    
    // Menghapus unit kerja
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('unit_kerja');
        return $this->db->affected_rows();
    }
    
    // Mendapatkan jumlah user di setiap unit kerja
    public function get_user_count() {
        $this->db->select('unit_kerja.id, unit_kerja.nama, COUNT(users.id) as user_count');
        $this->db->from('unit_kerja');
        $this->db->join('users', 'users.unit_kerja_id = unit_kerja.id', 'left');
        $this->db->group_by('unit_kerja.id');
        $this->db->order_by('unit_kerja.nama', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    // Mendapatkan user aktif di setiap unit kerja
    public function get_active_users() {
        $this->db->select('unit_kerja.id, unit_kerja.nama, users.id as user_id, users.username, users.full_name, MAX(simrs_usage.start_time) as last_activity, simrs_usage.status');
        $this->db->from('unit_kerja');
        $this->db->join('users', 'users.unit_kerja_id = unit_kerja.id', 'left');
        $this->db->join('simrs_usage', 'simrs_usage.user_id = users.id AND simrs_usage.status = "active"', 'left');
        $this->db->group_by('unit_kerja.id, users.id');
        $this->db->order_by('unit_kerja.nama', 'ASC');
        $this->db->order_by('last_activity', 'DESC');
        $query = $this->db->get();
        
        // Mengorganisir hasil query berdasarkan unit kerja
        $result = array();
        foreach ($query->result() as $row) {
            if (!isset($result[$row->id])) {
                $result[$row->id] = array(
                    'id' => $row->id,
                    'nama' => $row->nama,
                    'users' => array()
                );
            }
            
            if ($row->user_id) {
                $result[$row->id]['users'][] = array(
                    'id' => $row->user_id,
                    'username' => $row->username,
                    'full_name' => $row->full_name,
                    'last_activity' => $row->last_activity,
                    'status' => $row->status
                );
            }
        }
        
        return $result;
    }
    
    // Mendapatkan status penggunaan SIMRS berdasarkan unit kerja
    public function get_simrs_usage_by_unit() {
        $this->db->select('unit_kerja.id, unit_kerja.nama, users.id as user_id, users.username, users.full_name, simrs_usage.start_time, simrs_usage.status');
        $this->db->from('unit_kerja');
        $this->db->join('users', 'users.unit_kerja_id = unit_kerja.id', 'left');
        $this->db->join('simrs_usage', 'simrs_usage.user_id = users.id AND simrs_usage.status = "active"', 'left');
        $this->db->order_by('unit_kerja.nama', 'ASC');
        $this->db->order_by('simrs_usage.start_time', 'DESC');
        $query = $this->db->get();
        
        // Mengorganisir hasil query berdasarkan unit kerja
        $result = array();
        foreach ($query->result() as $row) {
            if (!isset($result[$row->id])) {
                $result[$row->id] = array(
                    'id' => $row->id,
                    'nama' => $row->nama,
                    'active_users' => array()
                );
            }
            
            if ($row->user_id && $row->status == 'active') {
                $result[$row->id]['active_users'][] = array(
                    'id' => $row->user_id,
                    'username' => $row->username,
                    'full_name' => $row->full_name,
                    'start_time' => $row->start_time
                );
            }
        }
        
        return $result;
    }
}
