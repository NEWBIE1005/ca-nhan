-- Cấu trúc cơ sở dữ liệu cho dự án Quản Lý Nhà Hàng
-- Cơ sở dữ liệu: quanlynhahang

CREATE DATABASE IF NOT EXISTS `quanlynhahang` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `quanlynhahang`;

-- 1. Bảng quản lý Bàn Ăn
CREATE TABLE IF NOT EXISTS `ban_an` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ten_ban` VARCHAR(50) NOT NULL,
  `so_ghe` INT NOT NULL,
  `trang_thai` VARCHAR(30) DEFAULT 'empty' -- empty (trống), serving (phục vụ), reserved (đã đặt)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng quản lý Món Ăn (Thực đơn)
CREATE TABLE IF NOT EXISTS `mon_an` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ten_mon` VARCHAR(100) NOT NULL,
  `gia` INT NOT NULL,
  `phan_loai` VARCHAR(50) NOT NULL, -- khaivi (khai vị), monchinh (món chính), douong (đồ uống), trangmieng (tráng miệng)
  `hinh_anh` VARCHAR(255) DEFAULT NULL,
  `trang_thai` VARCHAR(30) DEFAULT 'available' -- available (còn món), out_of_stock (hết món)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chèn dữ liệu mẫu cho Bàn Ăn
INSERT INTO `ban_an` (`ten_ban`, `so_ghe`, `trang_thai`) VALUES
('Bàn 01', 2, 'empty'),
('Bàn 02', 2, 'serving'),
('Bàn 03', 4, 'empty'),
('Bàn 04', 4, 'reserved'),
('Bàn 05', 6, 'empty'),
('Bàn 06', 8, 'empty');

-- Chèn dữ liệu mẫu cho Món Ăn
INSERT INTO `mon_an` (`ten_mon`, `gia`, `phan_loai`, `hinh_anh`, `trang_thai`) VALUES
('Gỏi Cuốn Tôm Thịt (3 chiếc)', 65000, 'khaivi', 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300', 'available'),
('Bò Bít Tết Sốt Tiêu Đen Úc', 245000, 'monchinh', 'https://images.unsplash.com/photo-1544025162-d76694265947?w=300', 'available'),
('Cơm Chiên Hải Sản Trứng Muối', 135000, 'monchinh', 'https://images.unsplash.com/photo-1603133872878-685f158659a5?w=300', 'available'),
('Trà Đào Cam Sả Đá', 45000, 'douong', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=300', 'available'),
('Cà Phê Muối GastroFlow', 49000, 'douong', 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=300', 'available'),
('Bánh Mousse Chanh Leo', 55000, 'trangmieng', 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=300', 'available');
