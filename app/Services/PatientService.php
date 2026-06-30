<?php

namespace App\Services;

class PatientService
{
    protected $patientRepo;

    public function __construct($patientRepo)
    {
        $this->patientRepo = $patientRepo;
    }

    /**
     * Hàm xử lý lấy danh sách bệnh nhân (Tích hợp luôn cả Tìm kiếm và Phân trang)
     */
    public function getPatientList($queryParams)
    {
        // 1. Nếu người dùng có gõ từ khóa tìm kiếm (Bấm nút Tìm)
        if (!empty($queryParams['keyword'])) {
            $keyword = trim($queryParams['keyword']);
            $patients = $this->patientRepo->search($keyword);
            
            return [
                'patients'   => $patients,
                'total'      => count($patients),
                'page'       => 1,
                'totalPages' => 1,
                'limit'      => count($patients),
                'keyword'    => $keyword
            ];
        }

        // 2. Nếu không tìm kiếm -> Chạy phân trang bình thường như cũ
        $page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;
        $limit = 5; 
        
        $sortColumn = $queryParams['sort'] ?? 'id';
        $sortOrder = $queryParams['order'] ?? 'DESC';

        $totalPatients = $this->patientRepo->countAll();
        $totalPages = ceil($totalPatients / $limit);
        if ($totalPages < 1) $totalPages = 1;

        $patients = $this->patientRepo->getPaginated($page, $limit, $sortColumn, $sortOrder);

        return [
            'patients'   => $patients,
            'total'      => $totalPatients,
            'page'       => $page,
            'totalPages' => $totalPages,
            'limit'      => $limit,
            'keyword'    => ''
        ];
    }
}