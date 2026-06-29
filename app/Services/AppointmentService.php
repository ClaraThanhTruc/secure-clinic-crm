<?php
namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Core\DuplicateRecordException;

class AppointmentService
{
    public function __construct(private AppointmentRepository $repo) {}

    public function getAppointmentList(array $query): array
    {
        $keyword = trim($query['q'] ?? '');
        
        $page = isset($query['page']) ? (int)$query['page'] : 1;
        $perPage = 5; 
        $totalItems = $this->repo->countAll($keyword);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        
        if ($page < 1) $page = 1;
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;

        $allowedSort = ['id', 'appointment_code', 'customer_name', 'total_amount', 'created_at'];
        $sort = in_array($query['sort'] ?? '', $allowedSort, true) ? $query['sort'] : 'created_at';
        $direction = strtoupper($query['direction'] ?? '') === 'ASC' ? 'ASC' : 'DESC';

        return [
            'appointments' => $this->repo->getPaginated($keyword, $perPage, $offset, $sort, $direction),
            'keyword' => $keyword,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'sort' => $sort,
            'direction' => $direction
        ];
    }

    public function createAppointment(array $input): array
    {
        $validation = $this->validateAppointmentData($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        try {
            $this->repo->create($validation['values']);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['appointment_code' => $e->getMessage()]];
        }
    }

    public function updateAppointment(int $id, array $input): array
    {
        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Lịch hẹn không tồn tại.']];
        }

        $validation = $this->validateAppointmentData($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        try {
            $this->repo->update($id, $validation['values']);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['appointment_code' => $e->getMessage()]];
        }
    }

    public function deleteAppointment(int $id): array
    {
        if ($id <= 0) return ['success' => false, 'errors' => ['general' => 'ID không hợp lệ.']];
        $this->repo->delete($id);
        return ['success' => true, 'errors' => []];
    }

    public function getAppointmentById(int $id): ?array
    {
        return $this->repo->findById($id);
    }

    private function validateAppointmentData(array $input): array
    {
        $errors = [];
        $appointment_code = trim($input['appointment_code'] ?? '');
        $customer_name = trim($input['customer_name'] ?? '');
        $customer_email = trim($input['customer_email'] ?? '');
        $total_amount = filter_var($input['total_amount'] ?? 0, FILTER_VALIDATE_FLOAT);
        $status = trim($input['status'] ?? 'pending');

        if ($appointment_code === '') $errors['appointment_code'] = 'Mã lịch hẹn không được để trống.';
        if ($customer_name === '') $errors['customer_name'] = 'Tên khách hàng đặt lịch không được để trống.';
        if ($customer_email !== '' && !filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
            $errors['customer_email'] = 'Email khách hàng định dạng không chính xác.';
        }
        if ($total_amount === false || $total_amount < 0) {
            $errors['total_amount'] = 'Số tiền chi phí khám không được nhỏ hơn 0.';
        }
        if (!in_array($status, ['pending', 'paid', 'shipping', 'completed', 'cancelled'], true)) {
            $errors['status'] = 'Trạng thái lịch hẹn không hợp lệ.';
        }

        return [
            'errors' => $errors,
            'values' => [
                'appointment_code' => $appointment_code,
                'customer_name' => $customer_name,
                'customer_email' => $customer_email ?: null,
                'total_amount' => $total_amount === false ? 0.00 : $total_amount,
                'status' => $status
            ]
        ];
    }
}