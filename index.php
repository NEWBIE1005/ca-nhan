<?php
// Bắt đầu session để lưu trạng thái thông báo toast
session_start();
require_once 'db.php';

// --- XỬ LÝ CÁC YÊU CẦU POST (CRUD) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        try {
            // 1. THÊM BÀN ĂN
            if ($action === 'add_table') {
                $ten_ban = trim($_POST['ten_ban']);
                $so_ghe = intval($_POST['so_ghe']);
                $trang_thai = $_POST['trang_thai'];
                
                $stmt = $conn->prepare("INSERT INTO ban_an (ten_ban, so_ghe, trang_thai) VALUES (?, ?, ?)");
                $stmt->execute([$ten_ban, $so_ghe, $trang_thai]);
                $_SESSION['toast'] = ['message' => "Đã thêm '$ten_ban' thành công!", 'type' => 'success'];
            }
            // 2. SỬA BÀN ĂN
            elseif ($action === 'edit_table') {
                $id = intval($_POST['id']);
                $ten_ban = trim($_POST['ten_ban']);
                $so_ghe = intval($_POST['so_ghe']);
                $trang_thai = $_POST['trang_thai'];
                
                $stmt = $conn->prepare("UPDATE ban_an SET ten_ban = ?, so_ghe = ?, trang_thai = ? WHERE id = ?");
                $stmt->execute([$ten_ban, $so_ghe, $trang_thai, $id]);
                $_SESSION['toast'] = ['message' => "Đã cập nhật '$ten_ban' thành công!", 'type' => 'success'];
            }
            // 3. XÓA BÀN ĂN
            elseif ($action === 'delete_table') {
                $id = intval($_POST['id']);
                $ten_ban = $_POST['ten_ban_delete'];
                
                $stmt = $conn->prepare("DELETE FROM ban_an WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['toast'] = ['message' => "Đã xóa bàn ăn '$ten_ban'!", 'type' => 'danger'];
            }
            // 4. THÊM MÓN ĂN
            elseif ($action === 'add_menu') {
                $ten_mon = trim($_POST['ten_mon']);
                $gia = intval($_POST['gia']);
                $phan_loai = $_POST['phan_loai'];
                $hinh_anh = trim($_POST['hinh_anh']) ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300';
                $trang_thai = $_POST['trang_thai'];
                
                $stmt = $conn->prepare("INSERT INTO mon_an (ten_mon, gia, phan_loai, hinh_anh, trang_thai) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$ten_mon, $gia, $phan_loai, $hinh_anh, $trang_thai]);
                $_SESSION['toast'] = ['message' => "Đã thêm món '$ten_mon' vào thực đơn!", 'type' => 'success'];
            }
            // 5. SỬA MÓN ĂN
            elseif ($action === 'edit_menu') {
                $id = intval($_POST['id']);
                $ten_mon = trim($_POST['ten_mon']);
                $gia = intval($_POST['gia']);
                $phan_loai = $_POST['phan_loai'];
                $hinh_anh = trim($_POST['hinh_anh']) ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300';
                $trang_thai = $_POST['trang_thai'];
                
                $stmt = $conn->prepare("UPDATE mon_an SET ten_mon = ?, gia = ?, phan_loai = ?, hinh_anh = ?, trang_thai = ? WHERE id = ?");
                $stmt->execute([$ten_mon, $gia, $phan_loai, $hinh_anh, $trang_thai, $id]);
                $_SESSION['toast'] = ['message' => "Đã cập nhật món '$ten_mon'!", 'type' => 'success'];
            }
            // 6. XÓA MÓN ĂN
            elseif ($action === 'delete_menu') {
                $id = intval($_POST['id']);
                $ten_mon = $_POST['ten_mon_delete'];
                
                $stmt = $conn->prepare("DELETE FROM mon_an WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['toast'] = ['message' => "Đã xóa món '$ten_mon' khỏi thực đơn!", 'type' => 'danger'];
            }
        } catch (Exception $e) {
            $_SESSION['toast'] = ['message' => "Đã xảy ra lỗi: " . $e->getMessage(), 'type' => 'danger'];
        }
        
        // Quay trở lại trang chính để tránh re-submit form
        header("Location: index.php");
        exit;
    }
}

// --- TRUY VẤN DỮ LIỆU ĐỂ HIỂN THỊ ---
$tables = $conn->query("SELECT * FROM ban_an ORDER BY ten_ban ASC")->fetchAll();
$menu_items = $conn->query("SELECT * FROM mon_an ORDER BY id DESC")->fetchAll();

// Lấy thông báo toast nếu có
$toast = isset($_SESSION['toast']) ? $_SESSION['toast'] : null;
unset($_SESSION['toast']); // Xóa sau khi đã lấy
?>
<!DOCTYPE html>
<html lang="vi" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GastroFlow - Admin CRUD</title>
    <!-- Bootstrap 5 CSS (Theme Dark) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0c10;
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, #ffffff, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-card {
            background: rgba(33, 37, 41, 0.45);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
        }
        .table-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .nav-pills .nav-link {
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
            color: #94a3b8;
        }
        .nav-pills .nav-link.active {
            background-color: #6366f1;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg border-bottom border-secondary border-opacity-25 py-3">
        <div class="container">
            <span class="navbar-brand fs-4"><i class="fa-solid fa-fire-burner me-2 text-primary"></i>GastroFlow POS</span>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3 border border-primary border-opacity-25 rounded-pill">
                    <i class="fa-solid fa-server me-1"></i> XAMPP Localhost Connected
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        
        <!-- Tab Selectors -->
        <div class="row mb-4">
            <div class="col-12 text-center d-flex justify-content-center">
                <ul class="nav nav-pills bg-secondary bg-opacity-10 p-1 rounded-pill" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill" id="pills-tables-tab" data-bs-toggle="pill" data-bs-target="#pills-tables" type="button" role="tab" aria-controls="pills-tables" aria-selected="true">
                            <i class="fa-solid fa-table-cells-large me-2"></i>Quản Lý Bàn Ăn
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill" id="pills-menu-tab" data-bs-toggle="pill" data-bs-target="#pills-menu" type="button" role="tab" aria-controls="pills-menu" aria-selected="false">
                            <i class="fa-solid fa-utensils me-2"></i>Quản Lý Thực Đơn
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="pills-tabContent">
            
            <!-- TAB 1: BÀN ĂN -->
            <div class="tab-pane fade show active" id="pills-tables" role="tabpanel" aria-labelledby="pills-tables-tab">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0 fw-bold"><i class="fa-solid fa-chair text-primary me-2"></i>Danh sách Bàn Ăn</h4>
                        <button class="btn btn-primary rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#addTableModal">
                            <i class="fa-solid fa-plus me-1"></i>Thêm Bàn Mới
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark text-secondary text-uppercase fs-7">
                                <tr>
                                    <th>Mã Bàn</th>
                                    <th>Tên Bàn</th>
                                    <th>Số Ghế</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-end">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($tables)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu bàn ăn nào. Hãy thêm mới!</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($tables as $table): ?>
                                        <tr>
                                            <td><strong>#<?php echo $table['id']; ?></strong></td>
                                            <td><span class="fw-semibold"><?php echo htmlspecialchars($table['ten_ban']); ?></span></td>
                                            <td><?php echo $table['so_ghe']; ?> ghế</td>
                                            <td>
                                                <?php if ($table['trang_thai'] === 'empty'): ?>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded">Trống</span>
                                                <?php elseif ($table['trang_thai'] === 'serving'): ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded">Đang Phục Vụ</span>
                                                <?php elseif ($table['trang_thai'] === 'reserved'): ?>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded">Đã Đặt Trước</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-outline-warning btn-sm me-1 rounded-3 edit-table-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editTableModal"
                                                        data-id="<?php echo $table['id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($table['ten_ban']); ?>"
                                                        data-seats="<?php echo $table['so_ghe']; ?>"
                                                        data-status="<?php echo $table['trang_thai']; ?>">
                                                    <i class="fa-solid fa-pen"></i> Sửa
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm rounded-3 delete-table-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteTableModal"
                                                        data-id="<?php echo $table['id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($table['ten_ban']); ?>">
                                                    <i class="fa-solid fa-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: THỰC ĐƠN -->
            <div class="tab-pane fade" id="pills-menu" role="tabpanel" aria-labelledby="pills-menu-tab">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0 fw-bold"><i class="fa-solid fa-utensils text-primary me-2"></i>Danh sách Thực Đơn</h4>
                        <button class="btn btn-primary rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                            <i class="fa-solid fa-plus me-1"></i>Thêm Món Mới
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark text-secondary text-uppercase fs-7">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên Món</th>
                                    <th>Phân Nhóm</th>
                                    <th>Đơn Giá</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-end">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($menu_items)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Chưa có món ăn nào trong thực đơn. Hãy thêm mới!</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($menu_items as $item): ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo htmlspecialchars($item['hinh_anh']); ?>" alt="Food image" class="table-image border border-secondary border-opacity-25">
                                            </td>
                                            <td><span class="fw-bold"><?php echo htmlspecialchars($item['ten_mon']); ?></span></td>
                                            <td>
                                                <?php 
                                                $categories = [
                                                    'khaivi' => 'Khai Vị',
                                                    'monchinh' => 'Món Chính',
                                                    'douong' => 'Đồ Uống',
                                                    'trangmieng' => 'Tráng Miệng'
                                                ];
                                                echo isset($categories[$item['phan_loai']]) ? $categories[$item['phan_loai']] : htmlspecialchars($item['phan_loai']);
                                                ?>
                                            </td>
                                            <td class="text-primary fw-semibold"><?php echo number_format($item['gia'], 0, ',', '.'); ?> đ</td>
                                            <td>
                                                <?php if ($item['trang_thai'] === 'available'): ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded">Còn món</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded">Hết món</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-outline-warning btn-sm me-1 rounded-3 edit-menu-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editMenuModal"
                                                        data-id="<?php echo $item['id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($item['ten_mon']); ?>"
                                                        data-price="<?php echo $item['gia']; ?>"
                                                        data-category="<?php echo $item['phan_loai']; ?>"
                                                        data-image="<?php echo htmlspecialchars($item['hinh_anh']); ?>"
                                                        data-status="<?php echo $item['trang_thai']; ?>">
                                                    <i class="fa-solid fa-pen"></i> Sửa
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm rounded-3 delete-menu-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteMenuModal"
                                                        data-id="<?php echo $item['id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($item['ten_mon']); ?>">
                                                    <i class="fa-solid fa-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ==========================================
         MODALS CHO BÀN ĂN
         ========================================== -->
    <!-- Modal: Thêm bàn -->
    <div class="modal fade" id="addTableModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus me-2 text-primary"></i>Thêm Bàn Ăn Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="add_table">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add-ten-ban" class="form-label">Tên Bàn / Số Bàn <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" id="add-ten-ban" name="ten_ban" required placeholder="Ví dụ: Bàn 13">
                        </div>
                        <div class="mb-3">
                            <label for="add-so-ghe" class="form-label">Số Ghế <span class="text-danger">*</span></label>
                            <input type="number" class="form-control bg-dark border-secondary text-light" id="add-so-ghe" name="so_ghe" min="1" max="50" required value="4">
                        </div>
                        <div class="mb-3">
                            <label for="add-trang-thai-ban" class="form-label">Trạng Thái</label>
                            <select class="form-select bg-dark border-secondary text-light" id="add-trang-thai-ban" name="trang_thai">
                                <option value="empty" selected>Bàn trống</option>
                                <option value="serving">Đang phục vụ</option>
                                <option value="reserved">Đã đặt trước</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary border-opacity-25">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Lưu Lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Sửa bàn -->
    <div class="modal fade" id="editTableModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen me-2 text-warning"></i>Chỉnh Sửa Bàn Ăn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="edit_table">
                    <input type="hidden" id="edit-table-id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-ten-ban" class="form-label">Tên Bàn / Số Bàn <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" id="edit-ten-ban" name="ten_ban" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-so-ghe" class="form-label">Số Ghế <span class="text-danger">*</span></label>
                            <input type="number" class="form-control bg-dark border-secondary text-light" id="edit-so-ghe" name="so_ghe" min="1" max="50" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-trang-thai-ban" class="form-label">Trạng Thái</label>
                            <select class="form-select bg-dark border-secondary text-light" id="edit-trang-thai-ban" name="trang_thai">
                                <option value="empty">Bàn trống</option>
                                <option value="serving">Đang phục vụ</option>
                                <option value="reserved">Đã đặt trước</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary border-opacity-25">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-save me-1"></i>Cập Nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Xóa bàn -->
    <div class="modal fade" id="deleteTableModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-trash-can me-2"></i>Xác Nhận Xóa Bàn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="delete_table">
                    <input type="hidden" id="delete-table-id" name="id">
                    <input type="hidden" id="delete-table-name-hidden" name="ten_ban_delete">
                    <div class="modal-body text-center py-4">
                        <i class="fa-solid fa-circle-exclamation text-danger fa-4x mb-3 animate-pulse"></i>
                        <p class="fs-5 mb-0">Bạn có chắc chắn muốn xóa bàn ăn <strong class="text-warning" id="delete-table-name">Bàn --</strong>?</p>
                        <span class="text-secondary fs-7">Hành động này không thể hoàn tác.</span>
                    </div>
                    <div class="modal-footer border-secondary border-opacity-25">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash-can me-1"></i>Đồng Ý Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==========================================
         MODALS CHO MÓN ĂN
         ========================================== -->
    <!-- Modal: Thêm món ăn -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus me-2 text-primary"></i>Thêm Món Ăn Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="add_menu">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add-ten-mon" class="form-label">Tên Món Ăn <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" id="add-ten-mon" name="ten_mon" required placeholder="Ví dụ: Bún Chả Hà Nội">
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="add-gia" class="form-label">Đơn Giá (đ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control bg-dark border-secondary text-light" id="add-gia" name="gia" min="0" required placeholder="65000">
                            </div>
                            <div class="col-6">
                                <label for="add-phan-loai" class="form-label">Phân Nhóm <span class="text-danger">*</span></label>
                                <select class="form-select bg-dark border-secondary text-light" id="add-phan-loai" name="phan_loai" required>
                                    <option value="khaivi">Khai Vị</option>
                                    <option value="monchinh" selected>Món Chính</option>
                                    <option value="douong">Đồ Uống</option>
                                    <option value="trangmieng">Tráng Miệng</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-hinh-anh" class="form-label">URL Hình Ảnh</label>
                            <input type="url" class="form-control bg-dark border-secondary text-light" id="add-hinh-anh" name="hinh_anh" placeholder="Để trống nếu lấy ảnh mặc định">
                        </div>
                        <div class="mb-3">
                            <label for="add-trang-thai-mon" class="form-label">Trạng Thái Món</label>
                            <select class="form-select bg-dark border-secondary text-light" id="add-trang-thai-mon" name="trang_thai">
                                <option value="available" selected>Còn món</option>
                                <option value="out_of_stock">Hết món</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary border-opacity-25">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i>Lưu Lại</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Sửa món ăn -->
    <div class="modal fade" id="editMenuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen me-2 text-warning"></i>Chỉnh Sửa Món Ăn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="edit_menu">
                    <input type="hidden" id="edit-menu-id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-ten-mon" class="form-label">Tên Món Ăn <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" id="edit-ten-mon" name="ten_mon" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="edit-gia" class="form-label">Đơn Giá (đ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control bg-dark border-secondary text-light" id="edit-gia" name="gia" min="0" required>
                            </div>
                            <div class="col-6">
                                <label for="edit-phan-loai" class="form-label">Phân Nhóm <span class="text-danger">*</span></label>
                                <select class="form-select bg-dark border-secondary text-light" id="edit-phan-loai" name="phan_loai" required>
                                    <option value="khaivi">Khai Vị</option>
                                    <option value="monchinh">Món Chính</option>
                                    <option value="douong">Đồ Uống</option>
                                    <option value="trangmieng">Tráng Miệng</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-hinh-anh" class="form-label">URL Hình Ảnh</label>
                            <input type="url" class="form-control bg-dark border-secondary text-light" id="edit-hinh-anh" name="hinh_anh">
                        </div>
                        <div class="mb-3">
                            <label for="edit-trang-thai-mon" class="form-label">Trạng Thái Món</label>
                            <select class="form-select bg-dark border-secondary text-light" id="edit-trang-thai-mon" name="trang_thai">
                                <option value="available">Còn món</option>
                                <option value="out_of_stock">Hết món</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary border-opacity-25">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-save me-1"></i>Cập Nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Xóa món ăn -->
    <div class="modal fade" id="deleteMenuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-trash-can me-2"></i>Xác Nhận Xóa Món Ăn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="delete_menu">
                    <input type="hidden" id="delete-menu-id" name="id">
                    <input type="hidden" id="delete-menu-name-hidden" name="ten_mon_delete">
                    <div class="modal-body text-center py-4">
                        <i class="fa-solid fa-circle-exclamation text-danger fa-4x mb-3"></i>
                        <p class="fs-5 mb-0">Bạn có chắc chắn muốn xóa món <strong class="text-warning" id="delete-menu-name">Món --</strong> khỏi thực đơn?</p>
                        <span class="text-secondary fs-7">Hành động này không thể hoàn tác.</span>
                    </div>
                    <div class="modal-footer border-secondary border-opacity-25">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash-can me-1"></i>Đồng Ý Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Live Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <?php if ($toast): ?>
            <div class="toast show bg-<?php echo $toast['type'] === 'danger' ? 'danger' : 'success'; ?> border-0 text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="fa-solid <?php echo $toast['type'] === 'danger' ? 'fa-circle-xmark' : 'fa-circle-check'; ?> me-2 fs-5"></i>
                        <span><?php echo htmlspecialchars($toast['message']); ?></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom scripts to handle Modal inputs population -->
    <script>
        // --- POPULATE TABLE EDIT & DELETE MODALS ---
        document.querySelectorAll('.edit-table-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-table-id').value = this.getAttribute('data-id');
                document.getElementById('edit-ten-ban').value = this.getAttribute('data-name');
                document.getElementById('edit-so-ghe').value = this.getAttribute('data-seats');
                document.getElementById('edit-trang-thai-ban').value = this.getAttribute('data-status');
            });
        });

        document.querySelectorAll('.delete-table-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                document.getElementById('delete-table-id').value = id;
                document.getElementById('delete-table-name').textContent = name;
                document.getElementById('delete-table-name-hidden').value = name;
            });
        });

        // --- POPULATE MENU EDIT & DELETE MODALS ---
        document.querySelectorAll('.edit-menu-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-menu-id').value = this.getAttribute('data-id');
                document.getElementById('edit-ten-mon').value = this.getAttribute('data-name');
                document.getElementById('edit-gia').value = this.getAttribute('data-price');
                document.getElementById('edit-phan-loai').value = this.getAttribute('data-category');
                document.getElementById('edit-hinh-anh').value = this.getAttribute('data-image');
                document.getElementById('edit-trang-thai-mon').value = this.getAttribute('data-status');
            });
        });

        document.querySelectorAll('.delete-menu-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                document.getElementById('delete-menu-id').value = id;
                document.getElementById('delete-menu-name').textContent = name;
                document.getElementById('delete-menu-name-hidden').value = name;
            });
        });
        
        // Auto hide toast after 4s
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            setTimeout(() => {
                const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
                toast.hide();
            }, 4000);
        }
    </script>
</body>
</html>
