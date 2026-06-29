<?php
namespace App\Services;

use App\Repositories\PatientRepository;
use App\Core\DuplicateRecordException;

class PatientService
{
    public function __construct(private PatientRepository $repo) {}

    public function getPatientList(array $query): array
    {
        $keyword = trim($query['q'] ?? '');
        
        // 1. Thuật toán xử lý phân trang an toàn (Bảo vệ dữ liệu biên)
        $page = isset($query['page']) ? (int)$query['page'] : 1;
        $perPage = 5; // Hiển thị 5 dòng mỗi trang để dễ test phân trang
        $totalItems = $this->repo->countAll($keyword);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        
        if ($page < 1) $page = 1;
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;

        // 2. Chốt chặn Sort bằng Whitelist an toàn (Thỏa mãn Task T25)
        $allowedSort = ['id', 'name', 'email', 'created_at'];
        $sort = in_array($query['sort'] ?? '', $allowedSort, true) ? $query['sort'] : 'created_at';
        
        $direction = strtoupper($query['direction'] ?? '') === 'ASC' ? 'ASC' : 'DESC';

        return [
            'patients' => $this->repo->getPaginated($keyword, $perPage, $offset, $sort, $direction),
            'keyword' => $keyword,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'sort' => $sort,
            'direction' => $direction
        ];
    }

    public function createPatient(array $input, bool $isPublicForm = false): array
    {
        // Chốt chặn Anti-Spam cho Form công khai bên ngoài (Thỏa mãn Task T09)
        if ($isPublicForm) {
            // Kỹ thuật 1: Bẫy gấu Honeypot (Field ẩn bị điền dữ liệu -> Hack/Bot spam)
            if (!isset($input['address_hp']) || $input['address_hp'] !== '') {
                return ['success' => false, 'errors' => ['general' => 'Phát hiện hành vi Spam hệ thống!']];
            }
            
            // Kỹ thuật 2: Rate Limit dựa trên Session (Chặn submit liên tục trong vòng 5 giây)
            if (isset($_SESSION['last_submit_time']) && (time() - $_SESSION['last_submit_time'] < 5)) {
                return ['success' => false, 'errors' => ['general' => 'Hệ thống bận! Vui lòng đợi 5 giây trước khi gửi lại form.']];
            }
            $_SESSION['last_submit_time'] = time();
        }

        // Validate Server-side nghiêm ngặt (Thỏa mãn Task T07)
        $validation = $this->validatePatientData($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        try {
            $this->repo->create($validation['values']);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['email' => $e->getMessage()]];
        }
    }

    public function updatePatient(int $id, array $input): array
    {
        if (!$this->repo->findById($id)) {
            return ['success' => false, 'errors' => ['general' => 'Bệnh nhân này không tồn tại trên hệ thống.']];
        }

        $validation = $this->validatePatientData($input);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors']];
        }

        try {
            $this->repo->update($id, $validation['values']);
            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException $e) {
            return ['success' => false, 'errors' => ['email' => $e->getMessage()]];
        }
    }

    public function deletePatient(int $id): array
    {
        if ($id <= 0) return ['success' => false, 'errors' => ['general' => 'ID không hợp lệ.']];
        $this->repo->delete($id);
        return ['success' => true, 'errors' => []];
    }

    public function getPatientById(int $id): ?array
    {
        return $this->repo->findById($id);
    }

    private function validatePatientData(array $input): array
    {
        $errors = [];
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $status = trim($input['status'] ?? 'new');
        $note = trim($input['note'] ?? '');

        if ($name === '') $errors['name'] = 'Tên bệnh nhân không được để trống.';
        if ($email === '') {
            $errors['email'] = 'Email không được để trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email định dạng không chính xác.';
        }
        if ($phone === '') $errors['phone'] = 'Số điện thoại không được để trống.';
        if (!in_array($status, ['new', 'contacted', 'qualified', 'lost'], true)) {
            $errors['status'] = 'Trạng thái lựa chọn không hợp lệ.';
        }

        return [
            'errors' => $errors,
            'values' => compact('name', 'email', 'phone', 'status', 'note')
        ];
    }
}