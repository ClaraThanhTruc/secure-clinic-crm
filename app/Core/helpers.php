<?php
// Cấu hình Session Cookie an toàn trước khi start
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,  // Chống mã độc JavaScript đánh cắp session
        'samesite' => 'Lax',  // Chống tấn công giả mạo CSRF cơ bản
    ]);
    session_start();
}

// 1. Hàm escape dữ liệu chống tấn công XSS
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// 2. Hàm chuyển hướng trang nhanh (PRG Pattern)
function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

// 3. Hàm render giao diện kèm Layout dùng chung
function render(string $view, array $data = [], string $layout = 'layouts/main'): void
{
    extract($data);
    ob_start();
    require __DIR__ . '/../Views/' . $view . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/../Views/' . $layout . '.php';
}

// 4. Hàm gọi file partial nhỏ (như thanh menu, thanh thông báo)
function partial(string $name, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../Views/partials/' . $name . '.php';
}

// 5. Hàm thiết lập thông báo nhanh (Flash Message) hiện 1 lần rồi tự xóa
function flash(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

// 6. Hàm lấy thông báo nhanh ra hiển thị
function get_flash(string $key): ?string
{
    if (empty($_SESSION['flash'][$key])) return null;
    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $message;
}

// 7. Hàm giữ lại dữ liệu cũ đã nhập vào form khi bị lỗi
function old(string $key, $default = '')
{
    return $_SESSION['old_input'][$key] ?? $default;
}

// 8. Hàm kiểm tra phương thức POST
function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// 9. Middleware kiểm tra đăng nhập bảo vệ các trang Admin
function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        flash('error', 'Vui lòng đăng nhập để truy cập hệ thống.');
        redirect('/login');
    }

    // Kiểm tra Session Timeout (Hết hạn phiên làm việc)
    $config = require __DIR__ . '/../../config/app.php';
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $config['timeout'])) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path']);
        }
        session_destroy();
        flash('error', 'Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại.');
        redirect('/login');
    }
    $_SESSION['last_activity'] = time(); // Cập nhật thời gian hoạt động mới nhất
}