<?php
namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    public function __construct(private AuthService $authService) {}

    public function login(): void
    {
        // Nếu đã login rồi thì đá thẳng vào dashboard luôn
        if (isset($_SESSION['user_id'])) {
            redirect('/dashboard');
        }
        render('auth/login', ['title' => 'Đăng nhập hệ thống Clinic', 'errors' => []]);
    }

    public function handleLogin(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->authService->authenticate($email, $password);

        if (!$result['success']) {
            $_SESSION['old_input'] = ['email' => $email];
            render('auth/login', [
                'title' => 'Đăng nhập hệ thống Clinic',
                'errors' => ['general' => $result['error']]
            ]);
            unset($_SESSION['old_input']);
            return;
        }

        // BẢO MẬT: Đổi ID Session ngay sau khi login đúng để chống hack Session Fixation (Task T11)
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $result['user']['id'];
        $_SESSION['user_name'] = $result['user']['name'];
        $_SESSION['last_activity'] = time(); // Thiết lập mốc tính Timeout

        flash('success', 'Đăng nhập vào hệ thống phòng khám thành công!');
        redirect('/dashboard');
    }

    public function logout(): void
    {
        // Dọn rác sạch sẽ session (Task T12)
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path']);
        }
        session_destroy();
        
        // Mở lại session mới chỉ để lưu cái thông báo flash
        session_start();
        flash('success', 'Bạn đã đăng xuất khỏi hệ thống an toàn.');
        redirect('/login');
    }
}