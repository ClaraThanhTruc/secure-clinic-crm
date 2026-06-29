<h2>📅 Phân Hệ Quản Lý Đơn Đặt Lịch Hẹn Khám</h2>
<div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
    <a href="/appointments/create" class="btn btn-success">+ Tạo Đơn Lịch Hẹn Mới</a>
    <form method="GET" action="/appointments" style="display: flex; gap: 5px;">
        <input type="text" name="q" class="form-control" style="width: 250px;" value="<?= e($keyword) ?>" placeholder="Mã đơn, tên, khách hàng...">
        <button type="submit" class="btn">Tìm Kiếm</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã Lịch Hẹn</th>
            <th>Tên Khách Hàng</th>
            <th>Email</th>
            <th>Phí Dịch Vụ Khám</th>
            <th>Trạng Thái Đơn</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($appointments)): ?>
            <tr><td colspan="7" style="text-align: center; color: #6c757d;">Không tìm thấy lịch hẹn nào trên hệ thống.</td></tr>
        <?php else: ?>
            <?php foreach ($appointments as $a): ?>
                <tr>
                    <td><?= e((string)$a['id']) ?></td>
                    <td><strong style="color: #007bff;"><?= e($a['appointment_code']) ?></strong></td>
                    <td><?= e($a['customer_name']) ?></td>
                    <td><?= e($a['customer_email'] ?? 'Trống') ?></td>
                    <td><strong><?= number_format((float)$a['total_amount'], 0, ',', '.') ?> VNĐ</strong></td>
                    <td><span style="padding: 3px 8px; border-radius: 4px; font-size: 12px; background: #eafaf1; color: #218838;"><?= e($a['status']) ?></span></td>
                    <td>
                        <a href="/appointments/edit?id=<?= e((string)$a['id']) ?>" class="btn" style="padding: 4px 8px; font-size: 12px;">Sửa</a>
                        <form method="POST" action="/appointments/delete" style="display: inline;" onsubmit="return confirm('Bồ chắc chắn muốn hủy/xóa đơn lịch hẹn này chứ?');">
                            <input type="hidden" name="id" value="<?= e((string)$a['id']) ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 12px;">Hủy Đơn</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/appointments?page=<?= $i ?>&q=<?= e($keyword) ?>&sort=<?= e($sort) ?>&direction=<?= e($direction) ?>" class="<?= $page === $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>