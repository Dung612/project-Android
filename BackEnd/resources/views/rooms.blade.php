<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách phòng học</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f4f7; margin: 0; padding: 0; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #fff; box-shadow: 2px 0 10px rgba(0,0,0,0.04); padding: 30px 0 0 0; display: flex; flex-direction: column; align-items: center; }
        .sidebar ul { list-style: none; padding: 0; width: 100%; }
        .sidebar li { width: 100%; margin-bottom: 18px; }
        .sidebar a { display: flex; align-items: center; text-decoration: none; color: #222; padding: 10px 30px; border-radius: 6px; transition: background 0.2s; }
        .sidebar a:hover, .sidebar .active { background: #f2f4f7; }
        .sidebar svg { margin-right: 12px; }
        .main { flex: 1; padding: 40px 40px 0 40px; }
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
        .action-btns {
            display: flex;
            gap: 6px;
            align-items: center;
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
        /* Chuẩn hóa input/select/textarea trong modal */
        .confirm-box input[type="text"],
        .confirm-box input[type="number"],
        .confirm-box input[type="email"],
        .confirm-box input[type="password"],
        .confirm-box select,
        .confirm-box textarea {
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
        .confirm-box input[type="number"]:focus,
        .confirm-box input[type="email"]:focus,
        .confirm-box input[type="password"]:focus,
        .confirm-box select:focus,
        .confirm-box textarea:focus {
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
            <li><a href="/dashboard"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9"/><path d="M9 21V9h6v12"/></svg>Trang chủ</a></li>
            <li><a href="/rooms" class="active"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M16 3v4"/><path d="M8 3v4"/></svg>Phòng học</a></li>
            <li><a href="/users" id="sidebar-users"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>Tài khoản</a></li>
            <li><a href="#"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 4v16"/><path d="M16 4v16"/><path d="M4 12h16"/></svg>Bài viết</a></li>
            <li><a href="#"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>Cài đặt</a></li>
        </ul>
    </nav>
    <main class="main">
        <div class="page-title">Danh sách phòng học</div>
        <div class="table-container">
            <div class="search-bar">
                <input type="text" id="search" placeholder="Nhập số phòng">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <button class="btn" style="margin-left:auto;" onclick="window.location.href='/rooms/create'">Thêm phòng</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Phòng</th>
                        <th>Vị trí</th>
                        <th>Tình trạng</th>
                        <th>Thiết bị</th>
                        <th>Chức năng</th>
                        <th>Mô tả</th>
                        <th>Booking</th>
                        <th>Chỉnh sửa</th>
                    </tr>
                </thead>
                <tbody id="room-table-body">
                    <!-- Dữ liệu sẽ được render ở đây, cần đảm bảo JS không render cột checkbox -->
                </tbody>
            </table>
            <div class="pagination" id="pagination"></div>
        </div>
        <!-- Modal hiển thị booking -->
        <div id="booking-modal" class="confirm-modal" style="display:none;z-index:2001;">
            <div class="confirm-box" style="min-width:400px;max-width:90vw;max-height:80vh;overflow:auto;">
                <h3>Danh sách booking phòng <span id="booking-room-name"></span></h3>
                <div id="booking-list-content">Đang tải...</div>
                <div class="form-actions" style="justify-content:center;margin-top:18px;">
                    <button class="btn" onclick="document.getElementById('booking-modal').style.display='none'">Đóng</button>
                </div>
            </div>
        </div>
    </main>
</div>
<div id="confirm-modal" class="confirm-modal" style="display:none;">
    <div class="confirm-box">
        <div style="margin-bottom:18px;font-size:17px;">Bạn có thật sự muốn xóa phòng không?</div>
        <div class="form-actions" style="justify-content:center;">
            <button class="btn" id="confirm-yes">Có</button>
            <button class="btn" id="confirm-no" style="background:#e53935;">Không</button>
        </div>
    </div>
</div>
<!-- Popup form Thêm/Sửa phòng -->
<div id="room-modal" class="confirm-modal" style="display:none;">
    <div class="confirm-box" style="min-width:350px;">
        <h3 id="modal-title">Thêm phòng</h3>
        <form id="room-form">
            <input type="hidden" id="room-id">
            <div class="form-row">
                <label for="room-name">Tên phòng (ví dụ: Phòng A101)</label>
                <input type="text" id="room-name" placeholder="Tên phòng">
            </div>
            <div class="form-row">
                <label for="room-type-id">Loại phòng</label>
                <select id="room-type-id"></select>
            </div>
            <div class="form-row">
                <label for="room-location">Vị trí (ví dụ: Tầng 1 - Khu A)</label>
                <input type="text" id="room-location" placeholder="Vị trí">
            </div>
            <div class="form-row">
                <label for="room-capacity">Sức chứa</label>
                <input type="text" id="room-capacity" pattern="[0-9]*" inputmode="numeric" placeholder="Sức chứa">
            </div>
            <div class="form-row">
                <label for="room-status">Tình trạng</label>
                <select id="room-status">
                    <option value="1">Tốt</option>
                    <option value="0">Bảo trì</option>
                    <option value="2">Chờ</option>
                </select>
            </div>
            <div class="form-row">
                <label for="room-description">Mô tả</label>
                <textarea id="room-description" placeholder="Mô tả"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn" id="room-save">Lưu</button>
                <button type="button" class="btn" id="room-cancel">Hủy</button>
            </div>
        </form>
    </div>
</div>
<!-- Thông báo -->
<div id="alert-box" style="display:none;position:fixed;top:30px;right:30px;z-index:2000;padding:16px 28px;border-radius:8px;font-weight:bold;"></div>
<!-- Popup xem thiết bị phòng học -->
<div id="device-modal" class="confirm-modal" style="display:none;">
    <div class="confirm-box" style="min-width:420px;max-width:90vw;">
        <h3 id="device-modal-title">Danh sách thiết bị</h3>
        <table style="width:100%;margin-top:10px;">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên thiết bị</th>
                    <th>Loại</th>
                    <th>Số lượng</th>
                    <th>Ghi chú</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody id="device-table-body"></tbody>
        </table>
        <div style="text-align:right;margin-top:12px;">
            <button class="btn" onclick="document.getElementById('device-modal').style.display='none';">Đóng</button>
        </div>
    </div>
</div>
<script>
    // Lấy tên user
    const token = localStorage.getItem('token');
    if (!token) window.location.href = '/login';
    fetch('/api/user', { headers: { 'Authorization': 'Bearer ' + token } })
        .then(res => res.json())
        .then(user => { document.getElementById('name').textContent = user.full_name || user.name || 'Admin'; });

    // Thay đổi tòa
    let currentToa = 'A';
    document.querySelectorAll('.sidebar li').forEach(function(li) {
        li.onclick = function() {
            if (li.textContent.trim().startsWith('Tòa')) {
                currentToa = li.textContent.trim().replace('Tòa ', '').replace('KTX', 'KTX');
                document.querySelectorAll('.sidebar li').forEach(e => e.style.fontWeight = '');
                li.style.fontWeight = 'bold';
                fetchRooms(1);
            }
        };
    });

    // Fetch danh sách phòng
    let currentPage = 1;
    let search = '';
    function fetchRooms(page = 1) {
        fetch(`/api/rooms?search=${encodeURIComponent(search)}&page=${page}`, {
            headers: { 'Authorization': 'Bearer ' + token }
        })
        .then(res => res.json())
        .then(data => {
            console.log('API /api/rooms trả về:', data);
            const rooms = data.data || [];
            const tbody = document.getElementById('room-table-body');
            tbody.innerHTML = '';
            rooms.forEach(room => {
                tbody.innerHTML += `
                <tr>
                    <td>${room.name}</td>
                    <td>${room.location || ''}</td>
                    <td>${room.status === 0 ? 'Bảo trì' : (room.status === 1 ? 'Tốt' : 'Chờ')}</td>
                    <td><a href="#" style="color:#1976d2;" onclick="showDevices(${room.id}, '${room.name}');return false;">Xem thêm</a></td>
                    <td>${room.room_type ? room.room_type.name : room.room_type_id}</td>
                    <td>${room.description || ''}</td>
                    <td>${room.booking ? 'Đã đặt' : 'Chưa đặt'}</td>
                    <td>
                        <div class="action-btns">
                            <button class="btn edit-btn" onclick="editRoom(${room.id})">+</button>
                            <button class="btn delete-btn" onclick="confirmDelete(${room.id})">×</button>
                        </div>
                    </td>
                </tr>`;
            });
            // Phân trang
            const pag = document.getElementById('pagination');
            pag.innerHTML = '';
            if (data.meta && data.meta.last_page > 1) {
                for (let i = 1; i <= data.meta.last_page; i++) {
                    pag.innerHTML += `<button class="${i === data.meta.current_page ? 'active' : ''}" onclick="gotoPage(${i})">${i}</button>`;
                }
            }
        });
    }
    function gotoPage(page) { currentPage = page; fetchRooms(page); }
    document.getElementById('search').addEventListener('input', function(e) {
        search = e.target.value; fetchRooms(1);
    });
    fetchRooms();

    // Xác nhận xóa
    let roomToDelete = null;
    function confirmDelete(id) {
        roomToDelete = id;
        document.getElementById('confirm-modal').style.display = 'flex';
    }
    document.getElementById('confirm-yes').onclick = function() {
        if (!roomToDelete) return;
        fetch(`/api/rooms/${roomToDelete}`, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        })
        .then(res => {
            if (res.ok) showAlert('Xóa phòng thành công!');
            else showAlert('Xóa phòng thất bại!', 'error');
            fetchRooms(currentPage);
        });
        document.getElementById('confirm-modal').style.display = 'none';
        roomToDelete = null;
    };
    document.getElementById('confirm-no').onclick = function() {
        document.getElementById('confirm-modal').style.display = 'none';
        roomToDelete = null;
    };
    // Hiển thị thông báo
    function showAlert(msg, type = 'success') {
        const box = document.getElementById('alert-box');
        box.textContent = msg;
        box.style.background = type === 'success' ? '#43a047' : '#e53935';
        box.style.color = '#fff';
        box.style.display = 'block';
        setTimeout(() => { box.style.display = 'none'; }, 2500);
    }
    // Hiển thị popup Thêm phòng
    document.querySelector('.search-bar .btn').onclick = function() {
        fetchRoomTypes().then(() => {
            document.getElementById('room-modal').style.display = 'flex';
            document.getElementById('modal-title').textContent = 'Thêm phòng';
            document.getElementById('room-id').value = '';
            document.getElementById('room-name').value = '';
            document.getElementById('room-location').value = '';
            document.getElementById('room-capacity').value = '';
            document.getElementById('room-status').value = 1;
            document.getElementById('room-description').value = '';
        });
    };
    // Lấy danh sách loại phòng từ API
    let roomTypes = [];
    function fetchRoomTypes(selectedId = null) {
        return fetch('/api/room-types', { headers: { 'Authorization': 'Bearer ' + token } })
            .then(res => res.json())
            .then(data => {
                roomTypes = data.data || data;
                const select = document.getElementById('room-type-id');
                select.innerHTML = '';
                roomTypes.forEach(rt => {
                    select.innerHTML += `<option value="${rt.id}" ${selectedId == rt.id ? 'selected' : ''}>${rt.name}</option>`;
                });
            });
    }
    // Sửa phòng
    function editRoom(id) {
        fetch(`/api/rooms/${id}`, {
            headers: { 'Authorization': 'Bearer ' + token }
        })
        .then(res => res.json())
        .then(res => {
            const room = res.data ? res.data : res;
            // Lấy loại phòng xong mới mở popup và set giá trị
            fetchRoomTypes(room.room_type_id).then(() => {
                document.getElementById('room-modal').style.display = 'flex';
                document.getElementById('modal-title').textContent = 'Sửa phòng';
                document.getElementById('room-id').value = room.id;
                document.getElementById('room-name').value = room.name;
                document.getElementById('room-location').value = room.location;
                document.getElementById('room-capacity').value = room.capacity;
                setTimeout(() => {
                    document.getElementById('room-status').value = String(room.status);
                }, 0);
                document.getElementById('room-description').value = room.description;
            });
        });
    }
    document.getElementById('room-cancel').onclick = function() {
        document.getElementById('room-modal').style.display = 'none';
    };
    // Lưu phòng (thêm/sửa) với validate và loading
    let saving = false;
    document.getElementById('room-save').onclick = function() {
        if (saving) return;
        const id = document.getElementById('room-id').value;
        const data = {
            name: document.getElementById('room-name').value.trim(),
            room_type_id: document.getElementById('room-type-id').value,
            location: document.getElementById('room-location').value.trim(),
            capacity: document.getElementById('room-capacity').value.trim(),
            status: document.getElementById('room-status').value,
            description: document.getElementById('room-description').value.trim()
        };
        // Validate
        if (!data.name || !data.room_type_id || !data.location || !data.capacity) {
            showAlert('Vui lòng nhập đầy đủ thông tin bắt buộc!', 'error');
            return;
        }
        saving = true;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `/api/rooms/${id}` : '/api/rooms';
        fetch(url, {
            method: method,
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(async res => {
            saving = false;
            if (res.ok) {
                showAlert(id ? 'Cập nhật phòng thành công!' : 'Thêm phòng thành công!');
                document.getElementById('room-modal').style.display = 'none';
                fetchRooms(currentPage);
            } else {
                const err = await res.json();
                showAlert(err.message || 'Có lỗi xảy ra!', 'error');
            }
        })
        .catch(() => {
            saving = false;
            showAlert('Có lỗi xảy ra!', 'error');
        });
    };
    document.getElementById('room-modal').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
    function showDevices(roomId, roomName) {
        const modal = document.getElementById('device-modal');
        const tbody = document.getElementById('device-table-body');
        document.getElementById('device-modal-title').textContent = `Danh sách thiết bị - ${roomName}`;
        tbody.innerHTML = '<tr><td colspan="6">Đang tải...</td></tr>';
        modal.style.display = 'flex';
        fetch(`/api/rooms/${roomId}/devices`, { headers: { 'Authorization': 'Bearer ' + token } })
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="6">Không có thiết bị nào!</td></tr>';
                    return;
                }
                tbody.innerHTML = '';
                data.forEach((item, idx) => {
                    tbody.innerHTML += `<tr>
                        <td>${idx+1}</td>
                        <td>${item.device_name}</td>
                        <td>${item.device_type}</td>
                        <td>${item.quantity}</td>
                        <td>${item.note||''}</td>
                        <td>${item.status==1?'Đang dùng':'Không dùng'}</td>
                    </tr>`;
                });
            })
            .catch(() => {
                tbody.innerHTML = '<tr><td colspan="6">Lỗi tải dữ liệu!</td></tr>';
            });
    }
</script>
</body>
</html> 