CREATE DATABASE IF NOT EXISTS secure_clinic_crm_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE secure_clinic_crm_db;

-- 1. Bảng nhân viên hệ thống (Đăng nhập)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Bảng quản lý bệnh nhân tiềm năng (Module A)
CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(30),
  status ENUM('new', 'contacted', 'qualified', 'lost') NOT NULL DEFAULT 'new',
  note TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_patient_email (email),
  INDEX idx_patients_created_at (created_at),
  INDEX idx_patients_status_created_at (status, created_at)
) ENGINE=InnoDB;

-- 3. Bảng quản lý lịch hẹn khám (Module B)
CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  appointment_code VARCHAR(50) NOT NULL,
  customer_name VARCHAR(100) NOT NULL,
  customer_email VARCHAR(150),
  total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  status ENUM('pending', 'paid', 'shipping', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_appointment_code (appointment_code),
  INDEX idx_appointments_created_at (created_at),
  INDEX idx_appointments_status_created_at (status, created_at),
  INDEX idx_appointments_customer_email (customer_email)
) ENGINE=InnoDB;