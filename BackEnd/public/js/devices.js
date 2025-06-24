let deviceCurrentPage = 1;
let deviceSearch = '';
let deviceTypes = [];

function fetchDevices(page = 1) {
    const token = localStorage.getItem('token');
    fetch(`/api/devices?search=${encodeURIComponent(deviceSearch)}&page=${page}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => res.json())
    .then(data => {
        const devices = data.data || [];
        const tbody = document.getElementById('device-table-body');
        if (!tbody) return;
        tbody.innerHTML = '';
        devices.forEach(device => {
            tbody.innerHTML += `
            <tr>
                <td>${device.name}</td>
                <td>${device.device_type ? device.device_type.name : ''}</td>
                <td>${device.location || ''}</td>
                <td>${device.status ? 'Đang hoạt động' : 'Bảo trì'}</td>
                <td>${device.description || ''}</td>
                <td>
                    <div class="action-btns">
                        <button class="edit-btn" onclick="editDevice(${device.id})">+</button>
                        <button class="delete-btn" onclick="confirmDeleteDevice(${device.id})">×</button>
                    </div>
                </td>
            </tr>`;
        });
        // Phân trang
        const pag = document.getElementById('device-pagination');
        if (!pag) return;
        pag.innerHTML = '';
        if (data.meta && data.meta.last_page > 1) {
            for (let i = 1; i <= data.meta.last_page; i++) {
                pag.innerHTML += `<button class="${i === data.meta.current_page ? 'active' : ''}" onclick="gotoDevicePage(${i})">${i}</button>`;
            }
        }
    });
}
function gotoDevicePage(page) { deviceCurrentPage = page; fetchDevices(page); }
window.gotoDevicePage = gotoDevicePage;
window.fetchDevices = fetchDevices;

function fetchDeviceTypes(selectedId = null) {
    const token = localStorage.getItem('token');
    return fetch('/api/device-types', { headers: { 'Authorization': 'Bearer ' + token } })
        .then(res => res.json())
        .then(data => {
            deviceTypes = data.data || data;
            const select = document.getElementById('device-type-id');
            if (!select) { console.error('Không tìm thấy select device-type-id'); return; }
            select.innerHTML = '';
            deviceTypes.forEach((dt, idx) => {
                let selected = '';
                if (selectedId != null) selected = (selectedId == dt.id ? 'selected' : '');
                else if (idx === 0) selected = 'selected';
                select.innerHTML += `<option value="${dt.id}" ${selected}>${dt.name}</option>`;
            });
        });
}
window.fetchDeviceTypes = fetchDeviceTypes;

function openAddDeviceModal() {
    fetchDeviceTypes().then(() => {
        const modal = document.getElementById('device-modal');
        if (!modal) { return; }
        modal.style.display = 'flex';
        document.getElementById('device-modal-title').textContent = 'Thêm thiết bị';
        document.getElementById('device-id').value = '';
        document.getElementById('device-name').value = '';
        document.getElementById('device-type-id').value = '';
        document.getElementById('device-location').value = '';
        document.getElementById('device-status').value = '1';
        document.getElementById('device-description').value = '';
    });
}
window.openAddDeviceModal = openAddDeviceModal;

function addDevice() {
    if (window.savingDevice) return;
    const name = document.getElementById('device-name').value.trim();
    const deviceTypeId = document.getElementById('device-type-id').value;
    const location = document.getElementById('device-location').value.trim();
    const status = document.getElementById('device-status').value;
    const description = document.getElementById('device-description').value.trim();
    if (!name) {
        showDeviceAlert('Vui lòng nhập tên thiết bị!', 'error');
        return;
    }
    if (!deviceTypeId) {
        showDeviceAlert('Vui lòng chọn loại thiết bị!', 'error');
        return;
    }
    window.savingDevice = true;
    const token = localStorage.getItem('token');
    fetch('/api/devices', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            device_type_id: deviceTypeId,
            location: location,
            status: Number(status),
            description: description
        })
    })
    .then(async res => {
        window.savingDevice = false;
        if (res.ok) {
            showDeviceAlert('Thêm thiết bị thành công!');
            document.getElementById('device-modal').style.display = 'none';
            fetchDevices(1);
        } else {
            let err;
            try { err = await res.json(); } catch { err = {}; }
            console.error('API device error:', res, err);
            showDeviceAlert((err.message || 'Có lỗi xảy ra!') + (res.status ? ' (Mã lỗi: ' + res.status + ')' : ''), 'error');
        }
    })
    .catch((e) => {
        window.savingDevice = false;
        showDeviceAlert('Có lỗi xảy ra khi gửi dữ liệu!', 'error');
    });
}
window.addDevice = addDevice;

function updateDevice() {
    if (window.savingDevice) return;
    const id = document.getElementById('device-id').value;
    const name = document.getElementById('device-name').value.trim();
    const deviceTypeId = document.getElementById('device-type-id').value;
    const location = document.getElementById('device-location').value.trim();
    const status = document.getElementById('device-status').value;
    const description = document.getElementById('device-description').value.trim();
    if (!name) {
        showDeviceAlert('Vui lòng nhập tên thiết bị!', 'error');
        return;
    }
    if (!deviceTypeId) {
        showDeviceAlert('Vui lòng chọn loại thiết bị!', 'error');
        return;
    }
    window.savingDevice = true;
    const token = localStorage.getItem('token');
    fetch(`/api/devices/${id}`, {
        method: 'PUT',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            device_type_id: deviceTypeId,
            location: location,
            status: Number(status),
            description: description
        })
    })
    .then(async res => {
        window.savingDevice = false;
        if (res.ok) {
            showDeviceAlert('Cập nhật thiết bị thành công!');
            document.getElementById('device-modal').style.display = 'none';
            fetchDevices(deviceCurrentPage);
        } else {
            let err;
            try { err = await res.json(); } catch { err = {}; }
            showDeviceAlert((err.message || 'Có lỗi xảy ra!') + (res.status ? ' (Mã lỗi: ' + res.status + ')' : ''), 'error');
        }
    })
    .catch((e) => {
        window.savingDevice = false;
        showDeviceAlert('Có lỗi xảy ra khi gửi dữ liệu!', 'error');
    });
}
window.updateDevice = updateDevice;

function initDeviceEvents() {
    const searchInput = document.getElementById('device-search');
    if (searchInput) {
        searchInput.oninput = function(e) {
            deviceSearch = e.target.value;
            fetchDevices(1);
        };
    }
    fetchDevices();
    // Nút thêm thiết bị
    const addBtn = document.getElementById('add-device-btn');
    if (addBtn) {
        addBtn.onclick = openAddDeviceModal;
    }
    // Nút Lưu thiết bị (thêm/sửa)
    const saveBtn = document.getElementById('device-save');
    if (saveBtn) {
        saveBtn.onclick = function() {
            const id = document.getElementById('device-id').value;
            if (!id) addDevice();
            else updateDevice();
        };
    }
    // Nút hủy popup
    const cancelBtn = document.getElementById('device-cancel');
    if (cancelBtn) {
        cancelBtn.onclick = function() {
            document.getElementById('device-modal').style.display = 'none';
        };
    }
    // Nút xác nhận xóa
    const confirmYes = document.getElementById('device-confirm-yes');
    if (confirmYes) {
        confirmYes.onclick = function() {
            if (!window.deviceToDelete) return;
            const token = localStorage.getItem('token');
            fetch(`/api/devices/${window.deviceToDelete}`, {
                method: 'DELETE',
                headers: { 'Authorization': 'Bearer ' + token }
            })
            .then(res => {
                if (res.ok) showDeviceAlert('Xóa thiết bị thành công!');
                else showDeviceAlert('Xóa thiết bị thất bại!', 'error');
                fetchDevices(deviceCurrentPage);
            });
            const modal = document.getElementById('device-confirm-modal');
            if (modal) modal.style.display = 'none';
            window.deviceToDelete = null;
        };
    }
    // Nút hủy xóa
    const confirmNo = document.getElementById('device-confirm-no');
    if (confirmNo) {
        confirmNo.onclick = function() {
            const modal = document.getElementById('device-confirm-modal');
            if (modal) modal.style.display = 'none';
            window.deviceToDelete = null;
        };
    }
    // Đóng popup modal
    const deviceModal = document.getElementById('device-modal');
    if (deviceModal) {
        deviceModal.onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };
    }
}
window.initDeviceEvents = initDeviceEvents;

window.confirmDeleteDevice = function(id) {
    window.deviceToDelete = id;
    const modal = document.getElementById('device-confirm-modal');
    if (modal) modal.style.display = 'flex';
};

function showDeviceAlert(msg, type = 'success') {
    const box = document.getElementById('device-alert-box');
    if (!box) return;
    box.textContent = msg;
    box.style.background = type === 'success' ? '#43a047' : '#e53935';
    box.style.color = '#fff';
    box.style.display = 'block';
    setTimeout(() => { box.style.display = 'none'; }, 2500);
}
window.showDeviceAlert = showDeviceAlert;

window.editDevice = function(id) {
    const token = localStorage.getItem('token');
    fetch(`/api/devices/${id}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => res.json())
    .then(res => {
        const device = res.data ? res.data : res;
        fetchDeviceTypes(device.device_type ? device.device_type.id : null).then(() => {
            const modal = document.getElementById('device-modal');
            if (!modal) return;
            modal.style.display = 'flex';
            document.getElementById('device-modal-title').textContent = 'Sửa thiết bị';
            document.getElementById('device-id').value = device.id;
            document.getElementById('device-name').value = device.name;
            document.getElementById('device-type-id').value = device.device_type ? device.device_type.id : '';
            document.getElementById('device-location').value = device.location || '';
            document.getElementById('device-status').value = String(device.status ? 1 : 0);
            document.getElementById('device-description').value = device.description || '';
        });
    });
}; 