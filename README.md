# GastroFlow Logistics - Hệ Thống Quản Lý Nhà Vận Chuyển (Bootstrap & PHP & MySQL)

GastroFlow Logistics là ứng dụng web quản lý danh sách và dịch vụ của các đơn vị nhà vận chuyển (shipping carriers, nhà xe). Dự án được thiết kế đẹp mắt với giao diện **Bootstrap 5 (Dark theme)** kết hợp kết nối cơ sở dữ liệu **MySQL trên XAMPP (qua cổng 3307)** và có đầy đủ tính năng CRUD (Thêm, Sửa, Xóa).

---

## 🌟 Chức Năng Cốt Lõi

1. **Thống kê nhanh**:
   - Hiển thị tổng số nhà xe trong danh sách.
   - Thống kê số lượng đơn vị đang hoạt động bình thường.
   - Tính toán mức phí vận chuyển cơ bản trung bình của các nhà xe.
   - Thống kê các đơn vị đang tạm dừng hoạt động.

2. **Tìm kiếm & Bộ lọc**:
   - Thanh tìm kiếm thông minh hỗ trợ lọc nhà xe theo Tên nhà vận chuyển, Địa chỉ hoặc Loại hình dịch vụ.

3. **CRUD Nhà Vận Chuyển**:
   - **Thêm mới**: Form modal nhập Tên nhà xe, Số điện thoại, Email, Địa chỉ, Chọn dịch vụ (Tiết kiệm, Hỏa tốc, Cồng kềnh, Hành khách), Phí cơ bản và Trạng thái.
   - **Chỉnh sửa**: Cập nhật thông tin chi tiết của nhà xe bất kỳ bằng form modal.
   - **Xóa**: Hủy đăng ký đơn vị vận chuyển khỏi hệ thống thông qua hộp thoại xác nhận.

---

## 🛠️ Hướng Dẫn Cài Đặt trên XAMPP

1. **Di chuyển thư mục**:
   - Đảm bảo thư mục dự án `quanlynhahang` nằm tại `C:\xampp\htdocs\quanlynhahang`.
2. **Khởi chạy XAMPP**:
   - Bật **Apache** và **MySQL** trên **XAMPP Control Panel**.
   - Chú ý: Cấu hình cổng MySQL trên máy của bạn đang chạy ở cổng **3307**.
3. **Import database**:
   - Tạo database tên `carrier_management` (đối chiếu `utf8mb4_unicode_ci`).
   - Chọn database `carrier_management`, bấm **Nhập (Import)** và chọn tệp `database.sql` trong thư mục dự án để cài đặt cấu trúc bảng và dữ liệu mẫu.

---

## 🚀 Khởi Chạy Ứng Dụng

Sau khi cấu hình XAMPP xong, bạn có thể chạy ứng dụng bằng 2 cách:

* **Cách 1 (XAMPP Server)**: Truy cập đường dẫn:
  [http://localhost/quanlynhahang/](http://localhost/quanlynhahang/)

* **Cách 2 (PHP Dev Server)**:
  1. Mở terminal tại thư mục dự án.
  2. Khởi động server: `C:\xampp\php\php.exe -S localhost:8000`.
  3. Truy cập địa chỉ: [http://localhost:8000/](http://localhost:8000/).
