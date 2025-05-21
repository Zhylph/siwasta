<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Dashboard</h5>
            </div>
            <div class="card-body">
                <h2>Selamat Datang, <?= $user['full_name']; ?>!</h2>
                <p>Anda telah berhasil login ke Aplikasi Sign On.</p>

                <div class="text-center my-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('dashboard/simrs'); ?>" class="btn btn-lg btn-success btn-block">
                                <i class="fas fa-hospital"></i> Halaman SIMRS
                            </a>
                            <p class="text-muted mt-2">Buka halaman SIMRS dengan berbagai metode</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="<?= base_url('config/run_simrs'); ?>" class="btn btn-lg btn-primary btn-block">
                                <i class="fas fa-play-circle"></i> Jalankan SIMRS Langsung
                            </a>
                            <p class="text-muted mt-2">Jalankan SIMRS langsung tanpa halaman tambahan</p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-user"></i> Profil</h5>
                                <p class="card-text">Lihat dan edit profil Anda</p>
                                <a href="<?= base_url('dashboard/profile'); ?>" class="btn btn-light">Lihat Profil</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-shield-alt"></i> Keamanan</h5>
                                <p class="card-text">Kelola keamanan akun Anda</p>
                                <a href="#" class="btn btn-light">Pengaturan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-history"></i> Aktivitas</h5>
                                <p class="card-text">Lihat riwayat aktivitas login</p>
                                <a href="#" class="btn btn-light">Lihat Aktivitas</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
