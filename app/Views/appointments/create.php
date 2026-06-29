<h2>📅 Khởi Tạo Đơn Đặt Lịch Hẹn Khám</h2>
<form method="POST" action="/appointments/store" style="max-width: 600px;">
    <div class="form-group">
        <label>Mã Bảo Mật Lịch Hẹn (Mã duy nhất) <span class="text-danger">*</span></label>
        <input type="text" name="appointment_code" class="form-control" value="<?= e(old('appointment_code', 'APT-' . date('Ymd') . '-' . rand(1000, 9999))) ?>">
        <?php if (isset($errors['appointment_code'])): ?><span class="text-danger"><?= e($errors['appointment_code']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Họ và Tên Khách Hàng <span class="text-danger">*</span></label>
        <input type="text" name="customer_name" class="form-control" value="<?= e(old('customer_name')) ?>">
        <?php if (isset($errors['customer_name'])): ?><span class="text-danger"><?= e($errors['customer_name']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Địa Chỉ Email Khách Hàng:</label>
        <input type="text" name="customer_email" class="form-control" value="<?= e(old('customer_email')) ?>">
        <?php if (isset($errors['customer_email'])): ?><span class="text-danger"><?= e($errors['customer_email']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Chi Phí Dịch Vụ / Tiền Khám (VNĐ) <span class="text-danger">*</span></label>
        <input type="number" name="total_amount" class="form-control" value="<?= e(old('total_amount', '0')) ?>">
        <?php if (isset($errors['total_amount'])): ?><span class="text-danger"><?= e($errors['total_amount']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Trạng Thái Thanh Toán / Xử Lý Lịch:</label>
        <select name="status" class="form-control">
            <option value="pending" <?= old('status') === 'pending' ? 'selected' : '' ?>>Chờ khám (Pending)</option>
            <option value="paid" <?= old('status') === 'paid' ? 'selected' : '' ?>>Đã đóng tiền khám (Paid)</option>
            <option value="completed" <?= old('status') === 'completed' ? 'selected' : '' ?>>Đã khám xong (Completed)</option>
            <option value="cancelled" <?= old('status') === 'cancelled' ? 'selected' : '' ?>>Hủy lịch hẹn (Cancelled)</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Tạo Đơn Hẹn</button>
    <a href="/appointments" class="btn" style="background: #6c757d;">Hủy Bỏ</a>
</form>