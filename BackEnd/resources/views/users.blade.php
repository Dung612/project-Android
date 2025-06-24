<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tài khoản</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f4f7; margin: 0; padding: 0; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #fff; box-shadow: 2px 0 10px rgba(0,0,0,0.04); padding: 30px 0 0 0; display: flex; flex-direction: column; align-items: center; }
        .sidebar ul { list-style: none; padding: 0; width: 100%; }
        .sidebar li { width: 100%; margin-bottom: 18px; }
        .sidebar a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #222 !important;
            padding: 10px 30px;
            border-radius: 6px;
            transition: background 0.2s;
        }
        .sidebar a:hover, .sidebar .active {
            background: #f2f4f7 !important;
            color: #222 !important;
        }
        .sidebar svg { margin-right: 12px; }
        .main {
            flex: 1;
            padding: 40px 40px 0 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            padding: 32px 28px;
            margin-bottom: 40px;
        }
        .main h2 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 24px;
        }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .topbar .icons { display: flex; gap: 24px; }
        .topbar .icons svg { width: 22px; height: 22px; cursor: pointer; }
        .page-title { font-size: 22px; font-weight: bold; margin-bottom: 24px; }
        .table-container { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); padding: 28px 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 12px 8px; text-align: left; }
        th { color: #1a237e; font-weight: 600; border-bottom: 1px solid #e0e0e0; }
        tr:not(:last-child) { border-bottom: 1px solid #f0f0f0; }
        .search-bar { display: flex; align-items: center; margin-bottom: 16px; }
        .search-bar input { padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; outline: none; width: 220px; margin-right: 10px; }
        .search-bar svg { width: 20px; height: 20px; color: #1a237e; }
        .btn { padding: 8px 18px; border: none; border-radius: 6px; background: #1976d2; color: #fff; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn:hover { background: #0d47a1; }
        .edit-btn {
            background: #2196f3;
            color: #fff;
            border: none;
            border-radius: 4px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: bold;
            margin-right: 6px;
            padding: 0;
            transition: background 0.2s;
        }
        .edit-btn:hover {
            background: #1565c0;
        }
        .delete-btn {
            background: #e53935;
            color: #fff;
            border: none;
            border-radius: 4px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: bold;
            padding: 0;
            transition: background 0.2s;
        }
        .delete-btn:hover {
            background: #b71c1c;
        }
        .pagination { display: flex; justify-content: center; align-items: center; margin-top: 18px; gap: 8px; }
        .pagination button { border: none; background: none; font-size: 18px; cursor: pointer; color: #1976d2; }
        .pagination .active { font-weight: bold; border-bottom: 2px solid #1976d2; }
        .confirm-modal { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; z-index: 1000; }
        .confirm-box { background: #fff; padding: 32px 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.12); text-align: center; }
        .confirm-box button { margin: 0 12px; }
        .role-list { display: flex; flex-wrap: wrap; gap: 6px; }
        .role-tag { background: #e3e6f3; color: #1a237e; border-radius: 4px; padding: 2px 8px; font-size: 13px; }
        .multiselect { width: 100%; min-height: 36px; border: 1px solid #ccc; border-radius: 6px; padding: 6px; background: #fff; display: flex; flex-wrap: wrap; gap: 4px; }
        .multiselect label { margin-right: 8px; }
        .action-btns {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .sidebar ul ul {
            padding-left: 24px;
            margin-top: 0;
            margin-bottom: 0;
        }
        .sidebar ul ul li {
            margin-bottom: 10px;
        }
        .sidebar ul ul a {
            font-size: 15px;
            opacity: 0.95;
            color: #222 !important;
            background: none !important;
            text-decoration: none !important;
            display: block;
            padding-left: 0;
        }
        .sidebar-sub .sidebar-child.active {
            background: #e3e6f3 !important;
            color: #1a237e !important;
            font-weight: bold;
        }
        .sidebar-sub .sidebar-child {
            padding-left: 40px;
            font-size: 15px;
            opacity: 0.95;
            color: #222 !important;
            background: none !important;
            text-decoration: none !important;
            display: block;
            border-radius: 4px;
            margin-bottom: 4px;
            transition: background 0.2s;
        }
        .btn.active {
            background: #0d47a1 !important;
            color: #fff !important;
            border: 1px solid #0d47a1;
        }
        .filter-link {
            text-decoration: none;
            display: inline-block;
        }
        /* Chuẩn hóa input/select trong modal */
        .confirm-box input[type="text"],
        .confirm-box input[type="email"],
        .confirm-box input[type="password"],
        .confirm-box select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            margin-top: 4px;
            margin-bottom: 14px;
            box-sizing: border-box;
            background: #fafbfc;
            transition: border 0.2s;
        }
        .confirm-box input[type="text"]:focus,
        .confirm-box input[type="email"]:focus,
        .confirm-box input[type="password"]:focus,
        .confirm-box select:focus {
            border: 1.5px solid #1976d2;
            outline: none;
            background: #fff;
        }
        .confirm-box label {
            display: block;
            font-weight: 500;
            margin-bottom: 2px;
            text-align: left;
        }
        .confirm-box .btn {
            min-width: 90px;
            margin-left: 8px;
            margin-right: 0;
        }
        .confirm-box .btn:first-child {
            margin-left: 0;
        }
        .confirm-box .btn:last-child {
            margin-right: 0;
        }
        .confirm-box .form-row {
            margin-bottom: 14px;
        }
        .confirm-box .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="sidebar">
        <ul>
            <li><a href="/dashboard" id="sidebar-dashboard"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9"/><path d="M9 21V9h6v12"/></svg>Trang chủ</a></li>
            <li><a href="/rooms" id="sidebar-rooms"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M16 3v4"/><path d="M8 3v4"/></svg>Phòng học</a></li>
            <li>
                <a href="#" id="sidebar-users" class="sidebar-parent"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>Tài khoản</a>
            </li>
            <li><a href="#" id="sidebar-device"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 4v16"/><path d="M16 4v16"/><path d="M4 12h16"/></svg>Thiết bị</a></li>
            <li><a href="#" id="sidebar-settings"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>Cài đặt</a></li>
        </ul>
    </nav>
    <main class="main">
        <div class="page-title">Danh sách tài khoản</div>
        <div class="table-container">
            <div class="search-bar">
                <input type="text" id="user-search" placeholder="Nhập tên hoặc email">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <button class="btn" style="margin-left:auto;" id="add-user-btn">Thêm tài khoản</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Chỉnh sửa</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                </tbody>
            </table>
            <div class="pagination" id="user-pagination"></div>
        </div>
        <div id="user-confirm-modal" class="confirm-modal" style="display:none;">
            <div class="confirm-box">
                <div style="margin-bottom:18px;font-size:17px;">Bạn có thật sự muốn xóa tài khoản không?</div>
                <div class="form-actions" style="justify-content:center;">
                    <button class="btn" id="user-confirm-yes">Có</button>
                    <button class="btn" id="user-confirm-no" style="background:#e53935;">Không</button>
                </div>
            </div>
        </div>
        <!-- Popup form Thêm/Sửa tài khoản -->
        <div id="user-modal" class="confirm-modal" style="display:none;">
            <div class="confirm-box" style="min-width:350px;">
                <h3 id="user-modal-title">Thêm tài khoản</h3>
                <form id="user-form">
                    <input type="hidden" id="user-id">
                    <div class="form-row">
                        <label for="user-name">Họ và tên</label>
                        <input type="text" id="user-name" placeholder="Họ và tên">
                    </div>
                    <div class="form-row">
                        <label for="user-email">Email</label>
                        <input type="email" id="user-email" placeholder="Email">
                    </div>
                    <div class="form-row">
                        <label for="user-role-id">Vai trò</label>
                        <select id="user-role-id"></select>
                    </div>
                    <div class="form-row">
                        <label for="user-password">Mật khẩu</label>
                        <input type="password" id="user-password" placeholder="Mật khẩu">
                    </div>
                    <div class="form-row">
                        <label style="font-weight:400;"><input type="checkbox" id="user-verified" style="width:auto;display:inline-block;"> Đã xác thực</label>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn" id="user-save">Lưu</button>
                        <button type="button" class="btn" id="user-cancel">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Thông báo -->
        <div id="user-alert-box" style="display:none;position:fixed;top:30px;right:30px;z-index:2000;padding:16px 28px;border-radius:8px;font-weight:bold;"></div>
    </main>
</div>
<script src="/js/users.js"></script>
<script>initUserEvents();</script>
</body>
</html>