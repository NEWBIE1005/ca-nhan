-- Cấu trúc cơ sở dữ liệu cho dự án Quản Lý Nhà Vận Chuyển
-- Cơ sở dữ liệu: carrier_management

CREATE DATABASE IF NOT EXISTS `carrier_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `carrier_management`;

-- 1. Bảng loại dịch vụ vận chuyển
CREATE TABLE IF NOT EXISTS `loai_dich_vu` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ten_dich_vu` VARCHAR(100) NOT NULL,
  `mo_ta` TEXT DEFAULT NULL,
  `phi_co_ban` INT NOT NULL,
  `trang_thai` VARCHAR(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng quản lý Nhà Vận Chuyển
CREATE TABLE IF NOT EXISTS `nha_van_chuyen` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ten_nha_xe` VARCHAR(100) NOT NULL,
  `so_dien_thoai` VARCHAR(15) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `dia_chi` VARCHAR(255) NOT NULL,
  `loai_dich_vu_id` INT NOT NULL,
  `phi_co_ban` INT NOT NULL,
  `trang_thai` VARCHAR(20) NOT NULL DEFAULT 'active',
  FOREIGN KEY (`loai_dich_vu_id`) REFERENCES `loai_dich_vu`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng khách hàng
CREATE TABLE IF NOT EXISTS `khach_hang` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ho_ten` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `so_dien_thoai` VARCHAR(15) NOT NULL,
  `dia_chi` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Bảng địa điểm giao nhận
CREATE TABLE IF NOT EXISTS `dia_diem` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ten_dia_diem` VARCHAR(120) NOT NULL,
  `tinh` VARCHAR(80) NOT NULL,
  `huyen` VARCHAR(80) NOT NULL,
  `xa` VARCHAR(80) NOT NULL,
  `mo_ta` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Bảng đơn hàng
CREATE TABLE IF NOT EXISTS `don_hang` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ma_don_hang` VARCHAR(50) NOT NULL UNIQUE,
  `khach_hang_id` INT NOT NULL,
  `nha_van_chuyen_id` INT NOT NULL,
  `dich_vu_id` INT NOT NULL,
  `dia_diem_nhan_id` INT NOT NULL,
  `dia_diem_giao_id` INT NOT NULL,
  `phi_van_chuyen` INT NOT NULL,
  `trang_thai` VARCHAR(30) NOT NULL DEFAULT 'chuaxuly',
  `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`nha_van_chuyen_id`) REFERENCES `nha_van_chuyen`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`dich_vu_id`) REFERENCES `loai_dich_vu`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`dia_diem_nhan_id`) REFERENCES `dia_diem`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`dia_diem_giao_id`) REFERENCES `dia_diem`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Bảng trạng thái đơn hàng
CREATE TABLE IF NOT EXISTS `trang_thai_don_hang` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ten_trang_thai` VARCHAR(50) NOT NULL,
  `mo_ta` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu cho dịch vụ
INSERT INTO `loai_dich_vu` (`ten_dich_vu`, `mo_ta`, `phi_co_ban`, `trang_thai`) VALUES
('Tiết kiệm', 'Dịch vụ giao hàng giá rẻ, phù hợp đơn hàng nhẹ và không gấp.', 15000, 'active'),
('Hỏa tốc', 'Giao hàng nhanh trong ngày, ưu tiên vận chuyển nội thành.', 30000, 'active'),
('Hàng cồng kềnh', 'Dịch vụ chuyên dụng cho hàng kích thước lớn và nặng.', 50000, 'active'),
('Vận tải hành khách', 'Dịch vụ vận chuyển hành khách và hành lý nhỏ.', 25000, 'active');

-- Dữ liệu mẫu cho nhà vận chuyển
INSERT INTO `nha_van_chuyen` (`ten_nha_xe`, `so_dien_thoai`, `email`, `dia_chi`, `loai_dich_vu_id`, `phi_co_ban`, `trang_thai`) VALUES
('Giao Hàng Nhanh (GHN)', '19001200', 'cskh@ghn.vn', '285 Cách Mạng Tháng Tám, Quận 10, TP.HCM', 2, 35000, 'active'),
('Viettel Post', '19008095', 'support@viettelpost.com.vn', 'Tòa nhà Viettel, Cầu Giấy, Hà Nội', 1, 18000, 'active'),
('J&T Express', '19001088', 'cskh@jtexpress.vn', '10 Mai Chí Thọ, Thủ Đức, TP.HCM', 1, 16500, 'active'),
('Giao Hàng Tiết Kiệm (GHTK)', '19006092', 'cskh@ghtk.vn', '8 Phạm Hùng, Nam Từ Liêm, Hà Nội', 1, 15000, 'active');

-- Dữ liệu mẫu cho khách hàng
INSERT INTO `khach_hang` (`ho_ten`, `email`, `so_dien_thoai`, `dia_chi`) VALUES
('Nguyễn Văn A', 'nguyenvana@example.com', '0912345678', '123 Lê Lợi, Quận 1, TP.HCM'),
('Trần Thị B', 'tranthib@example.com', '0987654321', '456 Nguyễn Trãi, Quận 5, TP.HCM');

-- Dữ liệu mẫu cho địa điểm
INSERT INTO `dia_diem` (`ten_dia_diem`, `tinh`, `huyen`, `xa`, `mo_ta`) VALUES
('Kho Trung Tâm HCM', 'TP.HCM', 'Quận 1', 'Phường Bến Nghé', 'Kho chính giao nhận tại trung tâm TP.HCM.'),
('Kho Hà Nội', 'Hà Nội', 'Quận Cầu Giấy', 'Phường Nghĩa Đô', 'Kho giao nhận của vùng Hà Nội.');

-- Dữ liệu mẫu cho trạng thái đơn hàng
INSERT INTO `trang_thai_don_hang` (`ten_trang_thai`, `mo_ta`) VALUES
('chuaxuly', 'Đơn hàng mới tiếp nhận, chưa xử lý'),
('dangvanchuyen', 'Đơn hàng đang trong quá trình vận chuyển'),
('hoanthanh', 'Đơn hàng đã giao thành công');

-- Dữ liệu mẫu cho đơn hàng
INSERT INTO `don_hang` (`ma_don_hang`, `khach_hang_id`, `nha_van_chuyen_id`, `dich_vu_id`, `dia_diem_nhan_id`, `dia_diem_giao_id`, `phi_van_chuyen`, `trang_thai`) VALUES
('DH2026001', 1, 1, 2, 1, 2, 45000, 'dangvanchuyen'),
('DH2026002', 2, 2, 1, 2, 1, 18000, 'chuaxuly');
