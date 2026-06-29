<h2>📊 Bàn Làm Việc Quản Trị Hệ Thống Phòng Khám</h2>
<p>Xin chào, <strong><?= e($_SESSION['user_name']) ?></strong>! Chúc bác sĩ có một ngày làm việc tràn đầy năng lượng và hiệu quả.</p>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;">
    <div style="padding: 20px; background: #e8f4fd; border-left: 5px solid #007bff; border-radius: 4px;">
        <h3>👥 BỆNH NHÂN TIỀN NĂNG</h3>
        <p>Kiểm soát thông tin, trạng thái tư vấn và hồ sơ của bệnh nhân đăng ký tư vấn qua trang công khai.</p>
        <a href="/patients" class="btn">Vào Phân Hệ Quản Lý</a>
    </div>
    <div style="padding: 20px; background: #eafaf1; border-left: 5px solid #28a745; border-radius: 4px;">
        <h3>📅 ĐƠN ĐẶT LỊCH HẸN KHÁM</h3>
        <p>Kiểm tra danh sách lịch hẹn đặt trước, quản lý chi phí dịch vụ và mã bảo mật tránh trùng đơn.</p>
        <a href="/appointments" class="btn" style="background-color: #28a745;">Vào Phân Hệ Lịch Hẹn</a>
    </div>
</div>