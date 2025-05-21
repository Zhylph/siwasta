# Aplikasi Sign On untuk SIMRS

Aplikasi ini digunakan sebagai jembatan untuk membuka aplikasi desktop SIMRS Monarch dan mencatat aktivitas penggunaan di database.

## Fitur

- Login dan registrasi pengguna
- Menjalankan aplikasi desktop SIMRS Monarch
- Mencatat aktivitas penggunaan SIMRS di database
- Dashboard admin untuk melihat statistik dan daftar pengguna aktif
- Berbagai metode untuk menjalankan aplikasi SIMRS

## Persyaratan

- PHP 5.6 atau lebih tinggi
- MySQL 5.6 atau lebih tinggi
- Web server (Apache/Nginx)
- Browser modern (Chrome, Firefox, Edge)

## Instalasi

1. Clone atau download repository ini ke direktori web server Anda
2. Buat database baru dengan nama `signon_db`
3. Import file `database.sql` ke database yang telah dibuat
4. Konfigurasi koneksi database di `application/config/database.php`
5. Akses aplikasi melalui browser di `http://localhost/signon/`

## Cara Menjalankan SIMRS

Aplikasi ini menyediakan cara mudah untuk menjalankan aplikasi desktop SIMRS:

1. Klik tombol "Jalankan SIMRS Langsung" di dashboard atau halaman SIMRS
2. Aplikasi SIMRS akan dijalankan menggunakan konfigurasi yang tersimpan
3. Jendela Command Prompt (CMD) akan tetap terbuka untuk menampilkan log dan pesan error
4. Aktivitas penggunaan SIMRS akan dicatat di database

Jika aplikasi SIMRS tidak dapat ditemukan atau tidak dapat dijalankan, Anda dapat mengkonfigurasi lokasi aplikasi SIMRS melalui halaman "Konfigurasi SIMRS".

### Fitur Jendela CMD

Aplikasi ini dirancang untuk mempertahankan jendela CMD tetap terbuka saat menjalankan SIMRS. Fitur ini berguna untuk:

1. **Debugging**: Melihat pesan error jika terjadi masalah dengan aplikasi SIMRS
2. **Monitoring**: Memantau aktivitas aplikasi SIMRS melalui log yang ditampilkan
3. **Troubleshooting**: Memudahkan penyelesaian masalah jika aplikasi SIMRS tidak berjalan dengan benar

Jendela CMD akan tetap terbuka hingga Anda menutupnya secara manual atau menekan CTRL+C.

## Konfigurasi Path SIMRS

Aplikasi ini menyediakan beberapa cara untuk mengkonfigurasi lokasi aplikasi SIMRS:

1. **Konfigurasi Otomatis**: Aplikasi akan mencoba mencari aplikasi SIMRS di beberapa lokasi umum.
2. **Konfigurasi Manual**: Gunakan tombol "Konfigurasi SIMRS" di halaman SIMRS untuk mengatur lokasi aplikasi secara manual.

Lokasi yang dicari secara otomatis:
- `F:\App\SIMRS-Monarch\aplikasi.bat`
- `C:\SIMRS\aplikasi.bat`
- `C:\Program Files\SIMRS\aplikasi.bat`
- `C:\Program Files (x86)\SIMRS\aplikasi.bat`
- `D:\SIMRS\aplikasi.bat`
- `F:\App\SIMRS-Monarch\SIMRS.jar`
- `C:\SIMRS\SIMRS.jar`
- `C:\Program Files\SIMRS\SIMRS.jar`
- `C:\Program Files (x86)\SIMRS\SIMRS.jar`
- `D:\SIMRS\SIMRS.jar`

Jika aplikasi SIMRS tidak ditemukan di lokasi-lokasi tersebut, Anda dapat menggunakan file `configure_simrs.bat` untuk mengkonfigurasi lokasi secara manual.

## Keamanan

Aplikasi ini menggunakan beberapa metode yang mungkin diblokir oleh browser atau sistem operasi karena alasan keamanan. Pastikan untuk mengkonfigurasi browser dan sistem operasi Anda dengan benar jika ingin menggunakan semua metode yang disediakan.

## Lisensi

MIT License
# siwasta
