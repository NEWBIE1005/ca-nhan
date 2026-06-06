-- Cấu trúc cơ sở dữ liệu đơn giản cho dự án Quản Lý Nhà Vận Chuyển
-- Cơ sở dữ liệu: carrier_management

CREATE DATABASE IF NOT EXISTS `carrier_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `carrier_management`;

CREATE TABLE IF NOT EXISTS `nha_van_chuyen` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ten_nha_xe` VARCHAR(100) NOT NULL,
  `so_dien_thoai` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `dia_chi` VARCHAR(255) NOT NULL,
  `loai_dich_vu` VARCHAR(50) NOT NULL,
  `phi_co_ban` INT NOT NULL,
  `trang_thai` VARCHAR(20) NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `nha_van_chuyen` (`ten_nha_xe`, `so_dien_thoai`, `email`, `dia_chi`, `loai_dich_vu`, `phi_co_ban`, `trang_thai`) VALUES
('Giao Hàng Nhanh (GHN)', '19001200', 'cskh@ghn.vn', '285 Cách Mạng Tháng Tám, Quận 10, TP.HCM', 'hoatoc', 35000, 'active'),
('Viettel Post', '19008095', 'support@viettelpost.com.vn', 'Tòa nhà Viettel, Cầu Giấy, Hà Nội', 'tietkiem', 18000, 'active'),
('J&T Express', '19001088', 'cskh@jtexpress.vn', '10 Mai Chí Thọ, Thủ Đức, TP.HCM', 'tietkiem', 16500, 'active'),
('Giao Hàng Tiết Kiệm (GHTK)', '19006092', 'cskh@ghtk.vn', '8 Phạm Hùng, Nam Từ Liêm, Hà Nội', 'tietkiem', 15000, 'active');
