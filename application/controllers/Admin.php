<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('unit_kerja_model');
        $this->load->model('simrs_model');

        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek apakah user memiliki unit kerja Admin
        $this->_check_admin_access();
    }

    // Fungsi untuk memeriksa apakah user memiliki akses admin
    private function _check_admin_access() {
        // Mendapatkan unit kerja user dari session
        $unit_kerja_id = $this->session->userdata('unit_kerja_id');

        // Jika unit_kerja_id tidak ada, redirect ke dashboard
        if (!$unit_kerja_id) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman admin.');
            redirect('dashboard');
        }

        // Mendapatkan data unit kerja
        $unit_kerja = $this->unit_kerja_model->get_by_id($unit_kerja_id);

        // Jika unit kerja bukan Admin, redirect ke dashboard
        if (!$unit_kerja || strtolower($unit_kerja->nama) !== 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman admin.');
            redirect('dashboard');
        }
    }

    // Halaman dashboard admin
    public function index() {
        $data['title'] = 'Admin Dashboard';
        $data['user'] = $this->session->userdata();

        // Meneruskan model unit_kerja_model ke view
        $data['unit_kerja_model'] = $this->unit_kerja_model;

        // Mendapatkan statistik penggunaan SIMRS
        $data['simrs_stats'] = $this->simrs_model->get_simrs_statistics();

        $this->load->view('templates/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }

    // Halaman daftar penggunaan SIMRS
    public function simrs_usage() {
        $data['title'] = 'Penggunaan SIMRS';
        $data['user'] = $this->session->userdata();

        // Meneruskan model unit_kerja_model ke view
        $data['unit_kerja_model'] = $this->unit_kerja_model;

        // Mendapatkan daftar penggunaan SIMRS yang aktif
        $data['active_usage'] = $this->simrs_model->get_active_simrs_usage();

        $this->load->view('templates/header', $data);
        $this->load->view('admin/simrs_usage', $data);
        $this->load->view('templates/footer');
    }
}
