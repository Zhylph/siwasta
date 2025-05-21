<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5><i class="fas fa-hospital"></i> SIMRS - Sistem Informasi Manajemen Rumah Sakit</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <img src="https://via.placeholder.com/150?text=SIMRS" class="img-fluid mb-3" alt="SIMRS Logo" style="max-width: 200px;">
                    <h3>SIMRS Monarch</h3>
                    <p class="text-muted">Sistem Informasi Manajemen Rumah Sakit</p>
                </div>

                <div class="alert alert-info">
                    <p><i class="fas fa-info-circle"></i> Klik tombol di bawah untuk menjalankan aplikasi SIMRS.</p>
                    <p>Aplikasi akan berjalan di komputer Anda dan aktivitas penggunaan akan dicatat.</p>
                    <p><strong>Catatan:</strong> Jika aplikasi SIMRS tidak ditemukan, gunakan tombol "Konfigurasi SIMRS" di bawah untuk mengatur lokasi aplikasi.</p>
                    <p><i class="fas fa-terminal"></i> <strong>Jendela Command Prompt (CMD) akan tetap terbuka</strong> untuk menampilkan log dan pesan error. Jendela ini berguna untuk debugging jika terjadi masalah dengan aplikasi SIMRS.</p>
                </div>

                <?php if(isset($active_user_warning)): ?>
                <div class="alert alert-warning">
                    <h5><i class="fas fa-exclamation-triangle"></i> Perhatian!</h5>
                    <p>Saat ini sudah ada pengguna lain dari unit kerja <strong><?= $active_user_warning['unit_kerja']; ?></strong> yang sedang menggunakan SIMRS:</p>
                    <ul>
                        <li><strong>Nama:</strong> <?= $active_user_warning['user_name']; ?></li>
                        <li><strong>Aktif sejak:</strong> <?= date('d-m-Y H:i:s', strtotime($active_user_warning['start_time'])); ?></li>
                    </ul>
                    <p>Jika Anda melanjutkan, sesi pengguna tersebut akan ditutup dan hanya Anda yang akan memiliki akses aktif ke SIMRS dari unit kerja ini.</p>
                </div>
                <?php endif; ?>

                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <a href="<?= base_url('config/run_simrs'); ?>" class="btn btn-lg btn-success btn-block">
                                <i class="fas fa-play-circle"></i> Jalankan SIMRS Langsung
                            </a>
                            <p class="text-muted mt-2">Jalankan SIMRS dengan konfigurasi yang tersimpan</p>
                            <?php if(isset($user['unit_kerja_nama'])): ?>
                            <p class="text-info"><i class="fas fa-info-circle"></i> Anda akan menjalankan SIMRS sebagai petugas unit <strong><?= $user['unit_kerja_nama']; ?></strong></p>
                            <?php else: ?>
                            <p class="text-warning"><i class="fas fa-exclamation-circle"></i> Anda belum memiliki unit kerja. Silahkan update profil Anda.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <p><i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success'); ?></p>
                </div>
                <?php endif; ?>

                <div id="simrs-status" class="d-none alert alert-success mt-3">
                    <p><i class="fas fa-check-circle"></i> <span id="status-message">SIMRS sedang berjalan...</span></p>
                    <p>Jika aplikasi tidak terbuka secara otomatis, periksa konfigurasi SIMRS.</p>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="fas fa-history"></i> Riwayat Penggunaan SIMRS</h5>
            </div>
            <div class="card-body">
                <?php if (empty($simrs_history)): ?>
                <div class="alert alert-info">
                    <p>Belum ada riwayat penggunaan SIMRS.</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Status</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($simrs_history as $history): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($history->start_time)); ?></td>
                                <td><?= ($history->end_time) ? date('d-m-Y H:i:s', strtotime($history->end_time)) : '-'; ?></td>
                                <td>
                                    <?php if ($history->status == 'active'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                    <span class="badge badge-secondary">Selesai</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $history->ip_address; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto refresh halaman setiap 30 detik untuk memperbarui riwayat dan status
    setTimeout(function() {
        location.reload();
    }, 30000);

    // Tambahkan konfirmasi saat menjalankan SIMRS jika ada peringatan user aktif
    <?php if(isset($active_user_warning)): ?>
    $('a[href="<?= base_url('config/run_simrs'); ?>"]').on('click', function(e) {
        e.preventDefault();

        if (confirm('Perhatian! Saat ini sudah ada pengguna lain (<?= $active_user_warning['user_name']; ?>) dari unit kerja <?= $active_user_warning['unit_kerja']; ?> yang sedang menggunakan SIMRS.\n\nJika Anda melanjutkan, sesi pengguna tersebut akan ditutup dan hanya Anda yang akan memiliki akses aktif ke SIMRS dari unit kerja ini.\n\nApakah Anda yakin ingin melanjutkan?')) {
            window.location.href = '<?= base_url('config/run_simrs'); ?>';
        }
    });
    <?php endif; ?>
});
</script>
