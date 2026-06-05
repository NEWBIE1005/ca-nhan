<?php
// Cấu hình kết nối Cơ sở dữ liệu MySQL trên XAMPP
$host = 'localhost';
$port = '3307'; // Cấu hình cổng 3307 từ XAMPP Control Panel
$dbname = 'quanlynhahang';
$username = 'root';
$password = '';

try {
    // Kết nối CSDL bằng PDO (thêm tham số port)
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Thiết lập chế độ báo lỗi ngoại lệ
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Thiết lập chế độ fetch mặc định là Array Assoc
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Hiển thị giao diện thông báo lỗi kết nối đẹp mắt nếu không khởi động CSDL
    die('
    <!DOCTYPE html>
    <html lang="vi" data-bs-theme="dark">
    <head>
        <meta charset="UTF-8">
        <title>Lỗi kết nối CSDL</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="bg-dark text-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card bg-danger bg-opacity-10 border-danger text-center p-5" style="max-width: 600px; border-radius: 20px; backdrop-filter: blur(10px);">
            <div class="text-danger mb-4"><i class="fa-solid fa-triangle-exclamation fa-4x"></i></div>
            <h2 class="card-title text-danger mb-3">Không thể kết nối Cơ sở dữ liệu!</h2>
            <p class="card-text mb-4 text-secondary">Hệ thống không thể kết nối tới MySQL trên XAMPP. Vui lòng thực hiện các bước sau:</p>
            <div class="text-start bg-dark p-3 rounded border border-secondary mb-4">
                <ol class="mb-0 text-light">
                    <li>Mở <strong>XAMPP Control Panel</strong>.</li>
                    <li>Khởi động dịch vụ <strong>Apache</strong> và <strong>MySQL</strong>.</li>
                    <li>Truy cập <a href="http://localhost/phpmyadmin/" target="_blank" class="text-info">phpMyAdmin</a> và tạo cơ sở dữ liệu mới với tên: <code class="text-warning">quanlynhahang</code>.</li>
                    <li>Nhập (Import) file <code class="text-warning">database.sql</code> trong thư mục dự án vào database vừa tạo.</li>
                </ol>
            </div>
            <button onclick="window.location.reload();" class="btn btn-outline-danger w-100"><i class="fa-solid fa-rotate-right me-2"></i>Thử kết nối lại</button>
        </div>
    </body>
    </html>
    ');
}
?>
