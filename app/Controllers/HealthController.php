<?php
namespace App\Controllers;

use PDO;

class HealthController
{
    public function __construct(private PDO $db) {}

    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $dbStatus = 'OK';
        try {
            $this->db->query("SELECT 1");
        } catch (\Exception $e) {
            $dbStatus = 'DISCONNECTED';
        }

        echo json_construct([
            'status' => 'UP',
            'timestamp' => date('Y-m-d H:i:s'),
            'services' => [
                'application' => 'OK',
                'database' => $dbStatus
            ]
        ]);
        exit;
    }
}

// Hàm bổ trợ tạo JSON thủ công không bị lỗi font Tiếng Việt
function json_construct(array $data): string {
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}