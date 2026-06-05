# GastroFlow - Hệ Thống Quản Lý Nhà Hàng & POS Cao Cấp

GastroFlow là một ứng dụng quản lý nhà hàng và điểm bán hàng (POS) trên nền tảng web Single-Page Application (SPA) cao cấp. Ứng dụng được thiết kế theo xu hướng **Glassmorphism** sang trọng, hiện đại, hỗ trợ đầy đủ các tính năng nghiệp vụ nhà hàng từ gọi món, điều phối bếp đến thanh toán và thống kê doanh thu.

Dự án chạy hoàn toàn ở phía client (Client-side) và lưu trữ dữ liệu thông qua trình duyệt (`LocalStorage`), cho phép khởi chạy ngay lập tức mà không cần cấu hình hệ quản trị cơ sở dữ liệu phức tạp.

---

## 🌟 Tính Năng Nổi Bật

1. **Bảng Điều Khiển Tổng Quan (Dashboard)**:
   - Thống kê doanh thu ngày, tỉ lệ sử dụng bàn ăn, số đơn hàng đang xử lý thời gian thực.
   - Trực quan hóa sơ đồ bàn nhanh.
   - Thống kê tự động các món ăn bán chạy nhất dựa trên lịch sử hóa đơn.
   - Nhật ký hóa đơn gần đây được thanh toán trong ngày.

2. **Quản Lý Sơ Đồ Bàn (Table Management)**:
   - Trực quan hóa trạng thái bàn ăn dưới dạng thẻ màu sắc (Trống - Xám, Đang phục vụ - Xanh lá, Đặt trước - Vàng).
   - Bộ lọc nhanh các trạng thái bàn.
   - Hỗ trợ thêm bàn ăn mới trực tuyến chỉ với vài cú click (tự động cấu hình số ghế).

3. **Màn Hình Gọi Món (POS - Point of Sale)**:
   - Tìm kiếm món ăn cực nhanh bằng thanh công cụ hoặc phân nhóm món (Khai vị, Món chính, Đồ uống, Tráng miệng).
   - Cho phép chọn bàn ăn trực tiếp để order, tăng giảm số lượng món, thêm ghi chú chi tiết cho nhà bếp (ít cay, không hành...).
   - Tự động tính toán Tạm tính, Thuế VAT (10%), Tổng tiền thanh toán thời gian thực.

4. **Điều Phối Đơn Hàng Live (Kitchen Coordinator)**:
   - Giao diện dạng Kanban live chia làm 4 công đoạn chính: **Chờ chế biến** -> **Đang nấu** -> **Chờ phục vụ (Xong)** -> **Đã phục vụ / Chờ thanh toán**.
   - Tự động cập nhật thời gian trôi qua từ lúc order để đầu bếp và nhân viên ưu tiên món ra trước.
   - Nút hành động nhanh để chuyển tiếp trạng thái món ăn mượt mà.

5. **Quản Lý Thực Đơn (Menu Setup - CRUD)**:
   - Danh sách thực đơn trực quan, cho phép Thêm món mới, Sửa thông tin món cũ hoặc Xóa món.
   - Thiết lập hình ảnh món ăn trực tuyến qua URL và thay đổi trạng thái "Còn món / Hết món" tức thời.

6. **Lịch Sử Hóa Đơn & Bộ Lọc Doanh Thu**:
   - Lưu trữ toàn bộ hóa đơn đã thanh toán.
   - Lọc hóa đơn theo khoảng thời gian tùy chọn để tính tổng doanh thu tương ứng.
   - Hỗ trợ **Xem lại & In lại hóa đơn (Reprint Receipt)** với thiết kế bill thanh toán chuẩn hóa kèm mã QR thanh toán mockup.

7. **Trải Nghiệm & Giao Diện Premium (UI/UX)**:
   - Chế độ Sáng/Tối (Light/Dark mode) đồng bộ và lưu cấu hình ưu tiên trên trình duyệt.
   - Thiết kế kính mờ Glassmorphic thời thượng, tạo chiều sâu thị giác.
   - Micro-animations mượt mà trên từng tương tác của người dùng.
   - Responsive toàn diện cho cả Máy tính để bàn, Máy tính bảng và Thiết bị di động.

---

## 🛠️ Công Nghệ Sử Dụng

- **HTML5**: Cấu trúc ngữ nghĩa (Semantic HTML), tối ưu hóa SEO.
- **CSS3 (Vanilla)**: Thiết kế hệ thống layout (CSS Grid, Flexbox), tạo hiệu ứng Glassmorphism (`backdrop-filter`), thiết lập các biến CSS (CSS Variables) để quản lý themes màu sáng tối đồng bộ.
- **Javascript (ES6+)**: Quản lý State của hệ thống, xử lý sự kiện và thao tác DOM mượt mà.
- **FontAwesome (v6.4.0)**: Thư viện icon phong phú, sắc nét.
- **Google Fonts (Plus Jakarta Sans)**: Kiểu chữ hiện đại, cao cấp.

---

## 🚀 Hướng Dẫn Sử Dụng

Bạn có thể chạy dự án này cực kỳ đơn giản bằng 2 cách:

### Cách 1: Chạy trực tiếp (Không cần cài đặt)
1. Tải thư mục dự án về máy.
2. Click đúp vào file `index.html` để mở trực tiếp trên các trình duyệt web hiện đại (Chrome, Edge, Safari, Firefox).

### Cách 2: Sử dụng Live Server (Khuyên dùng)
Nếu bạn lập trình bằng VS Code:
1. Mở thư mục dự án bằng VS Code.
2. Cài đặt extension **Live Server**.
3. Bấm chuột phải vào `index.html` và chọn **Open with Live Server** (hoặc nhấn `Go Live` ở góc dưới bên phải).
4. Dự án sẽ chạy dưới cổng `http://127.0.0.1:5500`.

---

## 📂 Cấu Trúc Thư Mục Dự Án

```text
quanlynhahang/
│
├── index.html       # File giao diện HTML chính (Gồm các tab, sidebar và modals)
├── style.css        # File thiết kế CSS hệ thống, responsive và giao diện Glassmorphic
├── app.js           # File logic xử lý State, POS, đặt bàn, hóa đơn, LocalStorage
├── .gitignore       # Bỏ qua các file rác hệ thống hoặc IDE cục bộ
└── README.md        # File tài liệu hướng dẫn này
```

---

## 🌐 Đẩy Dự Án Lên GitHub Cá Nhân

Dự án này đã được cấu hình Git cục bộ. Để đẩy mã nguồn lên kho chứa GitHub cá nhân của bạn, hãy làm theo hướng dẫn sau trên terminal:

1. Đảm bảo bạn đang đứng tại thư mục dự án `quanlynhahang`.
2. Kiểm tra xem các file đã được thêm vào staging chưa:
   ```bash
   git status
   ```
3. Commit toàn bộ thay đổi lần đầu:
   ```bash
   git add .
   git commit -m "Initial commit: GastroFlow Restaurant Management POS Web App"
   ```
4. Đẩy mã nguồn lên nhánh chính (`main`):
   ```bash
   git branch -M main
   git push -u origin main
   ```
*(Lưu ý: Nếu gặp lỗi quyền xác thực khi push, vui lòng kiểm tra cấu hình SSH key hoặc Git Credentials trên tài khoản GitHub cá nhân).*
