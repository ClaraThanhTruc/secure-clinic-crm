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
- **Tài khoản Demo:** `admin@clinic.com` / Mật khẩu gõ bypass bất kỳ.