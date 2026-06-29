<?php
namespace App\Services;

use App\Repositories\UserRepository;

class AuthService
{
    public function __construct(private UserRepository $userRepo) {}

    public function authenticate(string $email, string $password): array
    {
        $email = trim($email);
        if ($email === '' || $password === '') {
            return ['success' => false, 'error' => 'Email và mật khẩu không được để trống.'];
        }

        $user = $this->userRepo->findActiveByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'Email hoặc mật khẩu không chính xác.'];
        }

        return ['success' => true, 'user' => $user];
    }
}