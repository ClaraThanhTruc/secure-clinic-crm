<header>
    <h2>🏥 Secure Clinic CRM Portal</h2>
    <nav style="display: flex; gap: 15px; align-items: center;">
        <a href="/" style="color: white; text-decoration: none;">Trang Chủ Công Khai</a> |
        <a href="/public-leads/create" style="color: white; text-decoration: none; font-weight: bold;">Form Công Khai (Honeypot)</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            | <a href="/dashboard" style="color: white; text-decoration: none;">Dashboard</a>
            | <a href="/patients" style="color: white; text-decoration: none;">Quản Lý Bệnh Nhân</a>
            | <a href="/appointments" style="color: white; text-decoration: none;">Lịch Hẹn Khám</a>
            <span style="margin-left: 15px; background: #0056b3; padding: 4px 10px; border-radius: 4px; font-size: 13px;">
                BS: 🥼 <strong><?= e($_SESSION['user_name']) ?></strong>
            </span>
            <form method="POST" action="/logout" style="margin: 0; display: inline;">
                <button type="submit" style="background: none; border: none; color: #ffc107; cursor: pointer; font-weight: bold; font-size: 14px;">[Đăng Xuất]</button>
            </form>
        <?php else: ?>
            | <a href="/login" style="color: #ffc107; text-decoration: none; font-weight: bold;">[Đăng Nhập Hệ Thống]</a>
        <?php endif; ?>
    </nav>
</header>