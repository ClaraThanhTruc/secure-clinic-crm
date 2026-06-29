<h2>👥 Quản Lý Bệnh Nhân Tiềm Năng</h2>
<div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
    <a href="/patients/create" class="btn btn-success">+ Thêm Bệnh Nhân Mới</a>
    <form method="GET" action="/patients" style="display: flex; gap: 5px;">
        <input type="text" name="q" class="form-control" style="width: 250px;" value="<?= e($keyword) ?>" placeholder="Tìm tên, email, SĐT...">
        <button type="submit" class="btn">Tìm</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên Bệnh Nhân</th>
            <th>Email</th>
            <th>Số Điện Thoại</th>
            <th>Trạng Thái</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($patients)): ?>
            <tr><td colspan="6" style="text-align: center; color: #6c757d;">Không tìm thấy bệnh nhân nào phù hợp.</td></tr>
        <?php else: ?>
            <?php foreach ($patients as $p): ?>
                <tr>
                    <td><?= e((string)$p['id']) ?></td>
                    <td><strong><?= e($p['name']) ?></strong></td>
                    <td><?= e($p['email']) ?></td>
                    <td><?= e($p['phone']) ?></td>
                    <td><span style="padding: 3px 8px; border-radius: 4px; font-size: 12px; background: #e8f4fd;"><?= e($p['status']) ?></span></td>
                    <td>
                        <a href="/patients/edit?id=<?= e((string)$p['id']) ?>" class="btn" style="padding: 4px 8px; font-size: 12px;">Sửa</a>
                        <form method="POST" action="/patients/delete" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa hồ sơ bệnh nhân này không?');">
                            <input type="hidden" name="id" value="<?= e((string)$p['id']) ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 12px;">Xóa</button>
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
            <a href="/patients?page=<?= $i ?>&q=<?= e($keyword) ?>&sort=<?= e($sort) ?>&direction=<?= e($direction) ?>" class="<?= $page === $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>