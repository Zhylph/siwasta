<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Profil Pengguna</h5>
            </div>
            <div class="card-body text-center">
                <?php if (!empty($user['photo'])): ?>
                <img src="<?= base_url('uploads/photos/' . $user['photo']); ?>" class="rounded-circle mb-3" alt="Profile Picture" style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Profile Picture">
                <?php endif; ?>
                <h4><?= $user['full_name']; ?></h4>
                <p class="text-muted">@<?= $user['username']; ?></p>
                <p><i class="fas fa-envelope"></i> <?= $user['email']; ?></p>
                <hr>
                <div class="text-left">
                    <p><strong>Username:</strong> <?= $user['username']; ?></p>
                    <p><strong>Email:</strong> <?= $user['email']; ?></p>
                    <p><strong>Nama Lengkap:</strong> <?= $user['full_name']; ?></p>
                    <p><strong>Unit Kerja:</strong> <?= isset($user['unit_kerja_nama']) ? $user['unit_kerja_nama'] : 'Belum diatur'; ?></p>
                    <p><strong>Kontak:</strong> <?= isset($user['contact']) && !empty($user['contact']) ? $user['contact'] : 'Belum diatur'; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Edit Profil</h5>
            </div>
            <div class="card-body">
                <?= form_open_multipart('dashboard/update_profile'); ?>
                    <div class="form-group">
                        <label for="full_name">Nama Lengkap</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?= $user['full_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $user['email']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= $user['username']; ?>" readonly>
                        <small class="form-text text-muted">Username tidak dapat diubah.</small>
                    </div>
                    <div class="form-group">
                        <label for="unit_kerja_id">Unit Kerja</label>
                        <select class="form-control" id="unit_kerja_id" name="unit_kerja_id">
                            <option value="">-- Pilih Unit Kerja --</option>
                            <?php foreach ($unit_kerja as $unit): ?>
                            <option value="<?= $unit->id ?>" <?= (isset($user['unit_kerja_id']) && $user['unit_kerja_id'] == $unit->id) ? 'selected' : ''; ?>><?= $unit->nama ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Pilih unit kerja Anda</small>
                    </div>
                    <div class="form-group">
                        <label for="contact">Kontak (Telepon/Extension)</label>
                        <input type="text" class="form-control" id="contact" name="contact" value="<?= isset($user['contact']) ? $user['contact'] : ''; ?>" placeholder="Masukkan nomor telepon atau extension">
                        <small class="form-text text-muted">Nomor telepon atau extension yang dapat dihubungi</small>
                    </div>
                    <div class="form-group">
                        <label for="photo">Foto Profil</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*">
                            <label class="custom-file-label" for="photo">Pilih file foto</label>
                        </div>
                        <small class="form-text text-muted">Upload foto profil Anda (maks. 2MB, format: jpg, jpeg, png, gif)</small>
                        <?php if (!empty($user['photo'])): ?>
                        <div class="mt-2">
                            <img src="<?= base_url('uploads/photos/' . $user['photo']); ?>" alt="Current Photo" class="img-thumbnail" style="max-height: 100px;">
                            <p class="small text-muted">Foto saat ini</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <h5>Ubah Password</h5>
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
// Script untuk menampilkan nama file yang dipilih pada custom file input
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
});
</script>
