<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper('file');
        $this->load->model('unit_kerja_model');

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
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman konfigurasi.');
            redirect('dashboard');
        }

        // Mendapatkan data unit kerja
        $unit_kerja = $this->unit_kerja_model->get_by_id($unit_kerja_id);

        // Jika unit kerja bukan Admin, redirect ke dashboard
        if (!$unit_kerja || strtolower($unit_kerja->nama) !== 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman konfigurasi.');
            redirect('dashboard');
        }
    }

    // Halaman konfigurasi SIMRS
    public function simrs() {
        $data['title'] = 'Konfigurasi SIMRS';
        $data['user'] = $this->session->userdata();

        // Cek apakah file konfigurasi sudah ada
        $config_file = FCPATH . 'simrs_config.json';
        if (file_exists($config_file)) {
            $data['config'] = json_decode(file_get_contents($config_file), true);
        } else {
            $data['config'] = array(
                'simrs_path' => 'F:\App\SIMRS-Monarch\aplikasi.bat',
                'last_updated' => date('Y-m-d H:i:s'),
                'updated_by' => $data['user']['username']
            );
        }

        // Daftar lokasi yang umum
        $data['common_locations'] = array(
            'F:\App\SIMRS-Monarch\aplikasi.bat',
            'C:\SIMRS\aplikasi.bat',
            'C:\Program Files\SIMRS\aplikasi.bat',
            'C:\Program Files (x86)\SIMRS\aplikasi.bat',
            'D:\SIMRS\aplikasi.bat',
            'F:\App\SIMRS-Monarch\SIMRS.jar',
            'C:\SIMRS\SIMRS.jar',
            'C:\Program Files\SIMRS\SIMRS.jar',
            'C:\Program Files (x86)\SIMRS\SIMRS.jar',
            'D:\SIMRS\SIMRS.jar'
        );

        $this->load->view('templates/header', $data);
        $this->load->view('config/simrs', $data);
        $this->load->view('templates/footer');
    }

    // Menyimpan konfigurasi SIMRS
    public function save_simrs_config() {
        // Ambil data dari form
        $simrs_path = $this->input->post('simrs_path');

        // Validasi path
        if (empty($simrs_path)) {
            $this->session->set_flashdata('error', 'Path SIMRS tidak boleh kosong.');
            redirect('config/simrs');
            return;
        }

        // Buat data konfigurasi
        $config = array(
            'simrs_path' => $simrs_path,
            'last_updated' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('username')
        );

        // Simpan ke file JSON
        $config_file = FCPATH . 'simrs_config.json';
        if (write_file($config_file, json_encode($config, JSON_PRETTY_PRINT))) {
            // Juga update file batch
            $this->_update_batch_file($simrs_path);

            $this->session->set_flashdata('success', 'Konfigurasi SIMRS berhasil disimpan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan konfigurasi SIMRS.');
        }

        redirect('config/simrs');
    }

    // Menjalankan SIMRS dengan konfigurasi yang tersimpan
    public function run_simrs() {
        // Cek apakah file konfigurasi sudah ada
        $config_file = FCPATH . 'simrs_config.json';
        if (file_exists($config_file)) {
            $config = json_decode(file_get_contents($config_file), true);
            $simrs_path = $config['simrs_path'];

            // Mencatat penggunaan SIMRS
            $this->load->model('simrs_model');
            $user_id = $this->session->userdata('user_id');
            $log_id = $this->simrs_model->log_simrs_usage($user_id);

            // Menyimpan log_id ke session
            $this->session->set_userdata('simrs_log_id', $log_id);

            // Menjalankan aplikasi SIMRS
            if (file_exists($simrs_path)) {
                // Perbarui file batch terlebih dahulu
                $this->_update_batch_file($simrs_path);

                // Jalankan file batch dengan jendela CMD yang terlihat
                $batch_file = FCPATH . 'run_simrs.bat';

                // Gunakan popen dan pclose untuk menjalankan proses secara asinkron
                // dan memastikan sumber daya dilepaskan dengan benar
                $command = 'start /b cmd /c start cmd /k "' . $batch_file . '"';
                $handle = popen($command, 'r');
                pclose($handle);

                // Pastikan sesi PHP segera ditulis dan ditutup
                session_write_close();

                $this->session->set_flashdata('success', 'SIMRS berhasil dijalankan. Jendela CMD akan tetap terbuka untuk menampilkan log.');
            } else {
                $this->session->set_flashdata('error', 'File SIMRS tidak ditemukan: ' . $simrs_path);
            }
        } else {
            $this->session->set_flashdata('error', 'Konfigurasi SIMRS belum diatur. Silakan atur terlebih dahulu.');
        }

        redirect('dashboard/simrs');
    }

    // Fungsi untuk mengupdate file batch
    private function _update_batch_file($simrs_path) {
        $batch_content = "@echo on\r\n";
        $batch_content .= "title SIMRS Monitor - Log Window\r\n";
        $batch_content .= "color 0A\r\n";
        $batch_content .= "echo ========================================\r\n";
        $batch_content .= "echo Menjalankan SIMRS...\r\n";
        $batch_content .= "echo Jendela ini akan tetap terbuka untuk menampilkan log\r\n";
        $batch_content .= "echo ========================================\r\n";
        $batch_content .= "\r\n";

        // Jika file adalah .bat
        if (strtolower(substr($simrs_path, -4)) == '.bat') {
            $dir = dirname($simrs_path);
            $batch_content .= "echo Direktori: " . $dir . "\r\n";
            $batch_content .= "echo File: " . $simrs_path . "\r\n";
            $batch_content .= "echo ========================================\r\n";
            $batch_content .= "\r\n";
            $batch_content .= "REM Jalankan aplikasi dengan prioritas normal untuk mengurangi dampak pada sistem\r\n";
            $batch_content .= "cd /d \"" . $dir . "\"\r\n";
            $batch_content .= "start /B /NORMAL call \"" . $simrs_path . "\"\r\n";
        }
        // Jika file adalah .jar
        else if (strtolower(substr($simrs_path, -4)) == '.jar') {
            $dir = dirname($simrs_path);
            $batch_content .= "echo Direktori: " . $dir . "\r\n";
            $batch_content .= "echo File JAR: " . $simrs_path . "\r\n";
            $batch_content .= "echo ========================================\r\n";
            $batch_content .= "\r\n";
            $batch_content .= "REM Jalankan aplikasi dengan prioritas normal untuk mengurangi dampak pada sistem\r\n";
            $batch_content .= "cd /d \"" . $dir . "\"\r\n";
            $batch_content .= "start /B /NORMAL java -jar \"" . $simrs_path . "\"\r\n";
        }
        // File lainnya
        else {
            $batch_content .= "echo File: " . $simrs_path . "\r\n";
            $batch_content .= "echo ========================================\r\n";
            $batch_content .= "\r\n";
            $batch_content .= "REM Jalankan aplikasi dengan prioritas normal untuk mengurangi dampak pada sistem\r\n";
            $batch_content .= "start /B /NORMAL \"\" \"" . $simrs_path . "\"\r\n";
        }

        $batch_content .= "\r\n";
        $batch_content .= "echo ========================================\r\n";
        $batch_content .= "echo SIMRS telah dijalankan\r\n";
        $batch_content .= "echo Jendela ini akan tetap terbuka untuk menampilkan log\r\n";
        $batch_content .= "echo Tekan CTRL+C atau tutup jendela ini untuk keluar\r\n";
        $batch_content .= "echo ========================================\r\n";
        $batch_content .= "\r\n";
        $batch_content .= "REM Gunakan timeout daripada pause untuk mengurangi penggunaan CPU\r\n";
        $batch_content .= "timeout /t 86400 > nul\r\n";

        // Simpan ke file batch
        $batch_file = FCPATH . 'run_simrs.bat';
        write_file($batch_file, $batch_content);
    }
}
