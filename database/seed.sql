USE secure_clinic_crm_db;

-- Tài khoản mật khẩu demo: 123456
INSERT INTO users (name, email, password_hash, role)
VALUES
('Admin Clinic', 'admin@clinic.com', '$2y$10$8v/tYgQ0uA243Fbe0S6/G.7I9hO9RNoHAn8lZ36lqB16V7O60KkEq', 'admin');

-- Dữ liệu mẫu Bệnh nhân (Module A)
INSERT INTO patients (name, email, phone, status, note)
VALUES
('Nguyen Van A', 'bnhan01@gmail.com', '0901234561', 'new', 'Cần tư vấn gói khám tổng quát'),
('Tran Thi B', 'bnhan02@gmail.com', '0901234562', 'contacted', 'Đã hẹn lịch thứ 2 tuần sau');

-- Dữ liệu mẫu Lịch hẹn (Module B)
INSERT INTO appointments (appointment_code, customer_name, customer_email, total_amount, status)
VALUES
('APT-2026-0001', 'Nguyen Van A', 'bnhan01@gmail.com', 1500000.00, 'pending'),
('APT-2026-0002', 'Tran Thi B', 'bnhan02@gmail.com', 500000.00, 'paid');