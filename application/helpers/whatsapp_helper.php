<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * WhatsApp Helper
 *
 * Fungsi-fungsi untuk memformat nomor telepon untuk WhatsApp
 */

if (!function_exists('formatWhatsAppNumber')) {
    /**
     * Format nomor telepon untuk WhatsApp
     * 
     * @param string $phone Nomor telepon yang akan diformat
     * @return string Nomor telepon yang sudah diformat untuk WhatsApp
     */
    function formatWhatsAppNumber($phone) {
        // Hapus semua karakter non-numerik
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Jika nomor dimulai dengan 0, ganti dengan 62 (kode negara Indonesia)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Jika nomor belum memiliki kode negara, tambahkan 62 (kode negara Indonesia)
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }
}
