<?php
// 1. Nhúng bắt buộc file helpers chứa cấu hình Session Cookie Flag bảo mật
require_once __DIR__ . '/../app/Core/helpers.php';

// 2. Tự động nạp (Autoload) các Class theo đúng chuẩn cấu trúc namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// 3. Nạp cấu hình ứng dụng và kết nối Cơ sở dữ liệu bằng PDO chuẩn
$appConfig = require_once __DIR__ . '/../config/app.php';
$dbConfig = require_once __DIR__ . '/../config/database.php';

try {
    $pdo = \App\Core\Database::connect($dbConfig);
} catch (\PDOException $e) {
    // Chế độ bảo mật môi trường Production (Task T28)
    if (!$appConfig['debug']) {
        error_log($e->getMessage()); // Ghi lỗi âm thầm vào log hệ thống
        http_response_code(500);
        render('errors/production', ['title' => 'Lỗi Hệ Thống']);
        exit;
    }
    die("Lỗi kết nối cơ sở dữ liệu (Dev Mode): " . $e->getMessage());
}

// 4. Khởi tạo Container (Quản lý và bơm phụ thuộc giữa các tầng kiến trúc)
$userRepository = new \App\Repositories\UserRepository($pdo);
$patientRepository = new \App\Repositories\PatientRepository($pdo);
$appointmentRepository = new \App\Repositories\AppointmentRepository($pdo);

$authService = new \App\Services\AuthService($userRepository);
$patientService = new \App\Services\PatientService($patientRepository);
$appointmentService = new \App\Services\AppointmentService($appointmentRepository);

$container = [
    \App\Controllers\HomeController::class => new \App\Controllers\HomeController(),
    \App\Controllers\AuthController::class => new \App\Controllers\AuthController($authService),
    \App\Controllers\DashboardController::class => new \App\Controllers\DashboardController(),
    \App\Controllers\PatientController::class => new \App\Controllers\PatientController($patientService),
    \App\Controllers\AppointmentController::class => new \App\Controllers\AppointmentController($appointmentService),
    \App\Controllers\HealthController::class => new \App\Controllers\HealthController($pdo),
];

// 5. Khai báo Bản đồ Định tuyến (Route Table) chuẩn chỉ theo yêu cầu 1.2 của Đề bài
$router = new \App\Core\Router();

// --- METHOD: GET ---
$router->add('GET', '/', [\App\Controllers\HomeController::class, 'index']);
$router->add('GET', '/login', [\App\Controllers\AuthController::class, 'login']);
$router->add('GET', '/dashboard', [\App\Controllers\DashboardController::class, 'index']);
$router->add('GET', '/public-leads/create', [\App\Controllers\PatientController::class, 'createPublic']);
$router->add('GET', '/patients', [\App\Controllers\PatientController::class, 'index']);
$router->add('GET', '/patients/create', [\App\Controllers\PatientController::class, 'create']);
$router->add('GET', '/patients/edit', [\App\Controllers\PatientController::class, 'edit']);
$router->add('GET', '/appointments', [\App\Controllers\AppointmentController::class, 'index']);
$router->add('GET', '/appointments/create', [\App\Controllers\AppointmentController::class, 'create']);
$router->add('GET', '/appointments/edit', [\App\Controllers\AppointmentController::class, 'edit']);
$router->add('GET', '/health', [\App\Controllers\HealthController::class, 'index']);

// --- METHOD: POST ---
$router->add('POST', '/login', [\App\Controllers\AuthController::class, 'handleLogin']);
$router->add('POST', '/logout', [\App\Controllers\AuthController::class, 'logout']);
$router->add('POST', '/public-leads', [\App\Controllers\PatientController::class, 'storePublic']);
$router->add('POST', '/patients/store', [\App\Controllers\PatientController::class, 'store']);
$router->add('POST', '/patients/update', [\App\Controllers\PatientController::class, 'update']);
$router->add('POST', '/patients/delete', [\App\Controllers\PatientController::class, 'delete']);
$router->add('POST', '/appointments/store', [\App\Controllers\AppointmentController::class, 'store']);
$router->add('POST', '/appointments/update', [\App\Controllers\AppointmentController::class, 'update']);
$router->add('POST', '/appointments/delete', [\App\Controllers\AppointmentController::class, 'delete']);

// 6. Kích hoạt bộ định tuyến xử lý request hiện tại
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Chuẩn hóa đường dẫn bỏ dấu gạch chéo cuối nếu có (trừ trang chủ)
if ($path !== '/' && str_ends_with($path, '/')) {
    $path = rtrim($path, '/');
}

try {
    $router->dispatch($method, $path, $container);
} catch (\Exception $e) {
    if (!$appConfig['debug']) {
        error_log($e->getMessage());
        http_response_code(500);
        render('errors/production', ['title' => 'Lỗi Hệ Thống']);
        exit;
    }
    die("Lỗi thực thi hệ thống (Dev Mode): " . $e->getMessage());
}