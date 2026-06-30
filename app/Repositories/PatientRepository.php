<?php

namespace App\Repositories;

class PatientRepository
{
    protected $db;

    /**
     * Khởi tạo kết nối Database qua PDO
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * [TC17] Tìm kiếm bệnh nhân theo Tên, Email hoặc SĐT (Sửa lỗi HY093)
     */
    public function search($keyword)
    {
        $searchParam = "%" . $keyword . "%";

        // Gán 3 nhãn khác nhau để khớp hoàn toàn với mảng execute
        $sql = "SELECT * FROM patients 
                WHERE name LIKE :keyword1 
                   OR email LIKE :keyword2 
                   OR phone LIKE :keyword3 
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([
            ':keyword1' => $searchParam,
            ':keyword2' => $searchParam,
            ':keyword3' => $searchParam
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * [TC11, TC12] Thêm hồ sơ bệnh nhân mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO patients (name, email, phone, status, notes, created_at) 
                VALUES (:name, :email, :phone, :status, :notes, NOW())";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'   => $data['name'],
            ':email'  => $data['email'],
            ':phone'  => $data['phone'],
            ':status' => $data['status'] ?? 'new',
            ':notes'  => $data['notes'] ?? ''
        ]);
    }

    /**
     * [TC13] Lấy thông tin một bệnh nhân theo ID để sửa
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM patients WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra Email trùng lặp để phục vụ TC12
     */
    public function findActiveByEmail($email)
    {
        $sql = "SELECT * FROM patients WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật thông tin bệnh nhân
     */
    public function update($id, $data)
    {
        $sql = "UPDATE patients 
                SET name = :name, email = :email, phone = :phone, status = :status, notes = :notes 
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':name'   => $data['name'],
            ':email'  => $data['email'],
            ':phone'  => $data['phone'],
            ':status' => $data['status'],
            ':notes'  => $data['notes'] ?? ''
        ]);
    }

    /**
     * [TC14] Xóa bệnh nhân (Chỉ nhận qua phương thức bảo mật)
     */
    public function delete($id)
    {
        $sql = "DELETE FROM patients WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Hàm đếm tổng số bệnh nhân để tính số trang (Fix lỗi line 18 Service)
     */
    public function countAll()
    {
        $sql = "SELECT COUNT(*) as total FROM patients";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /**
     * [TC18, TC19, TC20] Lấy danh sách bệnh nhân kèm Phân Trang (Fix lỗi line 32 Service)
     */
    public function getPaginated($page = 1, $limit = 5, $sortColumn = 'id', $sortOrder = 'DESC')
    {
        // Danh sách trắng các cột cho phép sort để chống SQL Injection (TC20)
        $allowedColumns = ['id', 'name', 'email', 'phone', 'status'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
        
        $sortOrder = (strtoupper($sortOrder) === 'ASC') ? 'ASC' : 'DESC';
        
        // Xử lý trang âm hoặc quá lớn (TC18)
        $page = (int)$page;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM patients ORDER BY {$sortColumn} {$sortOrder} LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}