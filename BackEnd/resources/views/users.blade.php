<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar .logo {
            padding: 0 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .sidebar .logo img {
            max-width: 100px;
        }
        .nav-item {
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        .nav-item i {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }
        .nav-item:hover,
        .nav-item.active {
            background: #f0f7ff;
            color: #0d6efd;
            border-left-color: #0d6efd;
            text-decoration: none;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .page-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
        }
        .page-title i {
            margin-right: 10px;
            color: #0d6efd;
        }
        .btn-add {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-add:hover {
            background-color: #0b5ed7;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        .badge-role {
            background-color: #0d6efd;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-action {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            margin-right: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
        }
        .btn-edit {
            background-color: #0d6efd;
            color: white;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-edit:hover {
            background-color: #0b5ed7;
            color: white;
            text-decoration: none;
        }
        .btn-delete:hover {
            background-color: #bb2d3b;
            color: white;
            text-decoration: none;
        }
        .modal-content {
            border-radius: 8px;
        }
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
        }
        .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 8px 8px;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <img src="/images/logoTLU.png" alt="Logo TLU">
            </div>
            <a href="/dashboard" class="nav-item">
                <i class="fas fa-home"></i>
                Trang chủ
            </a>
            <a href="/rooms" class="nav-item">
                <i class="fas fa-door-open"></i>
                Phòng học
            </a>
            <a href="/users" class="nav-item active">
                <i class="fas fa-users"></i>
                Tài khoản
            </a>
            <a href="/devices" class="nav-item">
                <i class="fas fa-desktop"></i>
                Thiết bị
            </a>
            <a href="/settings" class="nav-item">
                <i class="fas fa-cog"></i>
                Cài đặt
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <div class="page-title">
                    <i class="fas fa-users"></i>
                    Danh sách tài khoản
                </div>
                
                <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus"></i>
                    Thêm tài khoản
                </button>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge-role">
                                        <i class="fas fa-user-tag"></i>
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                <button class="btn-action btn-edit" onclick="editUser({{ $user->id }})">
                                    <i class="fas fa-edit"></i>
                                    Sửa
                                </button>
                                <button class="btn-action btn-delete" onclick="deleteUser({{ $user->id }})">
                                    <i class="fas fa-trash"></i>
                                    Xóa
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}

                <!-- Add User Modal -->
                <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserModalLabel">
                                    <i class="fas fa-user-plus"></i>
                                    Thêm tài khoản mới
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addUserForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user"></i>
                                            Tên
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope"></i>
                                            Email
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock"></i>
                                            Mật khẩu
                                        </label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="fas fa-lock"></i>
                                            Xác nhận mật khẩu
                                        </label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times"></i>
                                            Hủy
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i>
                                            Lưu
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit User Modal -->
                <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">
                                    <i class="fas fa-user-edit"></i>
                                    Sửa tài khoản
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editUserForm">
                                    @csrf
                                    <input type="hidden" id="edit_user_id" name="user_id">
                                    <div class="mb-3">
                                        <label for="edit_name" class="form-label">
                                            <i class="fas fa-user"></i>
                                            Tên
                                        </label>
                                        <input type="text" class="form-control" id="edit_name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_email" class="form-label">
                                            <i class="fas fa-envelope"></i>
                                            Email
                                        </label>
                                        <input type="email" class="form-control" id="edit_email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_password" class="form-label">
                                            <i class="fas fa-lock"></i>
                                            Mật khẩu mới (để trống nếu không đổi)
                                        </label>
                                        <input type="password" class="form-control" id="edit_password" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_password_confirmation" class="form-label">
                                            <i class="fas fa-lock"></i>
                                            Xác nhận mật khẩu mới
                                        </label>
                                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times"></i>
                                            Hủy
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i>
                                            Lưu
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add User form submission
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch('/api/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Có lỗi xảy ra');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Không thể thêm tài khoản');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
        });

        // Edit user function
        function editUser(id) {
            fetch(`/api/users/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('edit_user_id').value = data.user.id;
                    document.getElementById('edit_name').value = data.user.full_name;
                    document.getElementById('edit_email').value = data.user.email;
                    
                    // Clear password fields
                    document.getElementById('edit_password').value = '';
                    document.getElementById('edit_password_confirmation').value = '';
                    
                    // Show modal
                    new bootstrap.Modal(document.getElementById('editUserModal')).show();
                } else {
                    alert(data.message || 'Không thể lấy thông tin tài khoản');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Không thể lấy thông tin tài khoản');
            });
        }

        // Edit User form submission
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const userId = document.getElementById('edit_user_id').value;
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Remove empty password fields from the data
            if (!data.password) {
                delete data.password;
                delete data.password_confirmation;
            }
            
            fetch(`/api/users/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Có lỗi xảy ra');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Không thể cập nhật tài khoản');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
        });

        // Delete user function
        function deleteUser(id) {
            if (confirm('Bạn có chắc chắn muốn xóa tài khoản này?')) {
                fetch(`/api/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Có lỗi xảy ra');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Không thể xóa tài khoản');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
            }
        }
    </script>
</body>
</html>