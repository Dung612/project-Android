<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tên phòng</th>
                                <th>Loại phòng</th>
                                <th>Vị trí</th>
                                <th>Sức chứa</th>
                                <th>Trạng thái</th>
                                <th>Giá</th>
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
                                <td>{{ number_format($room->price) }} VNĐ</td>
                                <td>
                                    <button class="btn btn-primary btn-action" onclick="editRoom({{ $room->id }})" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-action" onclick="deleteRoom({{ $room->id }})" title="Xóa">
                                        <i class="fas fa-trash"></i>
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
    <div class="modal fade" id="addRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm phòng học mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoomForm">
                        <div class="mb-3">
                            <label class="form-label">Tên phòng</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loại phòng</label>
                            <select class="form-select" name="room_type_id" required>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vị trí</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sức chứa</label>
                            <input type="number" class="form-control" name="capacity" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status" required>
                                <option value="available">Có sẵn</option>
                                <option value="maintenance">Đang bảo trì</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá</label>
                            <input type="number" class="form-control" name="price" value="0" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="saveRoom()">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Room Modal -->
    <div class="modal fade" id="editRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa phòng học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editRoomForm">
                        <input type="hidden" name="id">
                        <div class="mb-3">
                            <label class="form-label">Tên phòng</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loại phòng</label>
                            <select class="form-select" name="room_type_id" required>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vị trí</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sức chứa</label>
                            <input type="number" class="form-control" name="capacity" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status" required>
                                <option value="available">Có sẵn</option>
                                <option value="maintenance">Đang bảo trì</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá</label>
                            <input type="number" class="form-control" name="price" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="updateRoom()">Cập nhật</button>
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
        // Thêm phòng mới
        function saveRoom() {
            const form = document.getElementById('addRoomForm');
            const formData = new FormData(form);
            
            fetch('/api/rooms', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            });
        }

        // Lấy thông tin phòng để chỉnh sửa
        function editRoom(id) {
            fetch(`/api/rooms/${id}`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('editRoomForm');
                    const room = data.data;
                    
                    form.id.value = room.id;
                    form.name.value = room.name;
                    form.room_type_id.value = room.room_type_id;
                    form.location.value = room.location;
                    form.capacity.value = room.capacity;
                    form.status.value = room.status;
                    form.price.value = room.price;

                    new bootstrap.Modal(document.getElementById('editRoomModal')).show();
                });
        }

        // Cập nhật thông tin phòng
        function updateRoom() {
            const form = document.getElementById('editRoomForm');
            const formData = new FormData(form);
            const id = form.id.value;
            
            fetch(`/api/rooms/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            });
        }

        // Hiển thị modal xác nhận xóa
        function deleteRoom(id) {
            document.getElementById('deleteRoomId').value = id;
            new bootstrap.Modal(document.getElementById('deleteRoomModal')).show();
        }

        // Xác nhận xóa phòng
        function confirmDelete() {
            const id = document.getElementById('deleteRoomId').value;
            
            fetch(`/api/rooms/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            });
        }
    </script>
</body>
</html> 