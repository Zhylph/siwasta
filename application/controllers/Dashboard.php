<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('simrs_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('unit_kerja_model');
        $this->load->model('user_model');

        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    // Halaman dashboard
    public function index() {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->session->userdata();

        // Meneruskan model unit_kerja_model ke view
        $data['unit_kerja_model'] = $this->unit_kerja_model;

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }

    // Halaman profil
    public function profile() {
        $data['title'] = 'Profil';
        $data['user'] = $this->session->userdata();

        // Meneruskan model unit_kerja_model ke view
        $data['unit_kerja_model'] = $this->unit_kerja_model;

        // Mendapatkan daftar unit kerja untuk dropdown
        $data['unit_kerja'] = $this->unit_kerja_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/profile', $data);
        $this->load->view('templates/footer');
    }

    // Proses update profil
    public function update_profile() {
        // Set aturan validasi
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('contact', 'Kontak', 'trim');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembali ke halaman profil
            $this->profile();
        } else {
            // Ambil input
            $user_id = $this->session->userdata('user_id');
            $data = array(
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'unit_kerja_id' => $this->input->post('unit_kerja_id'),
                'contact' => $this->input->post('contact')
            );

            // Upload foto jika ada
            if (!empty($_FILES['photo']['name'])) {
                // Konfigurasi upload
                $config['upload_path'] = './uploads/photos/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'user_' . $user_id . '_' . time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('photo')) {
                    // Jika upload berhasil
                    $upload_data = $this->upload->data();
                    $data['photo'] = $upload_data['file_name'];

                    // Hapus foto lama jika ada
                    $user = $this->user_model->get_user_by_id($user_id);
                    if (!empty($user->photo) && file_exists('./uploads/photos/' . $user->photo)) {
                        unlink('./uploads/photos/' . $user->photo);
                    }
                } else {
                    // Jika upload gagal
                    $this->session->set_flashdata('error', 'Gagal mengupload foto: ' . $this->upload->display_errors('', ''));
                }
            }

            // Jika ada password baru
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');
            $confirm_password = $this->input->post('confirm_password');

            if (!empty($current_password) && !empty($new_password)) {
                // Validasi password
                if ($new_password != $confirm_password) {
                    $this->session->set_flashdata('error', 'Password baru dan konfirmasi password tidak sama.');
                    redirect('dashboard/profile');
                    return;
                }

                // Cek password saat ini
                $user = $this->user_model->get_user_by_id($user_id);
                if (!password_verify($current_password, $user->password)) {
                    $this->session->set_flashdata('error', 'Password saat ini salah.');
                    redirect('dashboard/profile');
                    return;
                }

                // Set password baru
                $data['password'] = $new_password;
            }

            // Update data user
            $result = $this->user_model->update_user($user_id, $data);

            if ($result) {
                // Jika update berhasil, update session
                $user = $this->user_model->get_user_by_id($user_id);
                $user_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'unit_kerja_id' => $user->unit_kerja_id,
                    'unit_kerja_nama' => $user->unit_kerja_nama,
                    'photo' => $user->photo,
                    'contact' => $user->contact,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($user_data);
                $this->session->set_flashdata('success', 'Profil berhasil diupdate.');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate profil.');
            }

            redirect('dashboard/profile');
        }
    }

    // Halaman SIMRS
    public function simrs() {
        $data['title'] = 'SIMRS';
        $data['user'] = $this->session->userdata();

        // Meneruskan model unit_kerja_model ke view
        $data['unit_kerja_model'] = $this->unit_kerja_model;

        // Mendapatkan riwayat penggunaan SIMRS oleh user
        $user_id = $this->session->userdata('user_id');
        $data['simrs_history'] = $this->simrs_model->get_user_simrs_history($user_id, 5);

        // Mendapatkan unit kerja user
        $user = $this->user_model->get_user_by_id($user_id);
        $unit_kerja_id = $user->unit_kerja_id;

        // Jika user memiliki unit kerja, cek apakah ada user lain dari unit kerja yang sama yang sedang aktif
        if ($unit_kerja_id) {
            // Mendapatkan daftar user aktif berdasarkan unit kerja
            $active_users = $this->simrs_model->get_active_simrs_users_by_unit();

            // Jika ada user aktif di unit kerja yang sama
            if (isset($active_users[$unit_kerja_id]) &&
                isset($active_users[$unit_kerja_id]['active_user']) &&
                $active_users[$unit_kerja_id]['active_user']['id'] != $user_id) {

                $active_user = $active_users[$unit_kerja_id]['active_user'];
                $data['active_user_warning'] = array(
                    'unit_kerja' => $active_users[$unit_kerja_id]['nama'],
                    'user_name' => $active_user['full_name'],
                    'start_time' => $active_user['start_time']
                );
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/simrs', $data);
        $this->load->view('templates/footer');
    }

    // Menjalankan aplikasi SIMRS
    public function run_simrs() {
        // Mencatat penggunaan SIMRS
        $user_id = $this->session->userdata('user_id');

        // Menutup sesi aktif lainnya dari user yang sama (jika ada)
        // Ini sudah ditangani di dalam log_simrs_usage() untuk unit kerja yang sama

        $log_id = $this->simrs_model->log_simrs_usage($user_id);

        // Menyimpan log_id ke session untuk keperluan update status nanti
        $this->session->set_userdata('simrs_log_id', $log_id);

        // Mencoba menjalankan aplikasi SIMRS menggunakan popen/pclose
        $bat_file = FCPATH . 'run_simrs.bat';

        // Gunakan popen dan pclose untuk menjalankan proses secara asinkron
        // dan memastikan sumber daya dilepaskan dengan benar
        $command = 'start /b cmd /c start cmd /k "' . $bat_file . '"';
        $handle = popen($command, 'r');
        pclose($handle);

        // Pastikan sesi PHP segera ditulis dan ditutup
        session_write_close();

        // Mendapatkan informasi unit kerja
        $usage = $this->simrs_model->get_usage_by_id($log_id);
        $unit_kerja_nama = ($usage && $usage->unit_kerja_nama) ? $usage->unit_kerja_nama : 'Tidak Ada Unit';

        // Mengirim response JSON
        $response = array(
            'status' => 'success',
            'message' => 'SIMRS berhasil dijalankan',
            'log_id' => $log_id,
            'simrs_path' => 'F:\\App\\SIMRS-Monarch\\aplikasi.bat',
            'bat_file' => $bat_file,
            'unit_kerja' => $unit_kerja_nama
        );

        echo json_encode($response);
    }

    // Halaman untuk menjalankan SIMRS langsung
    public function launch_simrs() {
        // Mencatat penggunaan SIMRS
        $user_id = $this->session->userdata('user_id');

        // Menutup sesi aktif lainnya dari user yang sama (jika ada)
        // Ini sudah ditangani di dalam log_simrs_usage() untuk unit kerja yang sama

        $log_id = $this->simrs_model->log_simrs_usage($user_id);

        // Menyimpan log_id ke session
        $this->session->set_userdata('simrs_log_id', $log_id);

        // Mendapatkan informasi unit kerja
        $usage = $this->simrs_model->get_usage_by_id($log_id);
        $unit_kerja_nama = ($usage && $usage->unit_kerja_nama) ? $usage->unit_kerja_nama : 'Tidak Ada Unit';

        $this->session->set_flashdata('success', 'SIMRS berhasil dijalankan untuk unit kerja ' . $unit_kerja_nama . '. Jendela CMD akan tetap terbuka untuk menampilkan log.');

        // Mencoba menjalankan aplikasi SIMRS menggunakan popen/pclose
        $bat_file = FCPATH . 'run_simrs.bat';

        // Gunakan popen dan pclose untuk menjalankan proses secara asinkron
        // dan memastikan sumber daya dilepaskan dengan benar
        $command = 'start /b cmd /c start cmd /k "' . $bat_file . '"';
        $handle = popen($command, 'r');
        pclose($handle);

        // Pastikan sesi PHP segera ditulis dan ditutup
        session_write_close();

        // Redirect kembali ke halaman SIMRS
        redirect('dashboard/simrs');
    }

    // Mengupdate status penggunaan SIMRS
    public function update_simrs_status() {
        $log_id = $this->input->post('log_id');
        $status = $this->input->post('status');

        $result = $this->simrs_model->update_simrs_status($log_id, $status);

        // Pastikan sesi PHP segera ditulis dan ditutup
        session_write_close();

        // Mengirim response JSON
        $response = array(
            'status' => ($result > 0) ? 'success' : 'error',
            'message' => ($result > 0) ? 'Status berhasil diupdate' : 'Gagal mengupdate status'
        );

        echo json_encode($response);
    }
}
