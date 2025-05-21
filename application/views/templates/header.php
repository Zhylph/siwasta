<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Aplikasi Sign On</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: bold;
        }
        .content-wrapper {
            padding: 30px 0;
        }
        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            font-weight: bold;
        }
        .dropdown-menu {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('dashboard'); ?>">Aplikasi Sign On</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item <?= ($title == 'Dashboard') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item <?= ($title == 'SIMRS') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url('dashboard/simrs'); ?>">
                            <i class="fas fa-hospital"></i> SIMRS
                        </a>
                    </li>
                    <?php
                    // Tampilkan menu konfigurasi hanya untuk user dengan unit kerja Admin
                    $unit_kerja_id = $this->session->userdata('unit_kerja_id');
                    $unit_kerja = null;
                    if ($unit_kerja_id) {
                        $unit_kerja = $this->unit_kerja_model->get_by_id($unit_kerja_id);
                    }
                    if ($unit_kerja && strtolower($unit_kerja->nama) === 'admin'):
                    ?>
                    <li class="nav-item <?= ($title == 'Konfigurasi SIMRS') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url('config/simrs'); ?>">
                            <i class="fas fa-cog"></i> Konfigurasi
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php
                    // Tampilkan menu admin hanya untuk user dengan unit kerja Admin
                    if ($unit_kerja && strtolower($unit_kerja->nama) === 'admin'):
                    ?>
                    <li class="nav-item <?= (strpos($title, 'Admin') !== false) ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?= base_url('admin'); ?>">
                            <i class="fas fa-cogs"></i> Admin
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?= $user['username']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?= base_url('dashboard/profile'); ?>">
                                <i class="fas fa-user"></i> Profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content-wrapper">
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
