<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Aplikasi Sign On</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .register-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .register-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-logo h2 {
            font-weight: bold;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-register {
            background-color: #28a745;
            border-color: #28a745;
            width: 100%;
            font-weight: bold;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-logo">
                <h2>Daftar Akun</h2>
            </div>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?= form_open('auth/process_register'); ?>
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Masukkan nama lengkap" value="<?= set_value('full_name'); ?>">
                    <?= form_error('full_name', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" value="<?= set_value('username'); ?>">
                    <?= form_error('username', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" value="<?= set_value('email'); ?>">
                    <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
                    <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password">
                    <?= form_error('confirm_password', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="unit_kerja_id">Unit Kerja</label>
                    <select class="form-control" id="unit_kerja_id" name="unit_kerja_id">
                        <option value="">-- Pilih Unit Kerja --</option>
                        <?php foreach ($unit_kerja as $unit): ?>
                        <option value="<?= $unit->id ?>" <?= set_select('unit_kerja_id', $unit->id); ?>><?= $unit->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('unit_kerja_id', '<small class="text-danger">', '</small>'); ?>
                    <small class="form-text text-muted">Pilih unit kerja Anda</small>
                </div>
                <button type="submit" class="btn btn-success btn-register">Daftar</button>
            <?= form_close(); ?>

            <div class="login-link">
                <p>Sudah punya akun? <a href="<?= base_url('auth'); ?>">Login disini</a></p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
