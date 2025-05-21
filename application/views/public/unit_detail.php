<div class="row mb-4">
    <div class="col-md-12">
        <div class="page-header">
            <h2><i class="fas fa-hospital-alt"></i> Unit: <?= $unit->nama; ?></h2>
            <p class="text-muted">Detail informasi dan petugas di unit <?= $unit->nama; ?></p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('public_view'); ?>"><i class="fas fa-home"></i> Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Unit: <?= $unit->nama; ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Unit</h5>
                    <div>
                        <span class="text-muted">Terakhir diperbarui: <span class="last-updated font-weight-bold"></span></span>
                        <a href="<?= base_url('public_view/unit/' . $unit->id); ?>" class="btn btn-sm btn-primary ml-2 refresh-btn">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group">
                            <label>Nama Unit</label>
                            <h5><?= $unit->nama; ?></h5>
                        </div>

                        <div class="info-group">
                            <label>Deskripsi</label>
                            <p><?= $unit->deskripsi ? $unit->deskripsi : 'Tidak ada deskripsi'; ?></p>
                        </div>

                        <div class="info-group">
                            <label>Jumlah Petugas</label>
                            <h5><?= count($users); ?> Orang</h5>
                        </div>

                        <a href="<?= base_url('public_view'); ?>" class="btn btn-outline-primary mt-3">
                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    </div>
                    <div class="col-md-6">
                        <div class="unit-stats">
                            <?php
                            $active_count = 0;
                            foreach ($users as $user) {
                                if (isset($user->simrs_active) && $user->simrs_active) {
                                    $active_count++;
                                }
                            }
                            ?>
                            <div class="stats-item">
                                <div class="stats-icon bg-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stats-info">
                                    <h3><?= count($users); ?></h3>
                                    <p>Total Petugas</p>
                                </div>
                            </div>

                            <div class="stats-item">
                                <div class="stats-icon bg-success">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="stats-info">
                                    <h3><?= $active_count; ?></h3>
                                    <p>Petugas Aktif</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-user-md"></i> Petugas Aktif</h5>
            </div>
            <div class="card-body">
                <?php
                $active_user = null;
                foreach ($users as $user) {
                    if (isset($user->simrs_active) && $user->simrs_active) {
                        $active_user = $user;
                        break;
                    }
                }
                ?>

                <?php if ($active_user): ?>
                <div class="active-user-card">
                    <div class="user-avatar">
                        <?php if (!empty($active_user->photo)): ?>
                        <img src="<?= base_url('uploads/photos/' . $active_user->photo); ?>" alt="Foto Profil">
                        <?php else: ?>
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                        <?php endif; ?>
                        <span class="status-indicator"></span>
                    </div>

                    <div class="user-info">
                        <h5><?= $active_user->full_name; ?></h5>
                        <p class="user-title"><?= $unit->nama; ?></p>

                        <div class="user-status">
                            <span class="badge badge-success status-badge">
                                <i class="fas fa-check-circle"></i> Aktif
                            </span>
                        </div>

                        <?php if (!empty($active_user->contact)): ?>
                        <div class="user-contact">
                            <i class="fas fa-phone-alt"></i> <?= $active_user->contact; ?>
                        </div>
                        <?php endif; ?>

                        <div class="user-time">
                            <i class="far fa-clock"></i> Aktif sejak:
                            <?php if (isset($active_user->last_activity)): ?>
                            <?= date('d-m-Y H:i:s', strtotime($active_user->last_activity)); ?>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="no-active-user">
                    <i class="fas fa-user-slash"></i>
                    <p>Tidak ada petugas yang sedang aktif menggunakan SIMRS di unit ini.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-list"></i> Daftar Petugas</h5>
            </div>
            <div class="card-body">
                <?php if (empty($users)): ?>
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x mr-3"></i>
                        <p class="mb-0">Tidak ada petugas yang terdaftar di unit ini.</p>
                    </div>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Kontak</th>
                                <th>Status</th>
                                <th>Terakhir Aktif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($users as $user): ?>
                            <tr class="<?= (isset($user->simrs_active) && $user->simrs_active) ? 'table-success' : ''; ?>">
                                <td><?= $no++; ?></td>
                                <td>
                                    <?php if (!empty($user->photo)): ?>
                                    <img src="<?= base_url('uploads/photos/' . $user->photo); ?>" alt="Foto" class="table-user-photo">
                                    <?php else: ?>
                                    <div class="table-photo-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= $user->full_name; ?></td>
                                <td><?= $user->username; ?></td>
                                <td><?= $user->email; ?></td>
                                <td><?= !empty($user->contact) ? $user->contact : '-'; ?></td>
                                <td>
                                    <?php if (isset($user->simrs_active) && $user->simrs_active): ?>
                                    <span class="badge badge-success status-badge">
                                        <i class="fas fa-check-circle"></i> Aktif
                                    </span>
                                    <?php else: ?>
                                    <span class="badge badge-secondary status-badge">
                                        <i class="fas fa-times-circle"></i> Tidak Aktif
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($user->last_activity) && $user->last_activity): ?>
                                    <span class="timestamp"><?= date('d-m-Y H:i:s', strtotime($user->last_activity)); ?></span>
                                    <?php else: ?>
                                    <span class="timestamp">-</span>
                                    <?php endif; ?>
                                </td>
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

<style>
    /* Breadcrumb */
    .breadcrumb {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 15px 20px;
    }

    .breadcrumb-item a {
        color: var(--primary);
        font-weight: 500;
    }

    .breadcrumb-item.active {
        color: var(--dark);
        font-weight: 500;
    }

    /* Info Group */
    .info-group {
        margin-bottom: 20px;
    }

    .info-group label {
        display: block;
        color: var(--gray);
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .info-group h5 {
        color: var(--dark);
        font-weight: 600;
        margin: 0;
    }

    /* Unit Stats */
    .unit-stats {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .stats-item {
        display: flex;
        align-items: center;
        background-color: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        margin-right: 15px;
    }

    .bg-primary {
        background-color: var(--primary);
    }

    .bg-success {
        background-color: var(--success);
    }

    .stats-info h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        color: var(--dark);
    }

    .stats-info p {
        margin: 0;
        color: var(--gray);
    }

    /* Active User Card */
    .active-user-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .user-avatar {
        position: relative;
        margin-bottom: 15px;
    }

    .user-avatar img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--success);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: var(--gray);
        border: 3px solid var(--gray);
    }

    .status-indicator {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: var(--success);
        border: 3px solid white;
        animation: pulse 2s infinite;
    }

    .user-info h5 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .user-title {
        color: var(--primary);
        font-weight: 500;
        margin-bottom: 10px;
    }

    .user-status {
        margin-bottom: 10px;
    }

    .user-contact, .user-time {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray);
        font-size: 0.9rem;
        margin-top: 5px;
    }

    .user-contact i, .user-time i {
        margin-right: 5px;
        color: var(--primary);
    }

    .no-active-user {
        text-align: center;
        padding: 30px 20px;
        color: var(--gray);
    }

    .no-active-user i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* Table */
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: var(--dark);
        border-top: none;
    }

    .table-success {
        background-color: rgba(52, 168, 83, 0.1) !important;
    }

    .table-user-photo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .table-photo-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: var(--gray);
    }
</style>
