<?php
namespace App\Repositories;

use App\Core\DuplicateRecordException;
use PDO;
use PDOException;

class AppointmentRepository
{
    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM appointments";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE appointment_code LIKE :keyword OR customer_name LIKE :keyword OR customer_email LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $sql = "SELECT id, appointment_code, customer_name, customer_email, total_amount, status, created_at FROM appointments";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE appointment_code LIKE :keyword OR customer_name LIKE :keyword OR customer_email LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }
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
        $sql = "INSERT INTO appointments (appointment_code, customer_name, customer_email, total_amount, status) 
                VALUES (:appointment_code, :customer_name, :customer_email, :total_amount, :status)";
        $stmt = $this->db->prepare($sql);
        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Mã lịch hẹn này đã tồn tại trên hệ thống phòng khám.');
            }
            throw $e;
        }
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM appointments WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $appointment = $stmt->fetch();
        return $appointment ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = "UPDATE appointments SET appointment_code = :appointment_code, customer_name = :customer_name, 
                customer_email = :customer_email, total_amount = :total_amount, status = :status WHERE id = :id";
        try {
            return $this->db->prepare($sql)->execute($data);
        } catch (PDOException $e) {
            if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) {
                throw new DuplicateRecordException('Mã lịch hẹn này đã được sử dụng cho một đơn đặt lịch khác.');
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM appointments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}