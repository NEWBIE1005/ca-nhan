<?php
// Bắt đầu session để lưu trạng thái thông báo toast
session_start();
require_once 'db.php';

// --- XỬ LÝ CÁC YÊU CẦU POST (CRUD) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        try {
            // 1. THÊM NHÀ VẬN CHUYỂN
            if ($action === 'add_carrier') {
                $ten_nha_xe = trim($_POST['ten_nha_xe']);
                $so_dien_thoai = trim($_POST['so_dien_thoai']);
                $email = trim($_POST['email']);
                $dia_chi = trim($_POST['dia_chi']);
                $loai_dich_vu = isset($_POST['loai_dich_vu']) ? $_POST['loai_dich_vu'] : 'tietkiem';
                $phi_co_ban = intval($_POST['phi_co_ban']);
                $trang_thai = $_POST['trang_thai'];
                
                $stmt = $conn->prepare("INSERT INTO nha_van_chuyen (ten_nha_xe, so_dien_thoai, email, dia_chi, loai_dich_vu, phi_co_ban, trang_thai) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$ten_nha_xe, $so_dien_thoai, $email, $dia_chi, $loai_dich_vu, $phi_co_ban, $trang_thai]);
                $_SESSION['toast'] = ['message' => "Đã thêm nhà xe '$ten_nha_xe' thành công!", 'type' => 'success'];
            }
            // 2. SỬA NHÀ VẬN CHUYỂN
            elseif ($action === 'edit_carrier') {
                $id = intval($_POST['id']);
                $ten_nha_xe = trim($_POST['ten_nha_xe']);
                $so_dien_thoai = trim($_POST['so_dien_thoai']);
                $email = trim($_POST['email']);
                $dia_chi = trim($_POST['dia_chi']);
                $loai_dich_vu = isset($_POST['loai_dich_vu']) ? $_POST['loai_dich_vu'] : 'tietkiem';
                $phi_co_ban = intval($_POST['phi_co_ban']);
                $trang_thai = $_POST['trang_thai'];
                
                $stmt = $conn->prepare("UPDATE nha_van_chuyen SET ten_nha_xe = ?, so_dien_thoai = ?, email = ?, dia_chi = ?, loai_dich_vu = ?, phi_co_ban = ?, trang_thai = ? WHERE id = ?");
                $stmt->execute([$ten_nha_xe, $so_dien_thoai, $email, $dia_chi, $loai_dich_vu, $phi_co_ban, $trang_thai, $id]);
                $_SESSION['toast'] = ['message' => "Đã cập nhật thông tin '$ten_nha_xe' thành công!", 'type' => 'success'];
            }
            // 3. XÓA NHÀ VẬN CHUYỂN
            elseif ($action === 'delete_carrier') {
                $id = intval($_POST['id']);
                $ten_nha_xe = $_POST['ten_nha_xe_delete'];
                
                $stmt = $conn->prepare("DELETE FROM nha_van_chuyen WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['toast'] = ['message' => "Đã xóa nhà vận chuyển '$ten_nha_xe'!", 'type' => 'danger'];
            }
        } catch (Exception $e) {
            $_SESSION['toast'] = ['message' => "Đã xảy ra lỗi: " . $e->getMessage(), 'type' => 'danger'];
        }
        
        // Quay trở lại trang chính để tránh re-submit form
        header("Location: index.php");
        exit;
    }
}

// --- XỬ LÝ TÌM KIẾM ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM nha_van_chuyen WHERE ten_nha_xe LIKE ? OR dia_chi LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%", "%$search%"]);
    $carriers = $stmt->fetchAll();
} else {
    $carriers = $conn->query("SELECT * FROM nha_van_chuyen ORDER BY id DESC")->fetchAll();
}

// --- THỐNG KÊ NHANH ---
$stat_total = count($carriers);
$stat_active = 0;
$stat_inactive = 0;
$total_fee = 0;
foreach ($carriers as $c) {
    if ($c['trang_thai'] === 'active') {
        $stat_active++;
    } else {
        $stat_inactive++;
    }
    $total_fee += $c['phi_co_ban'];
}
$stat_avg_fee = $stat_total > 0 ? Math_round($total_fee / $stat_total) : 0;

function Math_round($val) {
    return round($val);
}

// Lấy thông báo toast nếu có
$toast = isset($_SESSION['toast']) ? $_SESSION['toast'] : null;
unset($_SESSION['toast']);
?>
<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GastroFlow - Quản Lý Nhà Vận Chuyển</title>
    <!-- Bootstrap 5 CSS (Theme Light) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fb;
            color: #212529;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1d4ed8 !important;
        }
        .glass-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 20px;
        }
        .table {
            background: #ffffff;
        }
        .table thead {
            background-color: #f8fafc;
        }
        .form-control,
        .form-select {
            background-color: #ffffff;
            color: #212529;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg border-bottom border-secondary border-opacity-25 py-3">
        <div class="container">
            <span class="navbar-brand fs-4"><i class="fa-solid fa-truck-fast me-2 text-primary"></i>GastroFlow Logistics</span>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        
        <!-- Statistics Section -->
        <div class="row g-4 mb-5">
            <!-- Stat 1 -->
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-15 text-primary">
                        <i class="fa-solid fa-shipping-fast"></i>
                    </div>
                    <div>
                        <span class="text-secondary fs-7 d-block">Tổng nhà xe</span>
                        <h4 class="mb-0 fw-bold"><?php echo $stat_total; ?></h4>
                    </div>
                </div>
            </div>
            <!-- Stat 2 -->
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-15 text-success">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <span class="text-secondary fs-7 d-block">Đang hoạt động</span>
                        <h4 class="mb-0 fw-bold"><?php echo $stat_active; ?></h4>
                    </div>
                </div>
            </div>
            <!-- Stat 3 -->
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-15 text-warning">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                    <div>
                        <span class="text-secondary fs-7 d-block">Phí cơ bản TB</span>
                        <h4 class="mb-0 fw-bold"><?php echo number_format($stat_avg_fee, 0, ',', '.'); ?> đ</h4>
                    </div>
                </div>
            </div>
            <!-- Stat 4 -->
            <div class="col-6 col-md-3">
                <div class="glass-card p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-15 text-danger">
                        <i class="fa-solid fa-circle-pause"></i>
                    </div>
                    <div>
                        <span class="text-secondary fs-7 d-block">Tạm dừng hoạt động</span>
                        <h4 class="mb-0 fw-bold"><?php echo $stat_inactive; ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- CRUD Actions & Search Area -->
        <div class="row mb-4 align-items-center g-3">
            <div class="col-md-8 col-12">
                <form action="index.php" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control bg-dark border-secondary text-light" placeholder="Tìm kiếm nhà vận chuyển, địa chỉ..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <button type="submit" class="btn btn-outline-primary px-3"><i class="fa-solid fa-filter"></i> Lọc</button>
                    <?php if ($search !== ''): ?>
                        <a href="index.php" class="btn btn-text text-secondary d-flex align-items-center text-nowrap"><i class="fa-solid fa-rotate-left me-1"></i>Đặt lại</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-4 col-12 text-md-end">
                <button class="btn btn-primary rounded-3 w-100 w-md-auto py-2 px-4" data-bs-toggle="modal" data-bs-target="#addCarrierModal">
                    <i class="fa-solid fa-plus me-2"></i>Thêm Nhà Vận Chuyển
                </button>
            </div>
        </div>

        <!-- Data Table Container -->
        <div class="glass-card p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark text-secondary text-uppercase fs-7">
                        <tr>
                            <th>Nhà xe / Tên vận chuyển</th>
                            <th>Thông tin liên hệ</th>
                            <th>Địa chỉ hoạt động</th>
                            <th>Phí cơ bản</th>
                            <th>Trạng Thái</th>
                            <th class="text-end">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($carriers)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-truck-moving fa-3x mb-3 text-secondary opacity-25"></i>
                                    <p class="mb-0">Không tìm thấy nhà vận chuyển nào phù hợp.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($carriers as $carrier): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold fs-6"><?php echo htmlspecialchars($carrier['ten_nha_xe']); ?></div>
                                        <span class="text-secondary fs-7">Mã: #CAR-<?php echo $carrier['id']; ?></span>
                                    </td>
                                    <td>
                                        <div class="fs-7 text-light"><i class="fa-solid fa-phone me-1 text-secondary"></i><?php echo htmlspecialchars($carrier['so_dien_thoai']); ?></div>
                                        <div class="fs-7 text-secondary"><i class="fa-solid fa-envelope me-1"></i><?php echo htmlspecialchars($carrier['email']); ?></div>
                                    </td>
                                    <td style="max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="<?php echo htmlspecialchars($carrier['dia_chi']); ?>">
                                        <?php echo htmlspecialchars($carrier['dia_chi']); ?>
                                    </td>
                                    <td class="text-primary fw-semibold"><?php echo number_format($carrier['phi_co_ban'], 0, ',', '.'); ?> đ</td>
                                    <td>
                                        <?php if ($carrier['trang_thai'] === 'active'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded">Tạm dừng</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-outline-warning btn-sm me-1 rounded-3 edit-carrier-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editCarrierModal"
                                                data-id="<?php echo $carrier['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($carrier['ten_nha_xe']); ?>"
                                                data-phone="<?php echo htmlspecialchars($carrier['so_dien_thoai']); ?>"
                                                data-email="<?php echo htmlspecialchars($carrier['email']); ?>"
                                                data-address="<?php echo htmlspecialchars($carrier['dia_chi']); ?>"
                                                data-fee="<?php echo $carrier['phi_co_ban']; ?>"
                                                data-status="<?php echo htmlspecialchars($carrier['trang_thai']); ?>">
                                            <i class="fa-solid fa-pen"></i> Sửa
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm rounded-3 delete-carrier-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteCarrierModal"
                                                data-id="<?php echo $carrier['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($carrier['ten_nha_xe']); ?>">
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

    <!-- ==========================================
         MODALS FOR CARRIERS
         ========================================== -->
    <!-- Modal: Thêm nhà vận chuyển -->
    <div class="modal fade" id="addCarrierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus me-2 text-primary"></i>Thêm Nhà Vận Chuyển</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="add_carrier">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add-ten" class="form-label">Tên Nhà Xe / Đơn vị <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" id="add-ten" name="ten_nha_xe" required placeholder="Ví dụ: Giao Hàng Nhanh">
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="add-sdt" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-dark border-secondary text-light" id="add-sdt" name="so_dien_thoai" required placeholder="Ví dụ: 19001200">
                            </div>
                            <div class="col-6">
                                <label for="add-email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control bg-dark border-secondary text-light" id="add-email" name="email" required placeholder="cskh@gmail.com">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-diachi" class="form-label">Địa Chỉ Hoạt Động <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" id="add-diachi" name="dia_chi" required placeholder="Địa chỉ trụ sở chính">
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <input type="hidden" name="loai_dich_vu" value="tietkiem">
                                <label for="add-phi" class="form-label">Phí Cơ Bản (đ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control bg-dark border-secondary text-light" id="add-phi" name="phi_co_ban" min="0" required value="15000">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="add-status" class="form-label">Trạng Thái</label>
                            <select class="form-select bg-dark border-secondary text-light" id="add-status" name="trang_thai">
                                <option value="active" selected>Hoạt động</option>
                                <option value="inactive">Tạm dừng</option>
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

    <!-- Modal: Sửa nhà vận chuyển -->
    <div class="modal fade" id="editCarrierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen me-2 text-warning"></i>Chỉnh Sửa Nhà Vận Chuyển</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="edit_carrier">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-ten" class="form-label">Tên Nhà Xe / Đơn vị <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" id="edit-ten" name="ten_nha_xe" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="edit-sdt" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-dark border-secondary text-light" id="edit-sdt" name="so_dien_thoai" required>
                            </div>
                            <div class="col-6">
                                <label for="edit-email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control bg-dark border-secondary text-light" id="edit-email" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-diachi" class="form-label">Địa Chỉ Hoạt Động <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-dark border-secondary text-light" id="edit-diachi" name="dia_chi" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <input type="hidden" id="edit-dichvu" name="loai_dich_vu" value="tietkiem">
                                <label for="edit-phi" class="form-label">Phí Cơ Bản (đ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control bg-dark border-secondary text-light" id="edit-phi" name="phi_co_ban" min="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-status" class="form-label">Trạng Thế</label>
                            <select class="form-select bg-dark border-secondary text-light" id="edit-status" name="trang_thai">
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Tạm dừng</option>
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

    <!-- Modal: Xóa nhà vận chuyển -->
    <div class="modal fade" id="deleteCarrierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card">
                <div class="modal-header border-secondary border-opacity-25">
                    <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-trash-can me-2"></i>Xác Nhận Xóa Nhà Vận Chuyển</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="delete_carrier">
                    <input type="hidden" id="delete-id" name="id">
                    <input type="hidden" id="delete-name-hidden" name="ten_nha_xe_delete">
                    <div class="modal-body text-center py-4">
                        <i class="fa-solid fa-circle-exclamation text-danger fa-4x mb-3"></i>
                        <p class="fs-5 mb-0">Bạn có chắc chắn muốn xóa nhà vận chuyển <strong class="text-warning" id="delete-name">Nhà xe --</strong>?</p>
                        <span class="text-secondary fs-7">Hành động này không thể hoàn tác và sẽ xóa hoàn toàn nhà xe khỏi hệ thống.</span>
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
            <div class="toast show bg-<?php echo $toast['type'] === 'danger' ? 'danger' : 'success'; ?> border-0 text-white" role="alert" aria-live="assertive" aria-atomic="true">
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
    
    <!-- Custom script to handle Edit/Delete modals inputs population -->
    <script>
        // Populate edit modal fields
        document.querySelectorAll('.edit-carrier-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.getAttribute('data-id');
                document.getElementById('edit-ten').value = this.getAttribute('data-name');
                document.getElementById('edit-sdt').value = this.getAttribute('data-phone');
                document.getElementById('edit-email').value = this.getAttribute('data-email');
                document.getElementById('edit-diachi').value = this.getAttribute('data-address');
                document.getElementById('edit-phi').value = this.getAttribute('data-fee');
                document.getElementById('edit-status').value = this.getAttribute('data-status');
            });
        });

        // Populate delete modal fields
        document.querySelectorAll('.delete-carrier-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                document.getElementById('delete-id').value = id;
                document.getElementById('delete-name').textContent = name;
                document.getElementById('delete-name-hidden').value = name;
            });
        });

        // Auto hide toast alerts after 4s
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
