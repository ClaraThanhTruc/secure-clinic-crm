<h2>📝 Chỉnh Sửa Hồ Sơ Bệnh Nhân</h2>
<form method="POST" action="/patients/update" style="max-width: 600px;">
    <input type="hidden" name="id" value="<?= e((string)$patient['id']) ?>">
    <div class="form-group">
        <label>Họ và Tên Bệnh Nhân <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="<?= e(old('name', $patient['name'])) ?>">
        <?php if (isset($errors['name'])): ?><span class="text-danger"><?= e($errors['name']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Địa Chỉ Email <span class="text-danger">*</span></label>
        <input type="text" name="email" class="form-control" value="<?= e(old('email', $patient['email'])) ?>">
        <?php if (isset($errors['email'])): ?><span class="text-danger"><?= e($errors['email']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Số Điện Thoại <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control" value="<?= e(old('phone', $patient['phone'])) ?>">
        <?php if (isset($errors['phone'])): ?><span class="text-danger"><?= e($errors['phone']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Trạng Thái Tư Vấn:</label>
        <select name="status" class="form-control">
            <option value="new" <?= old('status', $patient['status']) === 'new' ? 'selected' : '' ?>>Mới đăng ký (New)</option>
            <option value="contacted" <?= old('status', $patient['status']) === 'contacted' ? 'selected' : '' ?>>Đã liên hệ cuộc gọi (Contacted)</option>
            <option value="qualified" <?= old('status', $patient['status']) === 'qualified' ? 'selected' : '' ?>>Đủ điều kiện khám (Qualified)</option>
            <option value="lost" <?= old('status', $patient['status']) === 'lost' ? 'selected' : '' ?>>Không nhu cầu (Lost)</option>
        </select>
    </div>
    <div class="form-group">
        <label>Ghi Chú Triệu Chứng Bệnh:</label>
        <textarea name="note" class="form-control" rows="4"><?= e(old('note', $patient['note'])) ?></textarea>
    </div>
    <button type="submit" class="btn">Cập Nhật Ngay</button>
    <a href="/patients" class="btn" style="background: #6c757d;">Hủy Bỏ</a>
</form>