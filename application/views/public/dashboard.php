<div class="row mb-4">
    <div class="col-md-12">
        <div class="page-header">
            <h2><i class="fas fa-hospital-user"></i> Status Petugas SIMRS</h2>
            <p class="text-muted">Informasi petugas yang sedang aktif menggunakan SIMRS di setiap unit kerja</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi &nbsp</h5>
                    <div>
                        <span class="text">Terakhir diperbarui: <span class="last-updated font-weight-bold"></span></span>
                        <a href="<?= base_url('public_view'); ?>" class="btn btn-sm btn-primary ml-2 refresh-btn">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p><i class="fas fa-check-circle text-success"></i> Dashboard ini menampilkan status petugas yang sedang aktif menggunakan SIMRS di setiap unit kerja.</p>
                        <p><i class="fas fa-sync text-primary"></i> Halaman ini akan diperbarui secara otomatis setiap 30 detik.</p>
                        <p><i class="fas fa-phone-alt text-info"></i> Anda dapat menghubungi petugas yang sedang aktif melalui nomor kontak yang tersedia.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="stats-box">
                            <div class="stats-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stats-info">
                                <h3><?= count($active_users); ?></h3>
                                <p>Unit Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (empty($active_users)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-3x mr-3"></i>
                <div>
                    <h5 class="alert-heading">Tidak Ada Petugas Aktif</h5>
                    <p class="mb-0">Saat ini tidak ada petugas yang sedang aktif menggunakan SIMRS.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <?php foreach ($active_users as $unit_id => $unit): ?>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card unit-card">
            <div class="card-header">
                <h5><i class="fas fa-hospital-alt"></i> <?= $unit['nama']; ?></h5>
            </div>
            <div class="card-body">
                <?php if (!isset($unit['active_user'])): ?>
                <div class="unit-empty">
                    <i class="fas fa-user-slash fa-3x mb-3"></i>
                    <p>Tidak ada petugas yang sedang menggunakan SIMRS di unit ini.</p>
                </div>
                <?php else: ?>
                <div class="user-info">
                    <?php if (!empty($unit['active_user']['photo'])): ?>
                    <img src="<?= base_url('uploads/photos/' . $unit['active_user']['photo']); ?>" alt="Foto Profil" class="user-photo">
                    <?php else: ?>
                    <div class="user-photo-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                    <?php endif; ?>

                    <div class="user-details">
                        <h5><?= $unit['active_user']['full_name']; ?></h5>
                        <p class="mb-1">
                            <span class="badge badge-primary status-badge">
                                <i class="fas fa-check-circle"></i> Aktif
                            </span>
                        </p>
                        <?php if (!empty($unit['active_user']['contact'])): ?>
                        <p class="contact-info mb-1">
                            <i class="fab fa-whatsapp"></i>
                            <a href="<?= 'https://wa.me/' . formatWhatsAppNumber($unit['active_user']['contact']); ?>" target="_blank" class="whatsapp-link">
                                <?= $unit['active_user']['contact']; ?>
                            </a>
                        </p>
                        <?php endif; ?>
                        <p class="timestamp">
                            <i class="far fa-clock"></i> Aktif sejak: <?= date('d-m-Y H:i:s', strtotime($unit['active_user']['start_time'])); ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<style>
    .page-header h2 {
        color: var(--teal);
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .page-header h2 i {
        color: var(--teal);
        margin-right: 15px;
    }

    .stats-box {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(26, 142, 131, 0.1);
        padding: 20px;
        border-radius: 10px;
    }

    .stats-icon {
        font-size: 2.5rem;
        color: var(--teal);
        margin-right: 15px;
    }

    .stats-info h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--teal);
        margin: 0;
    }

    .stats-info p {
        margin: 0;
        color: var(--gray);
    }

    .alert {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .alert-warning {
        background-color: rgba(251, 188, 5, 0.1);
        color: #856404;
    }

    .alert-warning .alert-heading {
        color: var(--warning);
    }

    .alert-warning i {
        color: var(--warning);
    }

    /* WhatsApp Link Styles */
    .whatsapp-link {
        color: #25D366;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }

    .whatsapp-link:hover {
        color: #128C7E;
        text-decoration: none;
    }

    .contact-info i.fab.fa-whatsapp {
        color: #25D366;
    }

</style>
