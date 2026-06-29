<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $handler): void
    {
        $this->routes[strtoupper($method)][$path] = $handler;
    }

    public function dispatch(string $method, string $path, array $container): void
    {
        $method = strtoupper($method);
        
        // 1. Kiểm tra xem Path này có tồn tại trong hệ thống không
        $pathExists = false;
        foreach ($this->routes as $m => $paths) {
            if (isset($paths[$path])) {
                $pathExists = true;
                break;
            }
        }

        // Nếu hoàn toàn không tồn tại -> Trả về lỗi 404
        if (!$pathExists) {
            http_response_code(404);
            render('errors/404', ['title' => '404 Not Found']);
            exit;
        }

        // 2. Nếu tồn tại nhưng sai Method (Ví dụ dùng GET cho Link Delete) -> Trả về lỗi 405
        if (!isset($this->routes[$method][$path])) {
            http_response_code(405);
            render('errors/405', ['title' => '405 Method Not Allowed']);
            exit;
        }

        // 3. Nếu mọi thứ hợp lệ -> Gọi Controller và Action tương ứng ra xử lý
        [$class, $action] = $this->routes[$method][$path];
        if (isset($container[$class])) {
            $controller = $container[$class];
            $controller->$action();
        } else {
            throw new \Exception("Component {$class} không tìm thấy trong Container hệ thống.");
        }
    }
}