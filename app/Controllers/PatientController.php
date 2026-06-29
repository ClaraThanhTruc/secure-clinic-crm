<?php
namespace App\Controllers;

use App\Services\PatientService;

class PatientController
{
    public function __construct(private PatientService $service) {}

    // 1. Danh sách bệnh nhân phía nội bộ (Admin)
    public function index(): void
    {
        require_login();
        $data = $this->service->getPatientList($_GET);
        render('patients/index', ['title' => 'Quản lý Bệnh nhân'] + $data);
    }

    // 2. Giao diện form công khai đặt lịch tư vấn (Không cần đăng nhập)
    public function createPublic(): void
    {
        render('patients/public_create', ['title' => 'Đăng ký Tư vấn Sức khỏe Miễn phí', 'errors' => []]);
    }

    // 3. Xử lý lưu form công khai (Áp dụng Honeypot/Rate Limit + PRG Pattern)
    public function storePublic(): void
    {
        $result = $this->service->createPatient($_POST, true); // true kích hoạt anti-spam
        
        if (!$result['success']) {
            $_SESSION['old_input'] = $_POST;
            render('patients/public_create', [
                'title' => 'Đăng ký Tư vấn Sức khỏe Miễn phí',
                'errors' => $result['errors']
            ]);
            unset($_SESSION['old_input']);
            return;
        }

        // ĐĂNG KÝ THÀNH CÔNG -> PRG Pattern redirect ngay lập tức (Task T08)
        flash('success', 'Gửi thông tin đăng ký tư vấn thành công! Bác sĩ sẽ gọi lại cho bạn sớm nhất.');
        redirect('/public-leads/create');
    }

    // 4. Các chức năng CRUD nội bộ Admin
    public function create(): void
    {
        require_login();
        render('patients/create', ['title' => 'Thêm Bệnh nhân mới', 'errors' => []]);
    }

    public function store(): void
    {
        require_login();
        $result = $this->service->createPatient($_POST, false);
        
        if (!$result['success']) {
            $_SESSION['old_input'] = $_POST;
            render('patients/create', [
                'title' => 'Thêm Bệnh nhân mới',
                'errors' => $result['errors']
            ]);
            unset($_SESSION['old_input']);
            return;
        }

        flash('success', 'Tạo hồ sơ bệnh nhân mới thành công.');
        redirect('/patients');
    }

    public function edit(): void
    {
        require_login();
        $id = (int)($_GET['id'] ?? 0);
        $patient = $this->service->getPatientById($id);
        
        if (!$patient) {
            flash('error', 'Không tìm thấy hồ sơ bệnh nhân yêu cầu.');
            redirect('/patients');
        }

        render('patients/edit', ['title' => 'Chỉnh sửa Hồ sơ Bệnh nhân', 'patient' => $patient, 'errors' => []]);
    }

    public function update(): void
    {
        require_login();
        $id = (int)($_POST['id'] ?? 0);
        $result = $this->service->updatePatient($id, $_POST);

        if (!$result['success']) {
            $_SESSION['old_input'] = $_POST;
            $patient = $this->service->getPatientById($id);
            render('patients/edit', [
                'title' => 'Chỉnh sửa Hồ sơ Bệnh nhân',
                'patient' => $patient,
                'errors' => $result['errors']
            ]);
            unset($_SESSION['old_input']);
            return;
        }

        flash('success', 'Cập nhật hồ sơ bệnh nhân thành công.');
        redirect('/patients');
    }

    public function delete(): void
    {
        require_login();
        $id = (int)($_POST['id'] ?? 0);
        $this->service->deletePatient($id);
        
        flash('success', 'Xóa hồ sơ bệnh nhân thành công.');
        redirect('/patients');
    }
}