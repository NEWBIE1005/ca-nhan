# GastroFlow POS - Quản Lý Nhà Hàng CRUD (Bootstrap & PHP & MySQL)

GastroFlow POS là ứng dụng web quản trị và quản lý nhà hàng chạy trên môi trường **XAMPP** sử dụng ngôn ngữ **PHP**, giao diện khung **Bootstrap 5** và cơ sở dữ liệu **MySQL**. Hệ thống tập trung vào tính năng CRUD (Thêm, Sửa, Xóa) cho hai phân hệ cốt lõi: **Quản lý Bàn Ăn** và **Quản lý Thực Đơn**.

---

## 🌟 Chức Năng Chính

1. **Quản Lý Bàn Ăn (CRUD)**:
   - Hiển thị danh sách bàn ăn (Mã bàn, Tên bàn, Số lượng ghế, Trạng thái).
   - Thêm bàn ăn mới với số ghế và trạng thái tùy chọn.
   - Chỉnh sửa thông tin chi tiết của bàn ăn (Tên bàn, số ghế, thay đổi trạng thái: Trống, Đang phục vụ, Đã đặt).
   - Xóa bàn ăn khỏi danh sách.

2. **Quản Lý Thực Đơn (CRUD)**:
   - Hiển thị danh sách món ăn kèm hình ảnh đại diện trực quan, giá bán, phân loại và trạng thái.
   - Thêm món ăn mới vào thực đơn (hỗ trợ nhập URL ảnh, đơn giá, phân nhóm món ăn).
   - Chỉnh sửa chi tiết món ăn (tên món, giá, phân nhóm, cập nhật trạng thái: Còn món, Hết món).
   - Xóa món ăn khỏi thực đơn.

---

## 🛠️ Yêu Cầu Hệ Thống & Cài Đặt trên XAMPP

Để chạy được dự án, bạn cần cài đặt phần mềm **XAMPP** trên máy tính:

### Bước 1: Di chuyển thư mục dự án
1. Đảm bảo thư mục dự án `quanlynhahang` nằm trong thư mục `htdocs` của XAMPP.
   - Đường dẫn thông thường trên Windows: `C:\xampp\htdocs\quanlynhahang`.

### Bước 2: Khởi động XAMPP Control Panel
1. Mở phần mềm **XAMPP Control Panel**.
2. Bấm nút **Start** cho cả hai dịch vụ: **Apache** và **MySQL**.

### Bước 3: Tạo và Import Cơ sở dữ liệu
1. Mở trình duyệt và truy cập: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
2. Bấm vào nút **Mới (New)** ở cột bên trái để tạo Cơ sở dữ liệu mới.
3. Nhập tên Cơ sở dữ liệu là: `quanlynhahang` (chọn đối chiếu `utf8mb4_unicode_ci`) rồi nhấn **Tạo (Create)**.
4. Bấm chọn database `quanlynhahang` vừa tạo, chọn tab **Nhập (Import)** ở menu trên cùng.
5. Nhấn **Chọn tệp (Choose File)**, chọn file `database.sql` nằm trong thư mục dự án (`C:\xampp\htdocs\quanlynhahang\database.sql`).
6. Kéo xuống dưới cùng và nhấn **Nhập (Import)**.

---

## 🚀 Cách Chạy Ứng Dụng

Sau khi hoàn tất cài đặt, bạn truy cập đường dẫn sau trên trình duyệt:

[http://localhost/quanlynhahang/](http://localhost/quanlynhahang/)

---

## 📂 Cấu Trúc Dự Án

```text
quanlynhahang/
│
├── index.php        # Trang giao diện chính và xử lý logic CRUD (Bootstrap 5 & PHP)
├── db.php           # Tệp kết nối Cơ sở dữ liệu MySQL bằng PDO
├── database.sql     # Tệp khởi tạo cấu trúc CSDL và chèn dữ liệu mẫu
├── .gitignore       # Bỏ qua các file rác của hệ thống/IDE khi đẩy lên Git
└── README.md        # Tài liệu hướng dẫn sử dụng tiếng Việt
```
