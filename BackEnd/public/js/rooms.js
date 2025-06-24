// Lấy tên user (nếu cần, có thể bỏ qua nếu đã có ở dashboard)
// const token = localStorage.getItem('token');
// if (!token) window.location.href = '/login';
// fetch('/api/user', { headers: { 'Authorization': 'Bearer ' + token } })
//     .then(res => res.json())
//     .then(user => { document.getElementById('name').textContent = user.full_name || user.name || 'Admin'; });

// Fetch danh sách phòng
let currentPage = 1;
let search = '';
let searchTimeout = null;

function debounce(func, wait) {
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(searchTimeout);
            func(...args);
        };
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(later, wait);
    };
}

function showLoadingState() {
    const tbody = document.getElementById('room-table-body');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center;">Đang tải...</td></tr>';
    }
}

function showEmptyState(message = 'Không tìm thấy phòng nào') {
    const tbody = document.getElementById('room-table-body');
    if (tbody) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align: center;">${message}</td></tr>`;
    }
}

function fetchRooms(page = 1) {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login';
        return;
    }

    showLoadingState();

    fetch(`/api/rooms?search=${encodeURIComponent(search)}&page=${page}&per_page=10`, {
        headers: { 
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(data => {
        if (!data.success) {
            throw new Error(data.message || 'Có lỗi xảy ra khi tải danh sách phòng');
        }

        const rooms = data.data || [];
        const tbody = document.getElementById('room-table-body');
        if (!tbody) return;

        if (rooms.length === 0) {
            showEmptyState(search ? 'Không tìm thấy phòng nào phù hợp với tìm kiếm' : 'Chưa có phòng nào');
            return;
        }

        tbody.innerHTML = '';
        rooms.forEach(room => {
            tbody.innerHTML += `
            <tr>
                <td>${room.name}</td>
                <td>${room.location || ''}</td>
                <td>
                    <span class="status-badge ${room.status === 1 ? 'good' : (room.status === 0 ? 'maintenance' : 'waiting')}">
                        ${room.status === 0 ? 'Bảo trì' : (room.status === 1 ? 'Tốt' : 'Chờ')}
                    </span>
                </td>
                <td><button class="btn device-btn" onclick="showDevices(${room.id}, '${room.name.replace(/'/g, '\'') || ''}')" title="Xem thiết bị">D</button></td>
                <td>${room.room_type ? room.room_type.name : ''}</td>
                <td>${room.description || ''}</td>
                <td><button class="btn booking-btn" onclick="showRoomBookings(${room.id}, '${room.name.replace(/'/g, '\'') || ''}')" title="Xem lịch đặt">B</button></td>
                <td>
                    <div class="action-btns">
                        <button class="btn edit-btn" onclick="editRoom(${room.id})" title="Sửa phòng">+</button>
                        <button class="btn delete-btn" onclick="confirmDelete(${room.id})" title="Xóa phòng">×</button>
                    </div>
                </td>
            </tr>`;
        });

        // Phân trang
        const pag = document.getElementById('pagination');
        if (!pag) return;
        pag.innerHTML = '';
        if (data.meta && data.meta.last_page > 1) {
            // Thêm nút Previous
            if (data.meta.current_page > 1) {
                pag.innerHTML += `<button onclick="gotoPage(${data.meta.current_page - 1})">←</button>`;
            }

            // Hiển thị các trang
            for (let i = 1; i <= data.meta.last_page; i++) {
                if (
                    i === 1 || // Luôn hiển thị trang đầu
                    i === data.meta.last_page || // Luôn hiển thị trang cuối
                    (i >= data.meta.current_page - 2 && i <= data.meta.current_page + 2) // Hiển thị 2 trang trước và sau trang hiện tại
                ) {
                    pag.innerHTML += `<button class="${i === data.meta.current_page ? 'active' : ''}" onclick="gotoPage(${i})">${i}</button>`;
                } else if (
                    (i === data.meta.current_page - 3 && data.meta.current_page > 4) ||
                    (i === data.meta.current_page + 3 && data.meta.current_page < data.meta.last_page - 3)
                ) {
                    pag.innerHTML += `<span>...</span>`;
                }
            }

            // Thêm nút Next
            if (data.meta.current_page < data.meta.last_page) {
                pag.innerHTML += `<button onclick="gotoPage(${data.meta.current_page + 1})">→</button>`;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(error.message || 'Có lỗi xảy ra khi tải danh sách phòng', 'error');
        showEmptyState('Có lỗi xảy ra khi tải danh sách phòng');
    });
}

function gotoPage(page) { currentPage = page; fetchRooms(page); }
window.gotoPage = gotoPage;
window.fetchRooms = fetchRooms;

function initRoomEvents() {
    const searchInput = document.getElementById('search');
    if (searchInput) {
        const debouncedSearch = debounce((value) => {
            search = value;
            currentPage = 1;
            fetchRooms(1);
        }, 300);

        searchInput.oninput = function(e) {
            debouncedSearch(e.target.value);
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

function showDevices(roomId, roomName) {
    const modal = document.getElementById('device-modal');
    const tbody = document.getElementById('device-table-body');
    document.getElementById('device-modal-title').textContent = `Danh sách thiết bị - ${roomName}`;
    
    // Show loading state
    tbody.innerHTML = `
        <tr>
            <td colspan="6">
                <div class="loading-state">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                    <p>Đang tải danh sách thiết bị...</p>
                </div>
            </td>
        </tr>`;
    
    modal.style.display = 'flex';
    
    const token = localStorage.getItem('token');
    fetch(`/api/rooms/${roomId}/devices`, { 
        headers: { 
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(data => {
        const devices = Array.isArray(data) ? data : (data.data || []);
        
        if (devices.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Phòng này chưa có thiết bị nào.</p>
                        </div>
                    </td>
                </tr>`;
            return;
        }
        
        tbody.innerHTML = '';
        devices.forEach((device, index) => {
            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${device.device_name}</td>
                    <td>${device.device_type}</td>
                    <td>${device.quantity}</td>
                    <td>${device.note || ''}</td>
                    <td>
                        <span class="status-badge ${device.status == 1 ? 'good' : 'maintenance'}">
                            ${device.status == 1 ? 'Đang dùng' : 'Không dùng'}
                        </span>
                    </td>
                </tr>`;
        });
    })
    .catch(error => {
        console.error('Error:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="#e53935" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p style="color:#e53935">Có lỗi xảy ra khi tải danh sách thiết bị.</p>
                    </div>
                </td>
            </tr>`;
    });
}

function showRoomBookings(roomId, roomName) {
    const modal = document.getElementById('booking-modal');
    const tbody = document.getElementById('booking-table-body');
    document.getElementById('booking-room-name').textContent = roomName;
    
    // Show loading state
    tbody.innerHTML = `
        <tr>
            <td colspan="5">
                <div class="loading-state">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                    <p>Đang tải danh sách đặt phòng...</p>
                </div>
            </td>
        </tr>`;
    
    modal.style.display = 'flex';
    
    const token = localStorage.getItem('token');
    fetch(`/api/rooms/${roomId}/bookings`, { 
        headers: { 
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(data => {
        const bookings = Array.isArray(data) ? data : (data.data || []);
        
        if (bookings.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Chưa có lịch đặt phòng nào.</p>
                        </div>
                    </td>
                </tr>`;
            return;
        }
        
        tbody.innerHTML = '';
        bookings.forEach(booking => {
            const startTime = new Date(booking.start_time).toLocaleString('vi-VN');
            const endTime = new Date(booking.end_time).toLocaleString('vi-VN');
            
            let statusClass = '';
            let statusText = '';
            switch(booking.status) {
                case 0:
                    statusClass = 'pending';
                    statusText = 'Chờ duyệt';
                    break;
                case 1:
                    statusClass = 'approved';
                    statusText = 'Đã duyệt';
                    break;
                case 2:
                    statusClass = 'rejected';
                    statusText = 'Từ chối';
                    break;
                case 3:
                    statusClass = 'completed';
                    statusText = 'Hoàn thành';
                    break;
            }
            
            tbody.innerHTML += `
                <tr>
                    <td>${booking.user ? booking.user.name : 'N/A'}</td>
                    <td>${startTime}</td>
                    <td>${endTime}</td>
                    <td>${booking.purpose || ''}</td>
                    <td><span class="booking-status ${statusClass}">${statusText}</span></td>
                </tr>`;
        });
    })
    .catch(error => {
        console.error('Error:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="#e53935" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p style="color:#e53935">Có lỗi xảy ra khi tải danh sách đặt phòng.</p>
                    </div>
                </td>
            </tr>`;
    });
}

// Add room function
function addRoom() {
    fetchRoomTypes().then(() => {
        document.getElementById('room-modal').style.display = 'flex';
        document.getElementById('modal-title').textContent = 'Thêm phòng mới';
        document.getElementById('room-id').value = '';
        document.getElementById('room-name').value = '';
        document.getElementById('room-location').value = '';
        document.getElementById('room-capacity').value = '';
        document.getElementById('room-status').value = '1';
        document.getElementById('room-description').value = '';
    });
}

// Close modals when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});

// Export functions to window object
window.showDevices = showDevices;
window.showRoomBookings = showRoomBookings;
window.addRoom = addRoom; 