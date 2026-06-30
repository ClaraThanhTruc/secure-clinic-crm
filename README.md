# 🏥 Secure Clinic CRM - Hệ Thống Quản Lý Phòng Khám Bảo Mật (Final Lab)

Dự án cuối kỳ môn Phát triển Ứng dụng Web - Thiết kế theo kiến trúc PHP Thuần nâng cao (MVC + Service + Repository Pattern).

## 🔒 Cơ Chế Bảo Mật Tích Hợp
1. **Front Controller & Router Table:** Toàn bộ request tập trung qua `public/index.php`. Chốt chặn thông minh tự động trả lỗi 404 và 405 Method Not Allowed.
2. **Anti-Spam Form:** Tích hợp kỹ thuật **Honeypot field** ẩn kết hợp **Rate Limit 5 giây** bằng Session ngăn chặn hoàn toàn Bot Spam.
3. **Session Security:** Thiết lập Cookie Flags nghiêm ngặt (`httponly`, `samesite => Lax`), cơ chế tự động hủy phiên làm việc sau 30 phút và `session_regenerate_id(true)` chống cướp phiên.
4. **Database Injection Defense:** Kết nối PDO cấu hình `PDO::ATTR_EMULATE_PREPARES => false` kết hợp sử dụng **Prepared Statements** chống SQL Injection 100%.

## 🛠️ Hướng Dẫn Cài Đặt
- **Tạo Database:** Tạo dữ liệu `secure_clinic_crm_db` với bảng mã `utf8mb4_unicode_ci` trên phpMyAdmin.
- **Import SQL:** Nạp file `database/schema.sql` trước, sau đó nạp file `database/seed.sql`.
- **Khởi chạy Server:** Mở Terminal chạy câu lệnh: `php -S localhost:8000 -t public`
- **Tài khoản Demo:** `admin@clinic.com` / Mật khẩu: 123456
# Secure Clinic CRM - Hệ Thống Quản Lý Phòng Khám Bảo Mật (Final Lab)

Dự án cuối kỳ môn Phát triển Ứng dụng Web - Thiết kế theo kiến trúc PHP Thuần nâng cao (MVC + Service + Repository Pattern).

---

## 🔒 Cơ Chế Bảo Mật Tích Hợp

* **Front Controller & Router Table:** Toàn bộ request tập trung qua một chốt chặn duy nhất tại `public/index.php`. Router kiểm tra nghiêm ngặt phương thức và đường dẫn, tự động trả lỗi tập trung `404 Not Found` và `405 Method Not Allowed` đúng kỹ thuật.
* **Anti-Spam Form (Honeypot & Rate Limit):** Tích hợp trường ẩn `website` bằng CSS (`display:none`). Nếu Bot tự động điền vào sẽ bị loại bỏ request ngay lập tức. Kết hợp cơ chế Rate Limit chặn gửi form liên tục dưới 5 giây bằng Session.
* **Session Security:** Thiết lập Cookie Flags nghiêm ngặt (`HttpOnly` chống XSS, `SameSite => Lax` chống CSRF). Cơ chế tự động hủy phiên làm việc sau 30 phút không tương tác (Timeout) và ép buộc gọi `session_regenerate_id(true)` ngay sau khi login thành công để chống tấn công Cố định phiên (Session Fixation).
* **Database Injection Defense:** Kết nối PDO cấu hình tắt chế độ biên dịch giả lập (`PDO::ATTR_EMULATE_PREPARES => false`), ép buộc sử dụng Prepared Statements truyền tham số tách biệt cho 100% câu lệnh INSERT/SELECT, kết hợp Whitelist Column chống SQL Injection qua chức năng Sắp xếp (Sort).
* **XSS Defense:** Toàn bộ dữ liệu động lấy ra từ Database hoặc nhận từ người dùng trước khi in ra giao diện View đều được escape an toàn thông qua hàm `htmlspecialchars()`.

---

## 🛠️ Hướng Dẫn Cài Đặt & Khởi Chạy

### 1. Cách Tạo & Cấu Hình Database
* **Bước 1:** Mở **phpMyAdmin** trên XAMPP.
* **Bước 2:** Tạo một cơ sở dữ liệu mới với tên chính xác là: `secure_clinic_crm_db` và chọn bảng mã đối chiếu (Collation) là `utf8mb4_unicode_ci`.
* **Bước 3:** Chọn cơ sở dữ liệu vừa tạo, vào tab **Nhập (Import)** và chọn nạp file cấu hình theo thứ tự:
    1. Nạp file `database/schema.sql` (Để khởi tạo cấu trúc các bảng và cấu hình chỉ mục Index/Unique).
    2. Nạp file `database/seed.sql` (Để nạp dữ liệu mẫu gồm tài khoản admin và danh sách bệnh nhân ban đầu).

### 2. Cách Khởi Chạy Server Ảo
* **Bước 1:** Mở chương trình **CMD** (Windows) hoặc **Terminal** (VS Code) lên.
* **Bước 2:** Di chuyển vào đúng thư mục gốc của dự án `secure-clinic-crm`.
* **Bước 3:** Gõ chính xác câu lệnh sau để khởi chạy server PHP ảo hướng vào thư mục `public`:
  ```bash
  php -S localhost:8000 -t public