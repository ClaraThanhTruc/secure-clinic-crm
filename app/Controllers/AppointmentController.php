<?php
namespace App\Controllers;

use App\Services\AppointmentService;

class AppointmentController
{
    public function __construct(private AppointmentService $service) {}

    public function index(): void
    {
        require_login();
        $data = $this->service->getAppointmentList($_GET);
        render('appointments/index', ['title' => 'Danh sách Lịch hẹn khám'] + $data);
    }

    public function create(): void
    {
        require_login();
        render('appointments/create', ['title' => 'Tạo Lịch hẹn khám mới', 'errors' => []]);
    }

    public function store(): void
    {
        require_login();
        $result = $this->service->createAppointment($_POST);
        
        if (!$result['success']) {
            $_SESSION['old_input'] = $_POST;
            render('appointments/create', [
                'title' => 'Tạo Lịch hẹn khám mới',
                'errors' => $result['errors']
            ]);
            unset($_SESSION['old_input']);
            return;
        }

        flash('success', 'Tạo đơn đặt lịch hẹn khám thành công.');
        redirect('/appointments');
    }

    public function edit(): void
    {
        require_login();
        $id = (int)($_GET['id'] ?? 0);
        $appointment = $this->service->getAppointmentById($id);
        
        if (!$appointment) {
            flash('error', 'Không tìm thấy lịch hẹn yêu cầu.');
            redirect('/appointments');
        }

        render('appointments/edit', ['title' => 'Cập nhật Đơn Lịch hẹn', 'appointment' => $appointment, 'errors' => []]);
    }

    public function update(): void
    {
        require_login();
        $id = (int)($_POST['id'] ?? 0);
        $result = $this->service->updateAppointment($id, $_POST);

        if (!$result['success']) {
            $_SESSION['old_input'] = $_POST;
            $appointment = $this->service->getAppointmentById($id);
            render('appointments/edit', [
                'title' => 'Cập nhật Đơn Lịch hẹn',
                'appointment' => $appointment,
                'errors' => $result['errors']
            ]);
            unset($_SESSION['old_input']);
            return;
        }

        flash('success', 'Cập nhật thông tin lịch hẹn thành công.');
        redirect('/appointments');
    }

    public function delete(): void
    {
        require_login();
        $id = (int)($_POST['id'] ?? 0);
        $this->service->deleteAppointment($id);
        
        flash('success', 'Đã hủy/xóa đơn lịch hẹn thành công.');
        redirect('/appointments');
    }
}