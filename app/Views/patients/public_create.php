<div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #dee2e6;">
    <h2 style="color: #28a745; margin-top: 0;">🏥 Đăng Ký Tư Vấn Sức Khỏe Cộng Đồng Miễn Phí</h2>
    <p style="color: #6c757d; font-size: 14px;">Vui lòng điền đầy đủ thông tin bên dưới, hội đồng bác sĩ phòng khám sẽ liên hệ hỗ trợ bồ trong vòng 24h.</p>
    
    <?php if (isset($errors['general'])): ?>
        <div class="alert alert-danger"><?= e($errors['general']) ?></div>
    <?php endif; ?>

    <form method="POST" action="/public-leads">
        <div style="display: none;">
            <label>Địa chỉ nhà (Bỏ trống field này nếu bồ là người thật):</label>
            <input type="text" name="address_hp" value="">
        </div>

        <div class="form-group">
            <label>Họ và Tên của Bạn <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="<?= e(old('name')) ?>" placeholder="Nguyễn Văn A">
            <?php if (isset($errors['name'])): ?><span class="text-danger"><?= e($errors['name']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Địa Chỉ Email Nhận Lời Khuyên <span class="text-danger">*</span></label>
            <input type="text" name="email" class="form-control" value="<?= e(old('email')) ?>" placeholder="nva@gmail.com">
            <?php if (isset($errors['email'])): ?><span class="text-danger"><?= e($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Số Điện Thoại Để Bác Sĩ Gọi <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control" value="<?= e(old('phone')) ?>" placeholder="090xxxxxxx">
            <?php if (isset($errors['phone'])): ?><span class="text-danger"><?= e($errors['phone']) ?></span><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Mô Tả Sơ Lược Tình Trạng Sức Khỏe Hiện Tại:</label>
            <textarea name="note" class="form-control" rows="4" placeholder="Ví dụ: Đau đầu âm ỉ kèm chóng mặt vào buổi sáng..."><?= e(old('note')) ?></textarea>
        </div>
        <button type="submit" class="btn btn-success" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold;">Gửi Đăng Ký Ngay</button>
    </form>
</div>