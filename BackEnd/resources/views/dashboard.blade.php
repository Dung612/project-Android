<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 220px;
            background: #fff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.04);
            padding: 30px 0 0 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }
        .sidebar li {
            width: 100%;
            margin-bottom: 18px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #222;
            padding: 10px 30px;
            border-radius: 6px;
            transition: background 0.2s;
        }
        .sidebar a:hover, .sidebar .active {
            background: #f2f4f7;
        }
        .sidebar svg {
            margin-right: 12px;
        }
        .main {
            flex: 1;
            padding: 40px 40px 0 40px;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .topbar .icons {
            display: flex;
            gap: 24px;
        }
        .topbar .icons svg {
            width: 22px;
            height: 22px;
            cursor: pointer;
        }
        .dashboard-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 24px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            padding: 28px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 110px;
            position: relative;
        }
        .stat-card .arrow {
            position: absolute;
            top: 18px;
            right: 18px;
            color: #1a237e;
        }
        .stat-card .label {
            font-size: 16px;
            color: #333;
            margin-bottom: 12px;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: bold;
            color: #1a237e;
        }
        .stat-card.red .value {
            color: #e53935;
        }
        @media (max-width: 900px) {
            .stats { grid-template-columns: 1fr; }
        }
        .sidebar-sub .sidebar-child {
            font-size: 14px;
            padding-left: 32px;
            color: #222 !important;
            opacity: 0.85;
            display: block;
            border-radius: 4px;
            margin-bottom: 4px;
            transition: background 0.2s;
        }
        .sidebar-sub .sidebar-child.active {
            background: #e3e6f3 !important;
            color: #1a237e !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="sidebar">
        <div style="width:100%;display:flex;flex-direction:column;align-items:center;margin-bottom:28px;">
            <img src="/images/logoTLU.png" alt="Logo TLU" style="width:200px;height:auto;display:block;margin-bottom:22px;margin-top:10px;" />
        </div>
        <ul>
            <li><a href="#" class="active"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9"/><path d="M9 21V9h6v12"/></svg>Trang chủ</a></li>
            <li><a href="#" id="sidebar-rooms"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M16 3v4"/><path d="M8 3v4"/></svg>Phòng học</a></li>
            <li><a href="/users" id="sidebar-users"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>Tài khoản</a></li>
            <li><a href="#" id="sidebar-device"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 4v16"/><path d="M16 4v16"/><path d="M4 12h16"/></svg>Thiết bị</a></li>
            <li><a href="#" id="sidebar-settings"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>Cài đặt</a></li>
        </ul>
        <button id="logout-btn" style="margin: 30px 0 0 0; padding: 10px 30px; border: none; background: #e53935; color: #fff; border-radius: 6px; cursor: pointer; width: 80%;">
            Đăng xuất
        </button>
    </nav>
    <main class="main">
        <div class="topbar">
            <div id="greeting">Xin chào, <span id="name">Admin</span>.</div>
            <div class="icons">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="M22 6l-10 7L2 6"/></svg>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
            </div>
        </div>
        <div class="dashboard-title">Trang chủ</div>
        <div class="stats">
            <div class="stat-card">
                <div class="label">Tổng số phòng</div>
                <div class="value" id="total-rooms">35</div>
            </div>
            <div class="stat-card">
                <div class="label">Số phòng trống</div>
                <div class="value" id="empty-rooms">5</div>
            </div>
            <div class="stat-card">
                <div class="label">Danh sách chờ</div>
                <div class="value" id="waiting-list">10</div>
            </div>
            <div class="stat-card red">
                <div class="label">Phòng đang bảo trì</div>
                <div class="value" id="maintenance-rooms">2</div>
            </div>
            <div class="stat-card" id="device-card" style="cursor:pointer;">
                <div class="label">Thiết bị</div>
                <div class="value" id="total-devices">...</div>
            </div>
        </div>
        <div id="main-content"></div>
    </main>
</div>
<script>
    // Lấy tên user từ API như cũ
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login';
    }
    fetch('/api/user', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Unauthorized');
        return res.json();
    })
    .then(user => {
        document.getElementById('name').textContent = user.full_name || user.name || 'Admin';
    })
    .catch(() => {
        alert('Token hết hạn, hãy đăng nhập lại.');
        localStorage.removeItem('token');
        window.location.href = '/login';
    });

    // Lấy số liệu dashboard
    fetch('/api/dashboard-stats', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(res => res.json())
    .then(stats => {
        document.getElementById('total-rooms').textContent = stats.total_rooms;
        document.getElementById('empty-rooms').textContent = stats.empty_rooms;
        document.getElementById('waiting-list').textContent = stats.waiting_list;
        document.getElementById('maintenance-rooms').textContent = stats.maintenance_rooms;
    });

    // Lấy số lượng thiết bị
    fetch('/api/devices?all=1', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(res => res.json())
    .then(result => {
        const devices = result.data || result;
        document.getElementById('total-devices').textContent = devices.length;
    });

    // Hàm gán lại sự kiện click cho toàn bộ sidebar
    function bindSidebarEvents() {
        // Trang chủ
        const sidebarHome = document.querySelector('.sidebar a[href="#"], .sidebar a.active');
        if (sidebarHome) {
            sidebarHome.onclick = function(e) {
                e.preventDefault();
                window.location.href = '/dashboard';
            };
        }
        // Phòng học
        const sidebarRooms = document.getElementById('sidebar-rooms');
        if (sidebarRooms) {
            sidebarRooms.onclick = function(e) {
                e.preventDefault();
                document.querySelector('.dashboard-title').style.display = 'none';
                document.querySelector('.stats').style.display = 'none';
                loadRoomsView();
            };
        }
        // Tài khoản
        const sidebarUsers = document.querySelector('.sidebar a[href="/users"]');
        if (sidebarUsers) {
            sidebarUsers.onclick = function(e) {
                e.preventDefault();
                document.querySelector('.dashboard-title').style.display = 'none';
                document.querySelector('.stats').style.display = 'none';
                loadUsersView();
            };
        }
        // Thiết bị
        const sidebarDevice = document.getElementById('sidebar-device');
        if (sidebarDevice) {
            sidebarDevice.onclick = function(e) {
                e.preventDefault();
                document.querySelector('.dashboard-title').style.display = 'none';
                document.querySelector('.stats').style.display = 'none';
                loadDevicesView();
            };
        }
        // Cài đặt
        const sidebarSetting = document.getElementById('sidebar-settings');
        if (sidebarSetting) {
            sidebarSetting.onclick = function(e) {
                e.preventDefault();
                document.querySelector('.dashboard-title').style.display = 'none';
                document.querySelector('.stats').style.display = 'none';
                const main = document.getElementById('main-content');
                main.innerHTML = `
                <style>
                .setting-tabs { display: flex; gap: 18px; margin-bottom: 32px; }
                .setting-tab-btn {
                  font-size: 18px;
                  font-weight: 600;
                  padding: 16px 38px;
                  border: none;
                  border-radius: 16px;
                  background: #1976d2;
                  color: #fff;
                  cursor: pointer;
                  transition: background 0.18s, color 0.18s, box-shadow 0.18s;
                  box-shadow: 0 2px 10px rgba(25,118,210,0.08);
                  margin-bottom: 8px;
                  outline: none;
                }
                .setting-tab-btn.active, .setting-tab-btn:hover {
                  background: #1565c0;
                  color: #fff;
                  box-shadow: 0 4px 18px rgba(25,118,210,0.13);
                }
                .setting-card { background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 36px 32px 28px 32px; margin-bottom: 32px; max-width: 900px; margin-left:auto; margin-right:auto; }
                .setting-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
                .setting-title { font-size: 22px; font-weight: bold; color: #1a237e; }
                .setting-add-btn { padding: 10px 28px; font-size: 17px; border-radius: 8px; background: #43a047; color: #fff; border: none; font-weight: 600; cursor: pointer; transition: background 0.2s; }
                .setting-add-btn:hover { background: #2e7d32; }
                .setting-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; }
                .setting-table th, .setting-table td { padding: 16px 12px; text-align: left; font-size: 16px; }
                .setting-table th { background: #f2f4f7; color: #1a237e; font-weight: 700; border-bottom: 2px solid #e0e0e0; }
                .setting-table tr:not(:last-child) { border-bottom: 1px solid #f0f0f0; }
                .action-btns { display: flex; gap: 8px; }
                .edit-btn, .delete-btn { width: 36px; height: 36px; font-size: 22px; border-radius: 8px; }
                .edit-btn { background: #2196f3; color: #fff; border: none; font-weight: bold; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
                .edit-btn:hover { background: #1565c0; }
                .delete-btn { background: #e53935; color: #fff; border: none; font-weight: bold; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
                .delete-btn:hover { background: #b71c1c; }
                @media (max-width: 700px) {
                  .setting-card { padding: 18px 6px; }
                  .setting-tabs { flex-direction: column; gap: 10px; }
                  .setting-tab-btn { width: 100%; }
                  .setting-table th, .setting-table td { padding: 10px 6px; font-size: 15px; }
                }
                /* Modal */
                .confirm-modal { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(30,40,80,0.18); display: flex; align-items: center; justify-content: center; z-index: 2000; }
                .confirm-box { background: #fff; padding: 38px 44px; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.13); min-width:320px; max-width:96vw; }
                .confirm-box h3 { font-size: 20px; font-weight: bold; margin-bottom: 18px; color: #1976d2; }
                .confirm-box label { font-weight: 600; color: #222; }
                .confirm-box input, .confirm-box textarea { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 7px; margin-top: 6px; margin-bottom: 18px; font-size: 16px; }
                .confirm-box textarea { min-height: 60px; }
                .confirm-box .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px; }
                .confirm-box .btn { padding: 9px 22px; font-size: 16px; border-radius: 7px; }
                .confirm-box .btn#setting-cancel, .confirm-box .btn#setting-confirm-no { background: #e53935; }
                .confirm-box .btn#setting-cancel:hover, .confirm-box .btn#setting-confirm-no:hover { background: #b71c1c; }
                .confirm-box .btn#setting-save, .confirm-box .btn#setting-confirm-yes { background: #1976d2; color: #fff; }
                .confirm-box .btn#setting-save:hover, .confirm-box .btn#setting-confirm-yes:hover { background: #0d47a1; }
                .setting-alert-box { display:none;position:fixed;top:30px;right:30px;z-index:3000;padding:16px 28px;border-radius:8px;font-weight:bold; }
                </style>
                <div class="setting-tabs">
                  <button class="setting-tab-btn" id="btn-roles">Vai trò</button>
                  <button class="setting-tab-btn" id="btn-room-types">Chức năng phòng</button>
                  <button class="setting-tab-btn" id="btn-device-types">Loại thiết bị</button>
                </div>
                <div style="display:flex;gap:18px;margin-bottom:32px;">
                  <button class="setting-tab-btn" id="btn-profile">Thông tin cá nhân</button>
                </div>
                <div id="setting-table"></div>
                <div id="setting-modal" class="confirm-modal" style="display:none;z-index:2001;">
                    <div class="confirm-box">
                        <h3 id="setting-modal-title"></h3>
                        <form id="setting-form">
                            <input type="hidden" id="setting-id">
                            <div class="form-row">
                                <label for="setting-name">Tên</label>
                                <input type="text" id="setting-name" required>
                            </div>
                            <div class="form-row">
                                <label for="setting-description">Mô tả</label>
                                <input type="text" id="setting-description" class="form-control" placeholder="Mô tả" style="margin-bottom:14px;">
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn" id="setting-save">Lưu</button>
                                <button type="button" class="btn" id="setting-cancel">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="setting-confirm-modal" class="confirm-modal" style="display:none;z-index:2002;">
                    <div class="confirm-box">
                        <div id="setting-confirm-msg" style="margin-bottom:18px;font-size:17px;"></div>
                        <div class="form-actions" style="justify-content:center;">
                            <button class="btn" id="setting-confirm-yes">Có</button>
                            <button class="btn" id="setting-confirm-no">Không</button>
                        </div>
                    </div>
                </div>
                <div id="setting-alert-box" class="setting-alert-box"></div>
                `;
                let currentType = null;
                function setActiveTab(tab) {
                  document.querySelectorAll('.setting-tab-btn').forEach(btn=>btn.classList.remove('active'));
                  tab.classList.add('active');
                }
                function showAlert(msg, type = 'success') {
                    const box = document.getElementById('setting-alert-box');
                    if (!box) return;
                    box.textContent = msg;
                    box.style.background = type === 'success' ? '#43a047' : '#e53935';
                    box.style.color = '#fff';
                    box.style.display = 'block';
                    setTimeout(() => { box.style.display = 'none'; }, 2200);
                }
                function renderTable(headers, rows, type) {
                    let html = `<div class=\"setting-card\">`;
                    html += `<div class=\"setting-header\"><button class=\"setting-back-btn\" id=\"setting-back-btn\" style=\"margin-right:18px;font-size:22px;background:none;border:none;cursor:pointer;color:#1976d2;\">←</button><div class=\"setting-title\">${type==='role'?'Vai trò':type==='room-type'?'Chức năng phòng':'Loại thiết bị'}</div><button class=\"setting-add-btn\" id=\"setting-add-btn\">Thêm</button></div>`;
                    html += '<table class="setting-table">';
                    html += '<thead><tr>' + headers.map(h=>`<th>${h}</th>`).join('') + '<th></th></tr></thead>';
                    html += '<tbody>' + (rows.length ? rows.map((r,i)=>{
                        let actionBtns = '';
                        if(type!=='role' || (r[0]!=='admin' && r[0]!=='manager')) actionBtns += `<button class=\"btn edit-btn\" data-idx=\"${i}\">+</button>`;
                        if(type!=='role' || (r[0]!=='admin' && r[0]!=='manager')) actionBtns += `<button class=\"btn delete-btn\" data-idx=\"${i}\">×</button>`;
                        return '<tr>'+r.map(c=>`<td>${c}</td>`).join('')+`<td><div class=\"action-btns\">${actionBtns}</div></td></tr>`;
                    }).join('') : `<tr><td colspan=\"${headers.length+1}\" style=\"text-align:center;color:#888;\">Không có dữ liệu</td></tr>`) + '</tbody>';
                    html += '</table></div>';
                    document.getElementById('setting-table').innerHTML = html;
                    document.getElementById('setting-back-btn').onclick = function() {
                        document.getElementById('setting-table').innerHTML = '';
                        document.querySelectorAll('.setting-tab-btn').forEach(btn=>btn.classList.remove('active'));
                    };
                }
                // CRUD logic
                let dataCache = [];
                function openModal(type, mode, data) {
                    document.getElementById('setting-modal-title').textContent = (mode==='add'?'Thêm ':'Sửa ') + (type==='role'?'vai trò':type==='room-type'?'chức năng phòng':'loại thiết bị');
                    document.getElementById('setting-id').value = data?.id||'';
                    document.getElementById('setting-name').value = data?.name||'';
                    document.getElementById('setting-description').value = data?.description||'';
                    document.querySelector('#setting-modal label[for=setting-name]').textContent = 'Tên';
                    document.querySelector('#setting-modal label[for=setting-description]').textContent = 'Mô tả';
                    document.getElementById('setting-name').placeholder = 'Tên';
                    document.getElementById('setting-description').placeholder = 'Mô tả';
                    document.getElementById('setting-modal').style.display = 'flex';
                    // Chỉnh toàn bộ form về giao diện dọc
                    setTimeout(function() {
                      var formRows = document.querySelectorAll('#setting-form .form-row');
                      formRows.forEach(function(row) {
                        row.style.display = 'flex';
                        row.style.flexDirection = 'column';
                        row.style.alignItems = 'stretch';
                        row.style.marginBottom = '18px';
                        var label = row.querySelector('label');
                        if(label) {
                          label.style.marginBottom = '6px';
                          label.style.fontWeight = 'bold';
                          label.style.fontSize = '15px';
                        }
                        var input = row.querySelector('input, select, textarea');
                        if(input) {
                          input.style.fontSize = '16px';
                          input.style.height = '38px';
                          input.style.padding = '0 10px';
                          input.style.background = '#fff';
                          input.style.border = '1px solid #ccc';
                          input.style.borderRadius = '6px';
                        }
                      });
                    }, 0);
                }
                function closeModal() {
                    document.getElementById('setting-modal').style.display = 'none';
                }
                function openConfirm(msg, onYes) {
                    document.getElementById('setting-confirm-msg').textContent = msg;
                    document.getElementById('setting-confirm-modal').style.display = 'flex';
                    document.getElementById('setting-confirm-yes').onclick = function() {
                        document.getElementById('setting-confirm-modal').style.display = 'none';
                        onYes();
                    };
                    document.getElementById('setting-confirm-no').onclick = function() {
                        document.getElementById('setting-confirm-modal').style.display = 'none';
                    };
                }
                // Loaders
                function loadRoles() {
                    currentType = 'role';
                    setActiveTab(document.getElementById('btn-roles'));
                    fetch('/api/roles', {headers:{'Authorization':'Bearer '+localStorage.getItem('token')}})
                    .then(res=>res.json()).then(data=>{
                        dataCache = (data.data||data);
                        const rows = dataCache.map(r=>[r.name, r.description||'']);
                        renderTable(['Tên vai trò','Mô tả'], rows, 'role');
                        bindTableEvents('role');
                    });
                }
                function loadRoomTypes() {
                    currentType = 'room-type';
                    setActiveTab(document.getElementById('btn-room-types'));
                    fetch('/api/room-types', {headers:{'Authorization':'Bearer '+localStorage.getItem('token')}})
                    .then(res=>res.json()).then(data=>{
                        dataCache = (data.data||data);
                        const rows = dataCache.map(r=>[r.name, r.description||'']);
                        renderTable(['Tên chức năng','Mô tả'], rows, 'room-type');
                        bindTableEvents('room-type');
                    });
                }
                function loadDeviceTypes() {
                    currentType = 'device-type';
                    setActiveTab(document.getElementById('btn-device-types'));
                    fetch('/api/device-types', {headers:{'Authorization':'Bearer '+localStorage.getItem('token')}})
                    .then(res=>res.json()).then(data=>{
                        dataCache = (data.data||data);
                        const rows = dataCache.map(r=>[r.name, r.description||'']);
                        renderTable(['Tên loại thiết bị','Mô tả'], rows, 'device-type');
                        bindTableEvents('device-type');
                    });
                }
                // Table events
                function bindTableEvents(type) {
                    document.getElementById('setting-add-btn').onclick = function() { openModal(type, 'add'); };
                    document.querySelectorAll('.edit-btn').forEach(btn => {
                        btn.onclick = function() {
                            const idx = btn.getAttribute('data-idx');
                            openModal(type, 'edit', dataCache[idx]);
                        };
                    });
                    document.querySelectorAll('.delete-btn').forEach(btn => {
                        btn.onclick = function() {
                            const idx = btn.getAttribute('data-idx');
                            const item = dataCache[idx];
                            if(type==='role' && (item.name==='admin'||item.name==='manager')) {
                                showAlert('Không được xóa vai trò admin hoặc manager!','error');
                                return;
                            }
                            openConfirm('Bạn có chắc muốn xóa không?', function() {
                                let url = type==='role'?`/api/roles/${item.id}`:type==='room-type'?`/api/room-types/${item.id}`:`/api/device-types/${item.id}`;
                                fetch(url, {
                                    method:'DELETE',
                                    headers:{'Authorization':'Bearer '+localStorage.getItem('token')}
                                }).then(async res=>{
                                    if(res.ok) {
                                        showAlert('Xóa thành công!');
                                        reloadTable();
                                    } else {
                                        let err = await res.json();
                                        showAlert(err.message||'Có lỗi xảy ra!','error');
                                    }
                                });
                            });
                        };
                    });
                }
                // Modal events
                document.getElementById('setting-cancel').onclick = closeModal;
                document.getElementById('setting-form').onsubmit = function(e) {
                    e.preventDefault();
                    const id = document.getElementById('setting-id').value;
                    const name = document.getElementById('setting-name').value.trim();
                    const description = document.getElementById('setting-description').value.trim();
                    if(!name) { showAlert('Vui lòng nhập tên!','error'); return; }
                    let url, method, body;
                    if(currentType==='role') {
                        url = id?`/api/roles/${id}`:'/api/roles';
                        method = id?'PUT':'POST';
                        body = JSON.stringify({name, description});
                    } else if(currentType==='room-type') {
                        url = id?`/api/room-types/${id}`:'/api/room-types';
                        method = id?'PUT':'POST';
                        body = JSON.stringify({name, description});
                    } else {
                        url = id?`/api/device-types/${id}`:'/api/device-types';
                        method = id?'PUT':'POST';
                        body = JSON.stringify({name, description});
                    }
                    fetch(url, {
                        method,
                        headers:{'Authorization':'Bearer '+localStorage.getItem('token'),'Content-Type':'application/json'},
                        body
                    }).then(async res=>{
                        if(res.ok) {
                            showAlert(id?'Cập nhật thành công!':'Thêm mới thành công!');
                            closeModal();
                            reloadTable();
                        } else {
                            let err = await res.json();
                            showAlert(err.message||'Có lỗi xảy ra!','error');
                        }
                    });
                };
                function reloadTable() {
                    if(currentType==='role') loadRoles();
                    else if(currentType==='room-type') loadRoomTypes();
                    else loadDeviceTypes();
                }
                document.getElementById('btn-roles').onclick = function() { loadRoles(); };
                document.getElementById('btn-room-types').onclick = function() { loadRoomTypes(); };
                document.getElementById('btn-device-types').onclick = function() { loadDeviceTypes(); };
                document.getElementById('btn-profile').onclick = function() {
                  document.querySelectorAll('.setting-tab-btn').forEach(btn=>btn.classList.remove('active'));
                  document.getElementById('setting-table').innerHTML = '<div class="setting-card" id="profile-card" style="max-width:500px;margin:auto;">Đang tải...</div>';
                  fetch('/api/user', {headers:{'Authorization':'Bearer '+localStorage.getItem('token')}})
                  .then(res=>res.json()).then(user=>{
                    let roles = user.roles && user.roles.length ? user.roles.map(r=>r.name).join(', ') : '';
                    let html = `<div style=\"font-size:22px;font-weight:bold;color:#1a237e;margin-bottom:18px;display:flex;align-items:center;justify-content:center;position:relative;\"><button id=\"profile-back-btn\" style=\"position:absolute;left:0;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:22px;color:#1976d2;\">←</button><span style=\"flex:1;text-align:center;\">Thông tin cá nhân</span></div>`;
                    html += `<div style=\"margin-bottom:12px;\"><b>Họ tên:</b> <span id=\"profile-fullname\">${user.full_name||user.name||''}</span></div>`;
                    html += `<div style=\"margin-bottom:12px;\"><b>Email:</b> <span id=\"profile-email\">${user.email||''}</span></div>`;
                    html += `<div style=\"margin-bottom:12px;\"><b>Vai trò:</b> ${roles}</div>`;
                    html += `<div style=\"margin-bottom:12px;\"><b>Trạng thái:</b> ${user.is_verified?'Đã xác thực':'Chưa xác thực'}</div>`;
                    if(user.created_at) html += `<div style=\"margin-bottom:12px;\"><b>Ngày tạo:</b> ${user.created_at}</div>`;
                    if(user.updated_at) html += `<div style=\"margin-bottom:12px;\"><b>Cập nhật:</b> ${user.updated_at}</div>`;
                    html += `<div style=\"margin-top:22px;text-align:right;\"><button class=\"btn\" id=\"btn-edit-profile\" style=\"background:#2196f3;color:#fff;font-size:16px;padding:9px 22px;border-radius:7px;\">Cập nhật thông tin</button></div>`;
                    document.getElementById('profile-card').innerHTML = html;
                    // Gán lại sự kiện cho nút quay lại
                    document.getElementById('profile-back-btn').onclick = function() {
                      renderSettingHome();
                    };
                    // Gán lại sự kiện cho nút cập nhật thông tin
                    document.getElementById('btn-edit-profile').onclick = function() {
                      // Hiện popup sửa thông tin cá nhân
                      let modal = document.getElementById('setting-modal');
                      document.getElementById('setting-modal-title').textContent = 'Cập nhật thông tin cá nhân';
                      document.getElementById('setting-id').value = user.id;
                      document.getElementById('setting-name').value = user.full_name||user.name||'';
                      document.getElementById('setting-description').value = user.email||'';
                      document.getElementById('setting-name').placeholder = 'Họ tên';
                      document.getElementById('setting-description').placeholder = 'Email';
                      // Thêm trường mật khẩu mới nếu chưa có
                      if(!document.getElementById('setting-password')) {
                        const pwRow = document.createElement('div');
                        pwRow.className = 'form-row';
                        pwRow.innerHTML = '<label for="setting-password">Mật khẩu mới</label><input type="password" id="setting-password" class="form-control" placeholder="Để trống nếu không đổi">';
                        document.getElementById('setting-description').parentElement.insertAdjacentElement('afterend', pwRow);
                      } else {
                        document.getElementById('setting-password').value = '';
                      }
                      document.getElementById('setting-modal').style.display = 'flex';
                      // Chỉnh toàn bộ form về giao diện dọc
                      setTimeout(function() {
                        var formRows = document.querySelectorAll('#setting-form .form-row');
                        formRows.forEach(function(row) {
                          row.style.display = 'flex';
                          row.style.flexDirection = 'column';
                          row.style.alignItems = 'stretch';
                          row.style.marginBottom = '18px';
                          var label = row.querySelector('label');
                          if(label) {
                            label.style.marginBottom = '6px';
                            label.style.fontWeight = 'bold';
                            label.style.fontSize = '15px';
                          }
                          var input = row.querySelector('input, select, textarea');
                          if(input) {
                            input.style.fontSize = '16px';
                            input.style.height = '38px';
                            input.style.padding = '0 10px';
                            input.style.background = '#fff';
                            input.style.border = '1px solid #ccc';
                            input.style.borderRadius = '6px';
                          }
                        });
                      }, 0);
                      // Xóa lỗi cũ
                      Array.from(document.querySelectorAll('.field-error')).forEach(e=>e.remove());
                      // Gán lại submit
                      document.getElementById('setting-form').onsubmit = function(e) {
                        e.preventDefault();
                        // Xóa lỗi cũ
                        Array.from(document.querySelectorAll('.field-error')).forEach(e=>e.remove());
                        const full_name = document.getElementById('setting-name').value.trim();
                        const email = document.getElementById('setting-description').value.trim();
                        const password = document.getElementById('setting-password') ? document.getElementById('setting-password').value : '';
                        if(!full_name || !email) {
                          if(!full_name) document.getElementById('setting-name').insertAdjacentHTML('afterend', '<div class="field-error" style="color:#d32f2f;font-size:13px;margin-top:2px;">Vui lòng nhập họ tên</div>');
                          if(!email) document.getElementById('setting-description').insertAdjacentHTML('afterend', '<div class="field-error" style="color:#d32f2f;font-size:13px;margin-top:2px;">Vui lòng nhập email</div>');
                          return;
                        }
                        let body = { full_name, email };
                        if(password) body.password = password;
                        fetch('/api/user', {
                          method: 'PUT',
                          headers: { 'Authorization': 'Bearer '+localStorage.getItem('token'), 'Content-Type': 'application/json' },
                          body: JSON.stringify(body)
                        }).then(async res => {
                          if(res.ok) {
                            showAlert('Cập nhật thành công!');
                            document.getElementById('setting-modal').style.display = 'none';
                            document.getElementById('btn-profile').click();
                          } else {
                            let err = await res.json();
                            if(err.errors) {
                              if(err.errors.full_name) document.getElementById('setting-name').insertAdjacentHTML('afterend', `<div class=\"field-error\" style=\"color:#d32f2f;font-size:13px;margin-top:2px;\">${err.errors.full_name[0]}</div>`);
                              if(err.errors.email) document.getElementById('setting-description').insertAdjacentHTML('afterend', `<div class=\"field-error\" style=\"color:#d32f2f;font-size:13px;margin-top:2px;\">${err.errors.email[0]}</div>`);
                              if(err.errors.password && document.getElementById('setting-password')) document.getElementById('setting-password').insertAdjacentHTML('afterend', `<div class=\"field-error\" style=\"color:#d32f2f;font-size:13px;margin-top:2px;\">${err.errors.password[0]}</div>`);
                            } else {
                              showAlert(err.message||'Có lỗi xảy ra!','error');
                            }
                          }
                        });
                      };
                    };
                  });
                };
                showLogoutBtnInSetting();
            }
        }
        // Đảm bảo nút đăng xuất luôn gán sự kiện
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.onclick = function() {
                if (confirm('Bạn chắc chắn muốn đăng xuất?')) {
                    fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(() => {
                        localStorage.removeItem('token');
                        window.location.href = '/login';
                    })
                    .catch(() => {
                        localStorage.removeItem('token');
                        window.location.href = '/login';
                    });
                }
            };
        }
    }
    // Gọi hàm này khi trang vừa load và sau khi load động nội dung
    document.addEventListener('DOMContentLoaded', bindSidebarEvents);

    // Hàm load động giao diện phòng học vào dashboard
    function loadRoomsView() {
        fetch('/rooms')
            .then(res => res.text())
            .then(html => {
                const temp = document.createElement('div');
                temp.innerHTML = html;
                // Lấy CSS trong <style> của rooms.blade.php và nhúng vào <head>
                const style = temp.querySelector('style');
                if (style) {
                    let oldStyle = document.getElementById('rooms-style');
                    if (oldStyle) oldStyle.remove();
                    const newStyle = document.createElement('style');
                    newStyle.id = 'rooms-style';
                    newStyle.innerHTML = style.innerHTML;
                    document.head.appendChild(newStyle);
                }
                // Lấy phần <main>
                const main = temp.querySelector('.main');
                document.getElementById('main-content').innerHTML = main ? main.innerHTML : 'Không tìm thấy nội dung phòng học!';
                // Lấy và append các modal/popup vào body nếu chưa có
                ['confirm-modal', 'room-modal', 'device-modal', 'alert-box'].forEach(id => {
                    const modal = temp.querySelector(`#${id}`);
                    if (modal && !document.getElementById(id)) {
                        document.body.appendChild(modal);
                    }
                });
                // Nạp lại JS cho phòng học
                const script = document.createElement('script');
                script.src = '/js/rooms.js';
                script.onload = function() { if (typeof initRoomEvents === 'function') initRoomEvents(); };
                document.body.appendChild(script);
                // Gán lại sự kiện sidebar sau khi load động
                bindSidebarEvents();
            });
    }
    // Kiểm tra query string
    if (window.location.search.includes('tab=rooms')) {
        document.querySelector('.dashboard-title').style.display = 'none';
        document.querySelector('.stats').style.display = 'none';
        loadRoomsView();
    } else {
        document.getElementById('main-content').innerHTML = '';
    }

    // Hàm load động giao diện thiết bị vào dashboard
    function loadDevicesView() {
        fetch('/devices')
            .then(res => res.text())
            .then(html => {
                const temp = document.createElement('div');
                temp.innerHTML = html;
                // Lấy CSS trong <style> của devices.blade.php và nhúng vào <head>
                const style = temp.querySelector('style');
                if (style) {
                    let oldStyle = document.getElementById('devices-style');
                    if (oldStyle) oldStyle.remove();
                    const newStyle = document.createElement('style');
                    newStyle.id = 'devices-style';
                    newStyle.innerHTML = style.innerHTML;
                    document.head.appendChild(newStyle);
                }
                // Lấy phần <main>
                const main = temp.querySelector('.main');
                document.getElementById('main-content').innerHTML = main ? main.innerHTML : 'Không tìm thấy nội dung thiết bị!';
                // Nạp lại JS cho thiết bị
                const script = document.createElement('script');
                script.src = '/js/devices.js';
                script.onload = function() { if (typeof initDeviceEvents === 'function') initDeviceEvents(); };
                document.body.appendChild(script);
                // Gán lại sự kiện sidebar sau khi load động
                bindSidebarEvents();
            });
    }

    // Hàm load động giao diện tài khoản vào dashboard
    function loadUsersView() {
        fetch('/users')
            .then(res => res.text())
            .then(html => {
                const temp = document.createElement('div');
                temp.innerHTML = html;
                // Lấy CSS trong <style> của users.blade.php và nhúng vào <head>
                const style = temp.querySelector('style');
                if (style) {
                    let oldStyle = document.getElementById('users-style');
                    if (oldStyle) oldStyle.remove();
                    const newStyle = document.createElement('style');
                    newStyle.id = 'users-style';
                    newStyle.innerHTML = style.innerHTML;
                    document.head.appendChild(newStyle);
                }
                // Lấy phần <main>
                const main = temp.querySelector('.main');
                document.getElementById('main-content').innerHTML = main ? main.innerHTML : 'Không tìm thấy nội dung tài khoản!';
                // Nạp lại JS cho tài khoản
                const script = document.createElement('script');
                script.src = '/js/users.js';
                script.onload = function() { if (typeof initUserEvents === 'function') initUserEvents(); };
                document.body.appendChild(script);
                // Gán lại sự kiện sidebar sau khi load động
                bindSidebarEvents();
            });
    }

    // Thêm hàm renderSettingHome để hiển thị giao diện mặc định của Cài đặt và gán lại sự kiện cho các tab
    function renderSettingHome() {
      const main = document.getElementById('main-content');
      const settingStyle = `
        <style>
        .setting-tabs { display: flex; gap: 18px; margin-bottom: 32px; }
        .setting-tab-btn {
          font-size: 18px;
          font-weight: 600;
          padding: 16px 38px;
          border: none;
          border-radius: 16px;
          background: #1976d2;
          color: #fff;
          cursor: pointer;
          transition: background 0.18s, color 0.18s, box-shadow 0.18s;
          box-shadow: 0 2px 10px rgba(25,118,210,0.08);
          margin-bottom: 8px;
          outline: none;
        }
        .setting-tab-btn.active, .setting-tab-btn:hover {
          background: #1565c0;
          color: #fff;
          box-shadow: 0 4px 18px rgba(25,118,210,0.13);
        }
        </style>
      `;
      main.innerHTML = settingStyle + `
        <div class=\"setting-tabs\">\n          <button class=\"setting-tab-btn\" id=\"btn-roles\">Vai trò</button>\n          <button class=\"setting-tab-btn\" id=\"btn-room-types\">Chức năng phòng</button>\n          <button class=\"setting-tab-btn\" id=\"btn-device-types\">Loại thiết bị</button>\n        </div>\n        <div style=\"display:flex;gap:18px;margin-bottom:32px;\">\n          <button class=\"setting-tab-btn\" id=\"btn-profile\">Thông tin cá nhân</button>\n        </div>\n        <div id=\"setting-table\"></div>\n      `;
      // Gán lại sự kiện cho các tab
      document.getElementById('btn-roles').onclick = function() { loadRoles(); };
      document.getElementById('btn-room-types').onclick = function() { loadRoomTypes(); };
      document.getElementById('btn-device-types').onclick = function() { loadDeviceTypes(); };
      document.getElementById('btn-profile').onclick = function() {
        document.querySelectorAll('.setting-tab-btn').forEach(btn=>btn.classList.remove('active'));
        document.getElementById('setting-table').innerHTML = '<div class="setting-card" id="profile-card" style="max-width:500px;margin:auto;">Đang tải...</div>';
        fetch('/api/user', {headers:{'Authorization':'Bearer '+localStorage.getItem('token')}})
        .then(res=>res.json()).then(user=>{
          let roles = user.roles && user.roles.length ? user.roles.map(r=>r.name).join(', ') : '';
          let html = `<div style=\"font-size:22px;font-weight:bold;color:#1a237e;margin-bottom:18px;display:flex;align-items:center;justify-content:center;position:relative;\"><button id=\"profile-back-btn\" style=\"position:absolute;left:0;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:22px;color:#1976d2;\">←</button><span style=\"flex:1;text-align:center;\">Thông tin cá nhân</span></div>`;
          html += `<div style=\"margin-bottom:12px;\"><b>Họ tên:</b> <span id=\"profile-fullname\">${user.full_name||user.name||''}</span></div>`;
          html += `<div style=\"margin-bottom:12px;\"><b>Email:</b> <span id=\"profile-email\">${user.email||''}</span></div>`;
          html += `<div style=\"margin-bottom:12px;\"><b>Vai trò:</b> ${roles}</div>`;
          html += `<div style=\"margin-bottom:12px;\"><b>Trạng thái:</b> ${user.is_verified?'Đã xác thực':'Chưa xác thực'}</div>`;
          if(user.created_at) html += `<div style=\"margin-bottom:12px;\"><b>Ngày tạo:</b> ${user.created_at}</div>`;
          if(user.updated_at) html += `<div style=\"margin-bottom:12px;\"><b>Cập nhật:</b> ${user.updated_at}</div>`;
          html += `<div style=\"margin-top:22px;text-align:right;\"><button class=\"btn\" id=\"btn-edit-profile\" style=\"background:#2196f3;color:#fff;font-size:16px;padding:9px 22px;border-radius:7px;\">Cập nhật thông tin</button></div>`;
          document.getElementById('profile-card').innerHTML = html;
          // Gán lại sự kiện cho nút quay lại
          document.getElementById('profile-back-btn').onclick = function() {
            renderSettingHome();
          };
        });
      };
      showLogoutBtnInSetting();
    }

    // Ẩn nút đăng xuất khỏi sidebar
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) logoutBtn.style.display = 'none';
    // Khi vào giao diện Cài đặt, hiển thị nút đăng xuất ở góc dưới bên phải
    function showLogoutBtnInSetting() {
      let btn = document.getElementById('logout-btn-floating');
      if (!btn) {
        btn = document.createElement('button');
        btn.id = 'logout-btn-floating';
        btn.textContent = 'Đăng xuất';
        btn.style.position = 'fixed';
        btn.style.bottom = '32px';
        btn.style.right = '40px';
        btn.style.padding = '14px 38px';
        btn.style.background = '#e53935';
        btn.style.color = '#fff';
        btn.style.border = 'none';
        btn.style.borderRadius = '10px';
        btn.style.fontSize = '18px';
        btn.style.fontWeight = 'bold';
        btn.style.boxShadow = '0 4px 18px rgba(0,0,0,0.13)';
        btn.style.cursor = 'pointer';
        btn.style.zIndex = '3000';
        btn.onclick = function() { logoutBtn && logoutBtn.click(); };
        document.body.appendChild(btn);
      } else {
        btn.style.display = 'block';
      }
    }
    function hideLogoutBtnInSetting() {
      let btn = document.getElementById('logout-btn-floating');
      if (btn) btn.style.display = 'none';
    }
    // Khi chuyển sang các tab khác (Trang chủ, Phòng học, Tài khoản, Thiết bị) thì ẩn nút floating
    ['sidebar-rooms','sidebar-users','sidebar-device'].forEach(id=>{
      const el = document.getElementById(id);
      if(el) el.onclick = function() { hideLogoutBtnInSetting(); };
    });
</script>
</body>
</html>
