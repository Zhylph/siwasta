<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="fas fa-cog"></i> Konfigurasi SIMRS</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <p><i class="fas fa-info-circle"></i> Halaman ini digunakan untuk mengkonfigurasi lokasi aplikasi SIMRS.</p>
                    <p>Setelah konfigurasi disimpan, Anda dapat menjalankan SIMRS langsung dari browser.</p>
                    <p><i class="fas fa-terminal"></i> <strong>Jendela Command Prompt (CMD) akan tetap terbuka</strong> saat menjalankan SIMRS untuk menampilkan log dan pesan error. Jendela ini berguna untuk debugging jika terjadi masalah dengan aplikasi SIMRS.</p>
                </div>

                <?= form_open('config/save_simrs_config'); ?>
                    <div class="form-group">
                        <label for="simrs_path"><strong>Path Aplikasi SIMRS:</strong></label>
                        <input type="text" class="form-control" id="simrs_path" name="simrs_path" value="<?= $config['simrs_path']; ?>" required>
                        <small class="form-text text-muted">Masukkan path lengkap ke aplikasi SIMRS (contoh: F:\App\SIMRS-Monarch\aplikasi.bat)</small>
                    </div>

                    <div class="form-group">
                        <label><strong>Lokasi Umum:</strong></label>
                        <div class="list-group">
                            <?php foreach ($common_locations as $location): ?>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action location-item" data-path="<?= $location; ?>">
                                <?= $location; ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <small class="form-text text-muted">Klik salah satu lokasi di atas untuk menggunakannya</small>
                    </div>

                    <div class="form-group">
                        <label><strong>Informasi Konfigurasi:</strong></label>
                        <div class="card">
                            <div class="card-body">
                                <p><strong>Terakhir diperbarui:</strong> <?= isset($config['last_updated']) ? $config['last_updated'] : 'Belum pernah'; ?></p>
                                <p><strong>Diperbarui oleh:</strong> <?= isset($config['updated_by']) ? $config['updated_by'] : '-'; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Konfigurasi
                        </button>
                        <a href="<?= base_url('config/run_simrs'); ?>" class="btn btn-success">
                            <i class="fas fa-play-circle"></i> Jalankan SIMRS
                        </a>
                        <a href="<?= base_url('dashboard/simrs'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Ketika lokasi diklik
    $('.location-item').click(function() {
        var path = $(this).data('path');
        $('#simrs_path').val(path);
    });
});
</script>
