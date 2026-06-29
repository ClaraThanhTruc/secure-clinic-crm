<h2>📝 Cập Nhật Thông Tin Đơn Đặt Lịch Hẹn</h2>
<form method="POST" action="/appointments/update" style="max-width: 600px;">
    <input type="hidden" name="id" value="<?= e((string)$appointment['id']) ?>">
    <div class="form-group">
        <label>Mã Bảo Mật Lịch Hẹn (Không được sửa đổi) <span class="text-danger">*</span></label>
        <input type="text" name="appointment_code" class="form-control" readonly value="<?= e($appointment['appointment_code']) ?>">
    </div>
    <div class="form-group">
        <label>Họ và Tên Khách Hàng <span class="text-danger">*</span></label>
        <input type="text" name="customer_name" class="form-control" value="<?= e(old('customer_name', $appointment['customer_name'])) ?>">
        <?php if (isset($errors['customer_name'])): ?><span class="text-danger"><?= e($errors['customer_name']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Địa Chỉ Email Khách Hàng:</label>
        <input type="text" name="customer_email" class="form-control" value="<?= e(old('customer_email', $appointment['customer_email'])) ?>">
        <?php if (isset($errors['customer_email'])): ?><span class="text-danger"><?= e($errors['customer_email']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Chi Phí Dịch Vụ / Tiền Khám (VNĐ) <span class="text-danger">*</span></label>
        <input type="number" name="total_amount" class="form-control" value="<?= e(old('total_amount', (string)$appointment['total_amount'])) ?>">
        <?php if (isset($errors['total_amount'])): ?><span class="text-danger"><?= e($errors['total_amount']) ?></span><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Trạng Thái Thanh Toán / Xử Lý Lịch:</label>
        <select name="status" class="form-control">
            <option value="pending" <?= old('status', $appointment['status']) === 'pending' ? 'selected' : '' ?>>Chờ khám (Pending)</option>
            <option value="paid" <?= old('status', $appointment['status']) === 'paid' ? 'selected' : '' ?>>Đã đóng tiền khám (Paid)</option>
            <option value="completed" <?= old('status', $appointment['status']) === 'completed' ? 'selected' : '' ?>>Đã khám xong (Completed)</option>
            <option value="cancelled" <?= old('status', $appointment['status']) === 'cancelled' ? 'selected' : '' ?>>Hủy lịch hẹn (Cancelled)</option>
        </select>
    </div>
    <button type="submit" class="btn">Cập Nhật Đơn Hẹn</button>
    <a href="/appointments" class="btn" style="background: #6c757d;">Hủy Bỏ</a>
</form>