<?php
namespace App\Repositories;

use App\Core\DuplicateRecordException;
use PDO;
use PDOException;

class PatientRepository
{
    public function __construct(private PDO $db) {}

    // Đếm tổng số bệnh nhân để tính số trang (Pagination)
    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM patients";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE name LIKE :keyword OR email LIKE :keyword OR phone LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    // Lấy danh sách bệnh nhân phân trang kèm Sort Whitelist an toàn
    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $sql = "SELECT id, name, email, phone, status, created_at FROM patients";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE name LIKE :keyword OR email LIKE :keyword OR phone LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }
        
        // Nối trực tiếp cột đã qua Whitelist kiểm duyệt an toàn
        $sql .= " ORDER BY {$sort} {$direction} LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO patients (name, email, phone, status, note) VALUES (:name, :email, :phone, :status, :note)";
        $stmt = $this->db->prepare($sql);
        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            // Lỗi số 1062 là mã lỗi trùng khóa UNIQUE trong MySQL
            if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Email này đã được đăng ký trong hệ thống.');
            }
            throw $e;
        }
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $patient = $stmt->fetch();
        return $patient ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = "UPDATE patients SET name = :name, email = :email, phone = :phone, status = :status, note = :note WHERE id = :id";
        try {
            return $this->db->prepare($sql)->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Email này đã được sử dụng bởi bệnh nhân khác.');
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM patients WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}