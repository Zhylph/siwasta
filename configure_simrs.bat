@echo off
title Konfigurasi SIMRS
color 0A
cls

echo =====================================================
echo             KONFIGURASI LOKASI SIMRS
echo =====================================================
echo.
echo Program ini akan membantu Anda mengkonfigurasi lokasi aplikasi SIMRS.
echo.

:MENU
echo Pilih opsi:
echo [1] Cari aplikasi SIMRS secara otomatis
echo [2] Tentukan lokasi aplikasi SIMRS secara manual
echo [3] Keluar
echo.
set /p "CHOICE=Pilihan Anda (1-3): "

if "%CHOICE%"=="1" goto :AUTO_SEARCH
if "%CHOICE%"=="2" goto :MANUAL_CONFIG
if "%CHOICE%"=="3" goto :EXIT
echo Pilihan tidak valid. Silakan coba lagi.
goto :MENU

:AUTO_SEARCH
echo.
echo Mencari aplikasi SIMRS...
echo.

REM Daftar kemungkinan lokasi
set "LOCATIONS=F:\App\SIMRS-Monarch C:\SIMRS C:\Program Files\SIMRS C:\Program Files (x86)\SIMRS D:\SIMRS E:\SIMRS"
set "FOUND=0"

REM Cek setiap lokasi
for %%L in (%LOCATIONS%) do (
    if exist "%%L\aplikasi.bat" (
        echo Aplikasi SIMRS ditemukan di: %%L\aplikasi.bat
        set "SIMRS_PATH=%%L\aplikasi.bat"
        set "FOUND=1"
        goto :SAVE_CONFIG
    )
    
    if exist "%%L\SIMRS.jar" (
        echo File SIMRS.jar ditemukan di: %%L\SIMRS.jar
        set "SIMRS_PATH=%%L\SIMRS.jar"
        set "FOUND=1"
        goto :SAVE_CONFIG
    )
)

if "%FOUND%"=="0" (
    echo Aplikasi SIMRS tidak ditemukan di lokasi yang umum.
    echo.
    echo Lokasi yang dicari:
    for %%L in (%LOCATIONS%) do (
        echo - %%L\aplikasi.bat
        echo - %%L\SIMRS.jar
    )
    echo.
    echo Silakan tentukan lokasi secara manual.
    pause
    goto :MANUAL_CONFIG
)

:MANUAL_CONFIG
echo.
echo Masukkan lokasi lengkap aplikasi SIMRS:
echo Contoh: F:\App\SIMRS-Monarch\aplikasi.bat atau C:\SIMRS\SIMRS.jar
echo.
set /p "SIMRS_PATH=Path: "

if not exist "%SIMRS_PATH%" (
    echo.
    echo File tidak ditemukan: %SIMRS_PATH%
    echo Silakan coba lagi.
    pause
    goto :MANUAL_CONFIG
)

:SAVE_CONFIG
echo.
echo Menyimpan konfigurasi...

REM Simpan konfigurasi ke file
echo @echo off > simrs_config.bat
echo REM Konfigurasi SIMRS >> simrs_config.bat
echo set "SIMRS_PATH=%SIMRS_PATH%" >> simrs_config.bat

REM Update run_simrs.bat
echo @echo off > run_simrs.bat
echo echo Menjalankan SIMRS... >> run_simrs.bat
echo. >> run_simrs.bat
echo call simrs_config.bat >> run_simrs.bat
echo. >> run_simrs.bat
echo if exist "%%SIMRS_PATH%%" ( >> run_simrs.bat
echo     echo File SIMRS ditemukan, menjalankan... >> run_simrs.bat
echo     cd /d "%%SIMRS_PATH%%\.." >> run_simrs.bat
echo     if "%%SIMRS_PATH:~-4%%"==".bat" ( >> run_simrs.bat
echo         call "%%SIMRS_PATH%%" >> run_simrs.bat
echo     ) else if "%%SIMRS_PATH:~-4%%"==".jar" ( >> run_simrs.bat
echo         java -jar "%%SIMRS_PATH%%" >> run_simrs.bat
echo     ) else ( >> run_simrs.bat
echo         start "" "%%SIMRS_PATH%%" >> run_simrs.bat
echo     ) >> run_simrs.bat
echo ) else ( >> run_simrs.bat
echo     echo File SIMRS tidak ditemukan: %%SIMRS_PATH%% >> run_simrs.bat
echo     echo Jalankan configure_simrs.bat untuk mengkonfigurasi ulang. >> run_simrs.bat
echo     pause >> run_simrs.bat
echo ) >> run_simrs.bat
echo. >> run_simrs.bat
echo exit >> run_simrs.bat

echo.
echo Konfigurasi berhasil disimpan!
echo Path SIMRS: %SIMRS_PATH%
echo.
echo Apakah Anda ingin menjalankan SIMRS sekarang? (Y/N)
set /p "RUN_NOW=Pilihan Anda: "

if /i "%RUN_NOW%"=="Y" (
    echo.
    echo Menjalankan SIMRS...
    call run_simrs.bat
) else (
    echo.
    echo Anda dapat menjalankan SIMRS nanti dengan mengklik tombol di aplikasi web.
)

:EXIT
echo.
echo Terima kasih telah menggunakan konfigurasi SIMRS.
echo.
pause
exit
