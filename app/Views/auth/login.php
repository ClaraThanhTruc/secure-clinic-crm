<div style="max-width: 400px; margin: 50px auto; padding: 25px; border: 1px solid #dee2e6; border-radius: 8px; background: #fff;">
    <h3 style="text-align: center; margin-top: 0;">🔐 ĐĂNG NHẬP HỆ THỐNG</h3>
    
    <?php if (isset($errors['general'])): ?>
        <div class="alert alert-danger"><?= e($errors['general']) ?></div>
    <?php endif; ?>

    <form method="POST" action="/login">
        <div class="form-group">
            <label>Email Nhân Viên:</label>
            <input type="email" name="email" class="form-control" value="<?= e(old('email')) ?>" required placeholder="admin@clinic.com">
        </div>
        <div class="form-group">
            <label>Mật Khẩu:</label>
            <input type="password" name="password" class="form-control" required placeholder="******">
        </div>
        <button type="submit" class="btn" style="width: 100%; padding: 10px;">Xác Thực Đăng Nhập</button>
    </form>
    <p style="text-align: center; font-size: 12px; color: #6c757d; margin-top: 15px;">Tài khoản test: admin@clinic.com / Mật khẩu: 123456</p>
</div>