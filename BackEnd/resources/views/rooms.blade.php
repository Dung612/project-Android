<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Danh sách phòng học - Hệ thống quản lý phòng học</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 280px;
            height: 100vh;
            background: white;
            border-right: 1px solid #e9ecef;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px 0;
            overflow-y: auto;
            z-index: 1000;
        }
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px 30px;
            min-height: 100vh;
            width: calc(100% - 280px);
            overflow-x: hidden;
        }
        .nav-link {
            color: #333;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }
        .nav-link.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 500;
        }
        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        .table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .table th {
            border-bottom-width: 1px;
            text-transform: uppercase;
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
            padding: 1rem;
        }
        .table td {
            padding: 1rem;
            vertical-align: middle;
        }
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            margin: 0 3px;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
        }
        .search-box {
            max-width: 300px;
            margin-right: 15px;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .status-verified {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .status-pending {
            background: #fff3e0;
            color: #ef6c00;
        }
        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 1.5rem;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .logo-container {
            padding: 0 25px;
            margin-bottom: 20px;
        }
        .logo-container img {
            max-width: 100%;
            height: auto;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 240px;
            }
            .main-content {
                margin-left: 240px;
                width: calc(100% - 240px);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="/images/logoTLU.png" alt="Logo" class="img-fluid">
        </div>
        <div class="nav flex-column">
            <a href="/dashboard" class="nav-link">
                <i class="fas fa-home"></i>
                Trang chủ
            </a>
            <a href="/rooms" class="nav-link active">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Danh sách phòng học</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                <i class="fas fa-plus me-2"></i>Thêm phòng học
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control search-box" placeholder="Nhập tên hoặc vị trí...">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên phòng</th>
                                <th>Loại phòng</th>
                                <th>Vị trí</th>
                                <th>Sức chứa</th>
                                <th>Trạng thái</th>
                                <th>Mô tả</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $room)
                            <tr>
                                <td>{{ $room->name }}</td>
                                <td>{{ $room->roomType->name }}</td>
                                <td>{{ $room->location }}</td>
                                <td>{{ $room->capacity }}</td>
                                <td>
                                    <span class="status-badge {{ $room->status == 'available' ? 'status-verified' : 'status-pending' }}">
                                        {{ $room->status == 'available' ? 'Có sẵn' : 'Đang bảo trì' }}
                                    </span>
                                </td>
                                <td>{{ $room->description }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editRoom({{ $room->id }})">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteRoom({{ $room->id }})">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomModalLabel">Thêm phòng học mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoomForm" method="POST" action="/api/rooms">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên phòng</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="room_type_id" class="form-label">Loại phòng</label>
                            <select class="form-select" id="room_type_id" name="room_type_id" required>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Vị trí</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Sức chứa</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">Có sẵn</option>
                                <option value="0">Bảo trì</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Room Modal -->
    <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoomModalLabel">Chỉnh sửa phòng học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRoomForm">
                        @csrf
                        <input type="hidden" id="editRoomId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Tên phòng</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRoomTypeId" class="form-label">Loại phòng</label>
                            <select class="form-select" id="editRoomTypeId" name="room_type_id" required>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editLocation" class="form-label">Vị trí</label>
                            <input type="text" class="form-control" id="editLocation" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCapacity" class="form-label">Sức chứa</label>
                            <input type="number" class="form-control" id="editCapacity" name="capacity" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Trạng thái</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="1">Có sẵn</option>
                                <option value="0">Bảo trì</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa phòng học này không?</p>
                    <input type="hidden" id="deleteRoomId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add Room form submission
        document.getElementById('addRoomForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch('/api/rooms', {
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
                    alert(data.message || 'Không thể thêm phòng học');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
        });

        // Edit room function
        function editRoom(id) {
            fetch(`/api/rooms/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Không thể tải thông tin phòng học');
                    }
                    return response.json();
                })
                .then(response => {
                    if (response.success) {
                        const room = response.data;
                        console.log('Room data:', room); // Debug log
                        
                        // Populate form fields
                        document.getElementById('editRoomId').value = room.id;
                        document.getElementById('editName').value = room.name;
                        document.getElementById('editRoomTypeId').value = room.room_type_id;
                        document.getElementById('editLocation').value = room.location;
                        document.getElementById('editCapacity').value = room.capacity;
                        document.getElementById('editStatus').value = room.status ? "1" : "0";
                        document.getElementById('editDescription').value = room.description || '';

                        // Show modal
                        new bootstrap.Modal(document.getElementById('editRoomModal')).show();
                    } else {
                        alert('Không thể tải thông tin phòng học');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
        }

        // Edit Room form submission
        document.getElementById('editRoomForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const id = data.id;
            delete data.id; // Remove id from the data to be sent
            
            fetch(`/api/rooms/${id}`, {
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
                    alert(data.message || 'Không thể cập nhật phòng học');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
        });

        // Delete room function
        function deleteRoom(id) {
            if (confirm('Bạn có chắc chắn muốn xóa phòng học này?')) {
                fetch(`/api/rooms/${id}`, {
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
                        alert(data.message || 'Không thể xóa phòng học');
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