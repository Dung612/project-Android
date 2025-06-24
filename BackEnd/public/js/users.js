let userCurrentPage = 1;
let userSearch = '';
let userRoles = [];

function fetchUsers(page = 1) {
    const token = localStorage.getItem('token');
    fetch(`/api/users?search=${encodeURIComponent(userSearch)}&page=${page}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => res.json())
    .then(data => {
        const users = data.data || [];
        const tbody = document.getElementById('user-table-body');
        if (!tbody) return;
        tbody.innerHTML = '';
        users.forEach(user => {
            tbody.innerHTML += `
            <tr>
                <td>${user.full_name || user.name}</td>
                <td>${user.email}</td>
                <td>${user.roles && user.roles.length ? user.roles[0].name : ''}</td>
                <td>${user.is_verified == 1 ? 'Đã xác thực' : 'Chưa xác thực'}</td>
                <td>
                    <div class="action-btns">
                        <button class="edit-btn" onclick="editUser(${user.id})">+</button>
                        <button class="delete-btn" onclick="confirmDeleteUser(${user.id})">×</button>
                    </div>
                </td>
            </tr>`;
        });
        // Phân trang
        const pag = document.getElementById('user-pagination');
        if (!pag) return;
        pag.innerHTML = '';
        if (data.meta && data.meta.last_page > 1) {
            for (let i = 1; i <= data.meta.last_page; i++) {
                pag.innerHTML += `<button class="${i === data.meta.current_page ? 'active' : ''}" onclick="gotoUserPage(${i})">${i}</button>`;
            }
        }
    });
}
function gotoUserPage(page) { userCurrentPage = page; fetchUsers(page); }
window.gotoUserPage = gotoUserPage;
window.fetchUsers = fetchUsers;

function fetchUserRoles(selectedId = null) {
    const token = localStorage.getItem('token');
    return fetch('/api/roles', { headers: { 'Authorization': 'Bearer ' + token } })
        .then(res => res.json())
        .then(data => {
            userRoles = data.data || data;
            const select = document.getElementById('user-role-id');
            if (!select) return;
            select.innerHTML = '';
            userRoles.forEach(role => {
                select.innerHTML += `<option value="${role.id}" ${selectedId == role.id ? 'selected' : ''}>${role.name}</option>`;
            });
        });
}
window.fetchUserRoles = fetchUserRoles;

function initUserEvents() {
    const searchInput = document.getElementById('user-search');
    if (searchInput) {
        searchInput.oninput = function(e) {
            userSearch = e.target.value; fetchUsers(1);
        };
    }
    fetchUsers();
    // Nút thêm tài khoản
    const addBtn = document.getElementById('add-user-btn');
    if (addBtn) {
        addBtn.onclick = function() {
            fetchUserRoles().then(() => {
                document.getElementById('user-modal').style.display = 'flex';
                document.getElementById('user-modal-title').textContent = 'Thêm tài khoản';
                document.getElementById('user-id').value = '';
                document.getElementById('user-name').value = '';
                document.getElementById('user-email').value = '';
                document.getElementById('user-role-id').value = '';
                document.getElementById('user-password').value = '';
                document.getElementById('user-verified').checked = true;
            });
        };
    }
    // Nút Lưu tài khoản (thêm/sửa)
    const saveBtn = document.getElementById('user-save');
    if (saveBtn) {
        saveBtn.onclick = function() {
            if (window.savingUser) return;
            const id = document.getElementById('user-id').value;
            const fullName = document.getElementById('user-name').value.trim();
            const email = document.getElementById('user-email').value.trim();
            let roleId = document.getElementById('user-role-id').value;
            let password = document.getElementById('user-password').value.trim();
            const isVerified = document.getElementById('user-verified').checked ? true : false;
            // Kiểm tra dữ liệu đầu vào
            if (!fullName) {
                showUserAlert('Vui lòng nhập họ và tên!', 'error');
                return;
            }
            if (!email) {
                showUserAlert('Vui lòng nhập email!', 'error');
                return;
            }
            if (!id && !password) {
                showUserAlert('Vui lòng nhập mật khẩu!', 'error');
                return;
            }
            if (!roleId) {
                showUserAlert('Bạn phải chọn vai trò cho tài khoản!', 'error');
                return;
            }
            // Nếu đang thêm mà nhập '********' thì báo lỗi
            if (!id && password === '********') {
                showUserAlert('Mật khẩu không hợp lệ!', 'error');
                return;
            }
            if (roleId === '') roleId = null;
            const data = {
                full_name: fullName,
                email: email,
                is_verified: isVerified
            };
            if (roleId) data.role_id = roleId;
            // Nếu là sửa và mật khẩu là ******** thì không gửi lên API
            if (!id || (password && password !== '********')) {
                data.password = password;
            }
            window.savingUser = true;
            const method = id ? 'PUT' : 'POST';
            const url = id ? `/api/users/${id}` : '/api/users';
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
                window.savingUser = false;
                if (res.ok) {
                    // Nếu là sửa và có role_id, cập nhật roles
                    if (id && roleId) {
                        fetch(`/api/users/${id}/roles`, {
                            method: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ role_ids: [parseInt(roleId)] })
                        }).then(() => {
                            showUserAlert('Cập nhật tài khoản thành công!');
                            document.getElementById('user-modal').style.display = 'none';
                            fetchUsers(1);
                        });
                    } else {
                        showUserAlert(id ? 'Cập nhật tài khoản thành công!' : 'Thêm tài khoản thành công!');
                        document.getElementById('user-modal').style.display = 'none';
                        fetchUsers(1);
                    }
                } else {
                    let err;
                    try { err = await res.json(); } catch { err = {}; }
                    // Hiển thị tất cả lỗi trả về từ API
                    if (err.errors) {
                        let msg = Object.values(err.errors).map(arr => arr.join(' ')).join('\n');
                        showUserAlert(msg, 'error');
                    } else {
                        showUserAlert((err.message || 'Có lỗi xảy ra!') + (res.status ? ' (Mã lỗi: ' + res.status + ')' : ''), 'error');
                    }
                }
            })
            .catch((e) => {
                window.savingUser = false;
                showUserAlert('Có lỗi xảy ra khi gửi dữ liệu!', 'error');
            });
        };
    }
    // Nút hủy popup
    const cancelBtn = document.getElementById('user-cancel');
    if (cancelBtn) {
        cancelBtn.onclick = function() {
            document.getElementById('user-modal').style.display = 'none';
        };
    }
    // Nút xác nhận xóa
    const confirmYes = document.getElementById('user-confirm-yes');
    if (confirmYes) {
        confirmYes.onclick = function() {
            if (!window.userToDelete) return;
            const token = localStorage.getItem('token');
            fetch(`/api/users/${window.userToDelete}`, {
                method: 'DELETE',
                headers: { 'Authorization': 'Bearer ' + token }
            })
            .then(res => {
                if (res.ok) showUserAlert('Xóa tài khoản thành công!');
                else showUserAlert('Xóa tài khoản thất bại!', 'error');
                fetchUsers(userCurrentPage);
            });
            const modal = document.getElementById('user-confirm-modal');
            if (modal) modal.style.display = 'none';
            window.userToDelete = null;
        };
    }
    // Nút hủy xóa
    const confirmNo = document.getElementById('user-confirm-no');
    if (confirmNo) {
        confirmNo.onclick = function() {
            const modal = document.getElementById('user-confirm-modal');
            if (modal) modal.style.display = 'none';
            window.userToDelete = null;
        };
    }
    // Đóng popup modal
    const userModal = document.getElementById('user-modal');
    if (userModal) {
        userModal.onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };
    }
}
window.initUserEvents = initUserEvents;

window.confirmDeleteUser = function(id) {
    window.userToDelete = id;
    const modal = document.getElementById('user-confirm-modal');
    if (modal) modal.style.display = 'flex';
};

function showUserAlert(msg, type = 'success') {
    const box = document.getElementById('user-alert-box');
    if (!box) return;
    box.textContent = msg;
    box.style.background = type === 'success' ? '#43a047' : '#e53935';
    box.style.color = '#fff';
    box.style.display = 'block';
    setTimeout(() => { box.style.display = 'none'; }, 2500);
}
window.showUserAlert = showUserAlert;

window.editUser = function(id) {
    const token = localStorage.getItem('token');
    fetch(`/api/users/${id}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => res.json())
    .then(res => {
        const user = res.data ? res.data : res;
        Promise.all([
            fetchUserRoles(user.roles && user.roles.length ? user.roles[0].id : null)
        ]).then(() => {
            document.getElementById('user-modal').style.display = 'flex';
            document.getElementById('user-modal-title').textContent = 'Sửa tài khoản';
            document.getElementById('user-id').value = user.id;
            document.getElementById('user-name').value = user.full_name || user.name || '';
            document.getElementById('user-email').value = user.email || '';
            document.getElementById('user-role-id').value = user.roles && user.roles.length ? user.roles[0].id : '';
            // Luôn hiển thị mật khẩu là ******** khi sửa
            const passwordInput = document.getElementById('user-password');
            passwordInput.value = '********';
            passwordInput.placeholder = '';
            document.getElementById('user-verified').checked = !!user.is_verified;
        });
    });
}; 