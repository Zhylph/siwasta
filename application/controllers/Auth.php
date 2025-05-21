<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('unit_kerja_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    // Halaman login
    public function index() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    // Proses login
    public function login() {
        // Set aturan validasi
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembali ke halaman login
            $this->load->view('auth/login');
        } else {
            // Ambil input
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Cek login
            $user = $this->user_model->login($username, $password);

            if ($user) {
                // Jika login berhasil, set session
                $user_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'unit_kerja_id' => $user->unit_kerja_id,
                    'unit_kerja_nama' => $user->unit_kerja_nama,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($user_data);
                redirect('dashboard');
            } else {
                // Jika login gagal
                $this->session->set_flashdata('error', 'Username atau password salah');
                redirect('auth');
            }
        }
    }

    // Halaman registrasi
    public function register() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        // Mendapatkan daftar unit kerja untuk dropdown
        $data['unit_kerja'] = $this->unit_kerja_model->get_all();

        $this->load->view('auth/register', $data);
    }

    // Proses registrasi
    public function process_register() {
        // Set aturan validasi
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('unit_kerja_id', 'Unit Kerja', 'trim');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembali ke halaman registrasi
            $data['unit_kerja'] = $this->unit_kerja_model->get_all();
            $this->load->view('auth/register', $data);
        } else {
            // Ambil input
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'full_name' => $this->input->post('full_name'),
                'unit_kerja_id' => $this->input->post('unit_kerja_id')
            );

            // Simpan ke database
            $user_id = $this->user_model->register($data);

            if ($user_id) {
                // Jika registrasi berhasil
                $this->session->set_flashdata('success', 'Registrasi berhasil. Silahkan login.');
                redirect('auth');
            } else {
                // Jika registrasi gagal
                $this->session->set_flashdata('error', 'Registrasi gagal. Silahkan coba lagi.');
                redirect('auth/register');
            }
        }
    }

    // Logout
    public function logout() {
        // Hapus semua session
        $this->session->sess_destroy();
        redirect('auth');
    }
}
