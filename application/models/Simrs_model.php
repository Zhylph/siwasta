<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simrs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Mencatat aktivitas penggunaan SIMRS
    public function log_simrs_usage($user_id) {
        // Dapatkan unit_kerja_id dari user
        $this->db->select('unit_kerja_id');
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        $user = $query->row();

        $unit_kerja_id = ($user) ? $user->unit_kerja_id : NULL;

        // Jika user memiliki unit kerja, tutup sesi aktif lainnya dari unit kerja yang sama
        if ($unit_kerja_id) {
            $this->close_active_sessions_by_unit($unit_kerja_id, $user_id);
        }

        $data = array(
            'user_id' => $user_id,
            'unit_kerja_id' => $unit_kerja_id,
            'ip_address' => $this->input->ip_address(),
            'start_time' => date('Y-m-d H:i:s'),
            'status' => 'active'
        );

        $this->db->insert('simrs_usage', $data);
        return $this->db->insert_id();
    }

    // Menutup sesi aktif berdasarkan unit kerja (kecuali user tertentu)
    private function close_active_sessions_by_unit($unit_kerja_id, $exclude_user_id = NULL) {
        $this->db->set('status', 'closed');
        $this->db->set('end_time', date('Y-m-d H:i:s'));
        $this->db->where('unit_kerja_id', $unit_kerja_id);
        $this->db->where('status', 'active');

        // Jika ada user yang dikecualikan
        if ($exclude_user_id) {
            $this->db->where('user_id !=', $exclude_user_id);
        }

        $this->db->update('simrs_usage');

        return $this->db->affected_rows();
    }

    // Menutup semua sesi aktif dari user tertentu
    public function close_active_sessions_by_user($user_id) {
        $this->db->set('status', 'closed');
        $this->db->set('end_time', date('Y-m-d H:i:s'));
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'active');

        $this->db->update('simrs_usage');

        return $this->db->affected_rows();
    }

    // Mengupdate status penggunaan SIMRS
    public function update_simrs_status($log_id, $status) {
        $data = array(
            'status' => $status,
            'end_time' => ($status == 'closed') ? date('Y-m-d H:i:s') : NULL
        );

        $this->db->where('id', $log_id);
        $this->db->update('simrs_usage', $data);
        return $this->db->affected_rows();
    }

    // Mendapatkan daftar penggunaan SIMRS yang aktif
    public function get_active_simrs_usage() {
        $this->db->select('simrs_usage.*, users.username, users.full_name, unit_kerja.nama as unit_kerja_nama');
        $this->db->from('simrs_usage');
        $this->db->join('users', 'users.id = simrs_usage.user_id');
        $this->db->join('unit_kerja', 'unit_kerja.id = simrs_usage.unit_kerja_id', 'left');
        $this->db->where('simrs_usage.status', 'active');
        $this->db->order_by('unit_kerja.nama', 'ASC');
        $this->db->order_by('simrs_usage.start_time', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    // Mendapatkan riwayat penggunaan SIMRS oleh user tertentu
    public function get_user_simrs_history($user_id, $limit = 10) {
        $this->db->select('simrs_usage.*, unit_kerja.nama as unit_kerja_nama');
        $this->db->from('simrs_usage');
        $this->db->join('unit_kerja', 'unit_kerja.id = simrs_usage.unit_kerja_id', 'left');
        $this->db->where('simrs_usage.user_id', $user_id);
        $this->db->order_by('simrs_usage.start_time', 'DESC');
        $this->db->limit($limit);

        $query = $this->db->get();
        return $query->result();
    }

    // Mendapatkan penggunaan SIMRS berdasarkan ID
    public function get_usage_by_id($log_id) {
        $this->db->select('simrs_usage.*, users.username, users.full_name, unit_kerja.nama as unit_kerja_nama');
        $this->db->from('simrs_usage');
        $this->db->join('users', 'users.id = simrs_usage.user_id');
        $this->db->join('unit_kerja', 'unit_kerja.id = simrs_usage.unit_kerja_id', 'left');
        $this->db->where('simrs_usage.id', $log_id);

        $query = $this->db->get();
        return $query->row();
    }

    // Mendapatkan statistik penggunaan SIMRS
    public function get_simrs_statistics() {
        // Total penggunaan
        $this->db->select('COUNT(*) as total_usage');
        $query_total = $this->db->get('simrs_usage');
        $total = $query_total->row()->total_usage;

        // Penggunaan hari ini
        $this->db->select('COUNT(*) as today_usage');
        $this->db->where('DATE(start_time)', date('Y-m-d'));
        $query_today = $this->db->get('simrs_usage');
        $today = $query_today->row()->today_usage;

        // Penggunaan aktif saat ini
        $this->db->select('COUNT(*) as active_usage');
        $this->db->where('status', 'active');
        $query_active = $this->db->get('simrs_usage');
        $active = $query_active->row()->active_usage;

        return array(
            'total' => $total,
            'today' => $today,
            'active' => $active
        );
    }

    // Mendapatkan penggunaan SIMRS berdasarkan unit kerja
    public function get_simrs_usage_by_unit_kerja() {
        $this->db->select('unit_kerja.id, unit_kerja.nama, COUNT(simrs_usage.id) as usage_count');
        $this->db->from('unit_kerja');
        $this->db->join('simrs_usage', 'simrs_usage.unit_kerja_id = unit_kerja.id', 'left');
        $this->db->group_by('unit_kerja.id');
        $this->db->order_by('unit_kerja.nama', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    // Mendapatkan penggunaan SIMRS aktif berdasarkan unit kerja
    public function get_active_simrs_by_unit_kerja() {
        $this->db->select('unit_kerja.id, unit_kerja.nama, COUNT(simrs_usage.id) as active_count');
        $this->db->from('unit_kerja');
        $this->db->join('simrs_usage', 'simrs_usage.unit_kerja_id = unit_kerja.id AND simrs_usage.status = "active"', 'left');
        $this->db->group_by('unit_kerja.id');
        $this->db->order_by('unit_kerja.nama', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    // Mendapatkan daftar user aktif dengan informasi SIMRS dan unit kerja
    public function get_active_users_with_simrs() {
        $this->db->select('users.id, users.username, users.full_name, users.photo, users.contact, unit_kerja.id as unit_kerja_id, unit_kerja.nama as unit_kerja_nama, simrs_usage.id as simrs_id, simrs_usage.start_time, simrs_usage.status');
        $this->db->from('users');
        $this->db->join('unit_kerja', 'unit_kerja.id = users.unit_kerja_id', 'left');
        $this->db->join('simrs_usage', 'simrs_usage.user_id = users.id AND simrs_usage.status = "active"', 'left');
        $this->db->order_by('unit_kerja.nama', 'ASC');
        $this->db->order_by('users.full_name', 'ASC');

        $query = $this->db->get();

        // Mengorganisir hasil query berdasarkan unit kerja
        $result = array();
        foreach ($query->result() as $row) {
            $unit_id = $row->unit_kerja_id ? $row->unit_kerja_id : 0;
            $unit_nama = $row->unit_kerja_nama ? $row->unit_kerja_nama : 'Tidak Ada Unit';

            if (!isset($result[$unit_id])) {
                $result[$unit_id] = array(
                    'id' => $unit_id,
                    'nama' => $unit_nama,
                    'users' => array()
                );
            }

            $result[$unit_id]['users'][] = array(
                'id' => $row->id,
                'username' => $row->username,
                'full_name' => $row->full_name,
                'photo' => $row->photo,
                'contact' => $row->contact,
                'simrs_active' => ($row->simrs_id) ? true : false,
                'start_time' => $row->start_time,
                'status' => $row->status
            );
        }

        return $result;
    }

    // Mendapatkan daftar user yang sedang aktif menggunakan SIMRS (1 user per unit kerja)
    public function get_active_simrs_users_by_unit() {
        // Subquery untuk mendapatkan user dengan aktivitas SIMRS terbaru di setiap unit kerja
        $subquery = "SELECT su.user_id, su.unit_kerja_id, MAX(su.start_time) as latest_time
                    FROM simrs_usage su
                    WHERE su.status = 'active'
                    GROUP BY su.unit_kerja_id";

        $this->db->select('users.id, users.username, users.full_name, users.photo, users.contact, unit_kerja.id as unit_kerja_id, unit_kerja.nama as unit_kerja_nama, simrs_usage.start_time, simrs_usage.status');
        $this->db->from('unit_kerja');
        $this->db->join("($subquery) as latest", 'latest.unit_kerja_id = unit_kerja.id', 'left');
        $this->db->join('simrs_usage', 'simrs_usage.user_id = latest.user_id AND simrs_usage.start_time = latest.latest_time', 'left');
        $this->db->join('users', 'users.id = latest.user_id', 'left');
        $this->db->order_by('unit_kerja.nama', 'ASC');

        $query = $this->db->get();

        // Mengorganisir hasil query berdasarkan unit kerja
        $result = array();
        foreach ($query->result() as $row) {
            if (!$row->id) {
                // Skip jika tidak ada user aktif di unit ini
                continue;
            }

            $unit_id = $row->unit_kerja_id;

            $result[$unit_id] = array(
                'id' => $unit_id,
                'nama' => $row->unit_kerja_nama,
                'active_user' => array(
                    'id' => $row->id,
                    'username' => $row->username,
                    'full_name' => $row->full_name,
                    'photo' => $row->photo,
                    'contact' => $row->contact,
                    'start_time' => $row->start_time,
                    'status' => $row->status
                )
            );
        }

        return $result;
    }

    // Menutup sesi yang tidak aktif dalam jangka waktu tertentu
    public function close_inactive_sessions($timeout_minutes = 60) {
        $timeout = date('Y-m-d H:i:s', strtotime("-$timeout_minutes minutes"));

        $this->db->set('status', 'closed');
        $this->db->set('end_time', date('Y-m-d H:i:s'));
        $this->db->where('status', 'active');
        $this->db->where('start_time <', $timeout);

        $this->db->update('simrs_usage');

        return $this->db->affected_rows();
    }
}
