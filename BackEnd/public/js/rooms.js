// Lấy tên user (nếu cần, có thể bỏ qua nếu đã có ở dashboard)
// const token = localStorage.getItem('token');
// if (!token) window.location.href = '/login';
// fetch('/api/user', { headers: { 'Authorization': 'Bearer ' + token } })
//     .then(res => res.json())
//     .then(user => { document.getElementById('name').textContent = user.full_name || user.name || 'Admin'; });

// Fetch danh sách phòng
let currentPage = 1;
let search = '';
function fetchRooms(page = 1) {
    const token = localStorage.getItem('token');
    fetch(`/api/rooms?search=${encodeURIComponent(search)}&page=${page}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => res.json())
    .then(data => {
        const rooms = data.data || [];
        const tbody = document.getElementById('room-table-body');
        if (!tbody) return;
        tbody.innerHTML = '';
        rooms.forEach(room => {
            tbody.innerHTML += `
            <tr>
                <td>${room.name}</td>
                <td>${room.location || ''}</td>
                <td>${room.status === 0 ? 'Bảo trì' : (room.status === 1 ? 'Tốt' : 'Chờ')}</td>
                <td><button class="btn" style="width:32px;height:32px;font-size:15px;border-radius:4px;padding:0;min-width:unset;line-height:32px;" onclick="showDevices(${room.id}, '${room.name.replace(/'/g, '\'') || ''}')\">D</button></td>
                <td>${room.room_type ? room.room_type.name : room.room_type_id}</td>
                <td>${room.description || ''}</td>
                <td><button class="btn" style="width:32px;height:32px;font-size:15px;border-radius:4px;padding:0;min-width:unset;line-height:32px;" onclick="showRoomBookings(${room.id}, '${room.name.replace(/'/g, '\'') || ''}')\">B</button></td>
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
        if (!pag) return;
        pag.innerHTML = '';
        if (data.meta && data.meta.last_page > 1) {
            for (let i = 1; i <= data.meta.last_page; i++) {
                pag.innerHTML += `<button class="${i === data.meta.current_page ? 'active' : ''}" onclick="gotoPage(${i})">${i}</button>`;
            }
        }
    });
}
function gotoPage(page) { currentPage = page; fetchRooms(page); }
window.gotoPage = gotoPage;
window.fetchRooms = fetchRooms;

function initRoomEvents() {
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.oninput = function(e) {
            search = e.target.value; fetchRooms(1);
        };
    }
    fetchRooms();
    // Nút thêm phòng
    const addBtn = document.querySelector('.search-bar .btn');
    if (addBtn) {
        addBtn.onclick = function() {
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
    }
    // Nút Lưu phòng (thêm/sửa)
    const saveBtn = document.getElementById('room-save');
    if (saveBtn) {
        saveBtn.onclick = function() {
            if (window.saving) return;
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
            window.saving = true;
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/api/rooms/${id}` : '/api/rooms';
            const token = localStorage.getItem('token');
            fetch(url, {
                method: method,
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(async res => {
                window.saving = false;
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
                window.saving = false;
                showAlert('Có lỗi xảy ra!', 'error');
            });
        };
    }
    // Nút hủy popup
    const cancelBtn = document.getElementById('room-cancel');
    if (cancelBtn) {
        cancelBtn.onclick = function() {
            document.getElementById('room-modal').style.display = 'none';
        };
    }
    // Nút xác nhận xóa
    const confirmYes = document.getElementById('confirm-yes');
    if (confirmYes) {
        confirmYes.onclick = function() {
            if (!window.roomToDelete) return;
            const token = localStorage.getItem('token');
            fetch(`/api/rooms/${window.roomToDelete}`, {
                method: 'DELETE',
                headers: { 'Authorization': 'Bearer ' + token }
            })
            .then(res => {
                if (res.ok) showAlert('Xóa phòng thành công!');
                else showAlert('Xóa phòng thất bại!', 'error');
                fetchRooms(currentPage);
            });
            const modal = document.getElementById('confirm-modal');
            if (modal) modal.style.display = 'none';
            window.roomToDelete = null;
        };
    }
    // Nút hủy xóa
    const confirmNo = document.getElementById('confirm-no');
    if (confirmNo) {
        confirmNo.onclick = function() {
            const modal = document.getElementById('confirm-modal');
            if (modal) modal.style.display = 'none';
            window.roomToDelete = null;
        };
    }
    // Đóng popup modal
    const roomModal = document.getElementById('room-modal');
    if (roomModal) {
        roomModal.onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };
    }
}
window.initRoomEvents = initRoomEvents;

window.confirmDelete = function(id) {
    window.roomToDelete = id;
    const modal = document.getElementById('confirm-modal');
    if (modal) modal.style.display = 'flex';
};

function showAlert(msg, type = 'success') {
    const box = document.getElementById('alert-box');
    if (!box) return;
    box.textContent = msg;
    box.style.background = type === 'success' ? '#43a047' : '#e53935';
    box.style.color = '#fff';
    box.style.display = 'block';
    setTimeout(() => { box.style.display = 'none'; }, 2500);
}
window.showAlert = showAlert;

// Lấy danh sách loại phòng từ API
let roomTypes = [];
function fetchRoomTypes(selectedId = null) {
    const token = localStorage.getItem('token');
    return fetch('/api/room-types', { headers: { 'Authorization': 'Bearer ' + token } })
        .then(res => res.json())
        .then(data => {
            roomTypes = data.data || data;
            const select = document.getElementById('room-type-id');
            if (!select) return;
            select.innerHTML = '';
            roomTypes.forEach(rt => {
                select.innerHTML += `<option value="${rt.id}" ${selectedId == rt.id ? 'selected' : ''}>${rt.name}</option>`;
            });
        });
}
window.fetchRoomTypes = fetchRoomTypes;

window.editRoom = function(id) {
    const token = localStorage.getItem('token');
    fetch(`/api/rooms/${id}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => res.json())
    .then(res => {
        const room = res.data ? res.data : res;
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
};

window.showDevices = function(roomId, roomName) {
    const token = localStorage.getItem('token');
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
};

window.showRoomBookings = function(roomId, roomName) {
    const modal = document.getElementById('booking-modal');
    const content = document.getElementById('booking-list-content');
    document.getElementById('booking-room-name').textContent = roomName;
    modal.style.display = 'flex';
    content.innerHTML = 'Đang tải...';
    const token = localStorage.getItem('token');
    fetch(`/api/rooms/${roomId}/bookings`, {
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.length) {
            content.innerHTML = '<i>Không có booking nào cho phòng này.</i>';
            return;
        }
        let html = `<table style='width:100%;border-collapse:collapse;'>
            <tr><th>Người đặt</th><th>Bắt đầu</th><th>Kết thúc</th><th>Mục đích</th><th>Ghi chú</th><th>Trạng thái</th></tr>`;
        data.forEach(b => {
            html += `<tr>
                <td>${b.user_id || ''}</td>
                <td>${b.start_time || ''}</td>
                <td>${b.end_time || ''}</td>
                <td>${b.purpose || ''}</td>
                <td>${b.note || ''}</td>
                <td>${b.status || ''}</td>
            </tr>`;
        });
        html += '</table>';
        content.innerHTML = html;
    })
    .catch(() => {
        content.innerHTML = '<span style="color:red">Lỗi khi tải booking!</span>';
    });
};

window.editRoom = editRoom;
window.confirmDelete = confirmDelete;
window.showDevices = showDevices;
window.fetchRooms = fetchRooms;
window.gotoPage = gotoPage;
window.fetchRoomTypes = fetchRoomTypes;
window.showAlert = showAlert;
window.initRoomEvents = initRoomEvents;
window.showRoomBookings = showRoomBookings; 