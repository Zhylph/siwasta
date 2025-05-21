@echo on
title SIMRS Monitor - Log Window
color 0A
echo ========================================
echo Menjalankan SIMRS...
echo Jendela ini akan tetap terbuka untuk menampilkan log
echo ========================================

echo Direktori: F:\App\SIMRS-Monarch
echo File: F:\App\SIMRS-Monarch\aplikasi.bat
echo ========================================

REM Jalankan aplikasi dengan prioritas normal untuk mengurangi dampak pada sistem
cd /d "F:\App\SIMRS-Monarch"
start /B /NORMAL call "F:\App\SIMRS-Monarch\aplikasi.bat"

echo ========================================
echo SIMRS telah dijalankan
echo Jendela ini akan tetap terbuka untuk menampilkan log
echo Tekan CTRL+C atau tutup jendela ini untuk keluar
echo ========================================

REM Gunakan timeout daripada pause untuk mengurangi penggunaan CPU
timeout /t 86400 > nul
