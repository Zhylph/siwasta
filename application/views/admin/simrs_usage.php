<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-list"></i> Daftar Penggunaan SIMRS</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <p><i class="fas fa-info-circle"></i> Berikut adalah daftar pengguna yang sedang menggunakan SIMRS saat ini.</p>
                </div>
                
                <?php if (empty($active_usage)): ?>
                <div class="alert alert-warning">
                    <p>Tidak ada pengguna yang sedang menggunakan SIMRS saat ini.</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Waktu Mulai</th>
                                <th>Durasi</th>
                                <th>IP Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            foreach ($active_usage as $usage): 
                                // Menghitung durasi
                                $start_time = new DateTime($usage->start_time);
                                $now = new DateTime();
                                $interval = $start_time->diff($now);
                                
                                if ($interval->h > 0) {
                                    $duration = $interval->h . ' jam ' . $interval->i . ' menit';
                                } else {
                                    $duration = $interval->i . ' menit ' . $interval->s . ' detik';
                                }
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $usage->username; ?></td>
                                <td><?= $usage->full_name; ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($usage->start_time)); ?></td>
                                <td><?= $duration; ?></td>
                                <td><?= $usage->ip_address; ?></td>
                                <td><span class="badge badge-success">Aktif</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
                <div class="mt-3">
                    <a href="<?= base_url('admin'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto refresh halaman setiap 30 detik
    setTimeout(function() {
        location.reload();
    }, 30000);
});
</script>
