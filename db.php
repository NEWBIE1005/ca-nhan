<?php
// Cấu hình kết nối Cơ sở dữ liệu MySQL trên XAMPP
// Port 3307 + Password Kh@i2005 là cấu hình hoạt động đúng trên máy này
$host = '127.0.0.1';
$port = '3307';
$username = 'root';
$password = 'Kh@i2005';
$dbname = 'quanlynhahang';

try {
    // 1. Kết nối CSDL bằng PDO (không truyền dbname trước để tránh lỗi Unknown Database)
    $conn = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);
    // Thiết lập chế độ báo lỗi ngoại lệ
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 2. Tự động tạo CSDL nếu chưa tồn tại
    $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->exec("USE `$dbname`");
    
    // 3. Tự động tạo bảng và dữ liệu mẫu nếu chưa có
    $tableExists = $conn->query("SHOW TABLES LIKE 'nha_van_chuyen'")->rowCount() > 0;
    if (!$tableExists) {
        $sqlPath = __DIR__ . '/database.sql';
        if (file_exists($sqlPath)) {
            $sql = file_get_contents($sqlPath);
            // Loại bỏ chú thích và chạy từng câu lệnh
            $sql = preg_replace('/^\s*--.*$/m', '', $sql);
            $queries = explode(';', $sql);
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $conn->exec($query);
                }
            }
        }
    }
    
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
            <p class="card-text mb-4 text-secondary">Hệ thống không thể kết nối tới MySQL trên XAMPP. Lỗi: <code>' . htmlspecialchars($e->getMessage()) . '</code></p>
            <div class="text-start bg-dark p-3 rounded border border-secondary mb-4">
                <ol class="mb-0 text-light">
                    <li>Mở <strong>XAMPP Control Panel</strong>.</li>
                    <li>Khởi động dịch vụ <strong>Apache</strong> và <strong>MySQL</strong>.</li>
                    <li>Kiểm tra xem MySQL có đang chạy trên cổng <strong>3307</strong> và mật khẩu root có đúng là <strong>Kh@i2005</strong> hay không.</li>
                </ol>
            </div>
            <button onclick="window.location.reload();" class="btn btn-outline-danger w-100"><i class="fa-solid fa-rotate-right me-2"></i>Thử kết nối lại</button>
        </div>
    </body>
    </html>
    ');
}
?>
