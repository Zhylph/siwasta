<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Mendaftarkan user baru
    public function register($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        // Insert ke database
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    // Memeriksa login
    public function login($username, $password) {
        // Cari user berdasarkan username
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        $user = $query->row();

        // Jika user ditemukan
        if ($user) {
            // Verifikasi password
            if (password_verify($password, $user->password)) {
                // Catat login berhasil
                $this->log_login_attempt($user->id, TRUE);
                return $user;
            }
        }

        // Catat login gagal
        $this->log_login_attempt(isset($user->id) ? $user->id : NULL, FALSE);
        return FALSE;
    }

    // Mencatat percobaan login
    private function log_login_attempt($user_id, $success) {
        $data = array(
            'user_id' => $user_id,
            'ip_address' => $this->input->ip_address(),
            'success' => $success
        );

        $this->db->insert('login_attempts', $data);
    }

    // Mendapatkan user berdasarkan ID
    public function get_user_by_id($user_id) {
        $this->db->select('users.*, unit_kerja.nama as unit_kerja_nama');
        $this->db->from('users');
        $this->db->join('unit_kerja', 'unit_kerja.id = users.unit_kerja_id', 'left');
        $this->db->where('users.id', $user_id);
        $query = $this->db->get();
        return $query->row();
    }

    // Mendapatkan user berdasarkan username
    public function get_user_by_username($username) {
        $this->db->select('users.*, unit_kerja.nama as unit_kerja_nama');
        $this->db->from('users');
        $this->db->join('unit_kerja', 'unit_kerja.id = users.unit_kerja_id', 'left');
        $this->db->where('users.username', $username);
        $query = $this->db->get();
        return $query->row();
    }

    // Mendapatkan user berdasarkan email
    public function get_user_by_email($email) {
        $this->db->select('users.*, unit_kerja.nama as unit_kerja_nama');
        $this->db->from('users');
        $this->db->join('unit_kerja', 'unit_kerja.id = users.unit_kerja_id', 'left');
        $this->db->where('users.email', $email);
        $query = $this->db->get();
        return $query->row();
    }

    // Update data user
    public function update_user($user_id, $data) {
        // Jika ada password baru, hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
        return $this->db->affected_rows();
    }

    // Mendapatkan semua user
    public function get_all_users() {
        $this->db->select('users.*, unit_kerja.nama as unit_kerja_nama');
        $this->db->from('users');
        $this->db->join('unit_kerja', 'unit_kerja.id = users.unit_kerja_id', 'left');
        $this->db->order_by('users.full_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    // Mendapatkan user berdasarkan unit kerja
    public function get_users_by_unit_kerja($unit_kerja_id) {
        $this->db->select('users.*, unit_kerja.nama as unit_kerja_nama');
        $this->db->from('users');
        $this->db->join('unit_kerja', 'unit_kerja.id = users.unit_kerja_id', 'left');
        $this->db->where('users.unit_kerja_id', $unit_kerja_id);
        $this->db->order_by('users.full_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
}
