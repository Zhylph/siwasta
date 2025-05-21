<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h5>
            </div>
            <div class="card-body">
                <h2>Selamat Datang di Panel Admin</h2>
                <p>Anda dapat mengelola dan memantau penggunaan SIMRS dari sini.</p>
                <hr>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h1><?= $simrs_stats['total']; ?></h1>
                                <h5>Total Penggunaan SIMRS</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h1><?= $simrs_stats['today']; ?></h1>
                                <h5>Penggunaan Hari Ini</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h1><?= $simrs_stats['active']; ?></h1>
                                <h5>Penggunaan Aktif</h5>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="<?= base_url('admin/simrs_usage'); ?>" class="btn btn-primary">
                        <i class="fas fa-list"></i> Lihat Daftar Penggunaan SIMRS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
