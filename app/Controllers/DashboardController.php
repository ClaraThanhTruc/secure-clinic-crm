<?php
namespace App\Controllers;

class DashboardController
{
    public function index(): void
    {
        require_login(); // Ép phải đăng nhập mới được xem
        render('dashboard/index', ['title' => 'Bàn làm việc Bác sĩ (Dashboard)']);
    }
}