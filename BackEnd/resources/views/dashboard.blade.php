<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hệ thống quản lý phòng học</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .sidebar {
            width: 280px;
            min-height: 100vh;
            background: white;
            border-right: 1px solid #e9ecef;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px 0;
        }
        .main-content {
            margin-left: 280px;
            padding: 20px 30px;
            min-height: 100vh;
        }
        .nav-link {
            color: #333;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .nav-link:hover {
            background-color: #f8f9fa;
        }
        .nav-link.active {
            background-color: #f0f2f5;
            font-weight: 500;
        }
        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        .top-bar {
            background: white;
            padding: 15px 30px;
            margin: -20px -30px 30px -30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stats-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            height: 100%;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        .stats-number {
            font-size: 2.8rem;
            font-weight: 600;
            margin: 15px 0;
            color: #0d6efd;
            line-height: 1;
        }
        .stats-number.danger {
            color: #dc3545;
        }
        .user-actions {
            display: flex;
            gap: 25px;
            align-items: center;
        }
        .user-actions a {
            color: #666;
            text-decoration: none;
            font-size: 1.2rem;
        }
        .user-actions a:hover {
            color: #333;
        }
        .page-title {
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 25px;
        }
        .stats-title {
            color: #666;
            font-size: 0.95rem;
            font-weight: 500;
            margin: 0;
        }
        .stats-card .icon {
            color: #999;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="px-4 mb-4">
            <img src="/images/logoTLU.png" alt="Logo" class="img-fluid" style="max-width: 150px;">
        </div>
        <div class="nav flex-column">
            <a href="/dashboard" class="nav-link active">
                <i class="fas fa-home"></i>
                Trang chủ
            </a>
            <a href="/rooms" class="nav-link">
                <i class="fas fa-calendar"></i>
                Phòng học
            </a>
            <a href="/users" class="nav-link">
                <i class="fas fa-user"></i>
                Tài khoản
            </a>
            <a href="/devices" class="nav-link">
                <i class="fas fa-desktop"></i>
                Thiết bị
            </a>
            <a href="/settings" class="nav-link">
                <i class="fas fa-cog"></i>
                Cài đặt
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h4 class="mb-0">Xin chào, {{ Auth::user()->full_name }}</h4>
            <div class="user-actions">
                <a href="/messages" title="Tin nhắn">
                    <i class="far fa-envelope"></i>
                </a>
                <a href="/notifications" title="Thông báo">
                    <i class="far fa-bell"></i>
                </a>
                <a href="/profile" title="Tài khoản">
                    <i class="far fa-user"></i>
                </a>
            </div>
        </div>

        <h5 class="page-title">Trang chủ</h5>

        <!-- Stats Cards -->
        <div class="row g-3">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="stats-title">Tổng số phòng</h6>
                        <i class="fas fa-chevron-right icon"></i>
                    </div>
                    <div class="stats-number">{{ App\Models\Room::count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="stats-title">Số phòng trống</h6>
                        <i class="fas fa-chevron-right icon"></i>
                    </div>
                    <div class="stats-number">{{ App\Models\Room::where('status', 'available')->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="stats-title">Danh sách chờ</h6>
                        <i class="fas fa-chevron-right icon"></i>
                    </div>
                    <div class="stats-number">{{ App\Models\Booking::where('status', 'pending')->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="stats-title">Phòng đang bảo trì</h6>
                        <i class="fas fa-chevron-right icon"></i>
                    </div>
                    <div class="stats-number danger">{{ App\Models\Room::where('status', 'maintenance')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
