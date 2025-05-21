<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Public_view extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('whatsapp');
        $this->load->library('session');
        $this->load->model('unit_kerja_model');
        $this->load->model('simrs_model');
    }

    // Halaman utama view publik
    public function index() {
        $data['title'] = 'Dashboard Publik';

        // Menutup sesi yang tidak aktif dalam 60 menit terakhir
        $this->simrs_model->close_inactive_sessions(60);

        // Mendapatkan data unit kerja dan user aktif (1 user per unit kerja)
        $data['active_users'] = $this->simrs_model->get_active_simrs_users_by_unit();

        $this->load->view('public/header', $data);
        $this->load->view('public/dashboard', $data);
        $this->load->view('public/footer');
    }

    // Halaman detail unit kerja
    public function unit($unit_id) {
        $unit = $this->unit_kerja_model->get_by_id($unit_id);

        if (!$unit) {
            show_404();
        }

        $data['title'] = 'Unit Kerja: ' . $unit->nama;
        $data['unit'] = $unit;

        // Mendapatkan user di unit kerja ini
        $data['users'] = $this->user_model->get_users_by_unit_kerja($unit_id);

        $this->load->view('public/header', $data);
        $this->load->view('public/unit_detail', $data);
        $this->load->view('public/footer');
    }

    // API untuk mendapatkan data realtime
    public function get_active_users() {
        // Menutup sesi yang tidak aktif dalam 60 menit terakhir
        $this->simrs_model->close_inactive_sessions(60);

        $active_users = $this->simrs_model->get_active_simrs_users_by_unit();

        // Mengirim response JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($active_users));
    }
}
