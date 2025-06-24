# Hướng dẫn Test API bằng Postman

## 🚀 Khởi động Server

```bash
php artisan serve
```

Server sẽ chạy tại: `http://127.0.0.1:8000`

## 📋 Các API Endpoint cần test

### **1. Lấy danh sách tất cả phòng**

**Method:** `GET`  
**URL:** `http://127.0.0.1:8000/api/rooms`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {your_token}  // Nếu có authentication
```

**Response mong đợi:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Phòng A101",
            "room_type": {
                "id": 1,
                "name": "Phòng họp nhỏ",
                "description": "Phòng họp dành cho nhóm 5-10 người"
            },
            "capacity": 8,
            "location": "Tầng 1 - Khu A",
            "description": "Phòng họp nhỏ với bàn tròn và ghế thoải mái",
            "status": true,
            "images": [
                "http://127.0.0.1:8000/images/rooms/room_a101_1.jpg",
                "http://127.0.0.1:8000/images/rooms/room_a101_2.jpg"
            ],
            "open_time": "08:00:00",
            "close_time": "22:00:00",
            "price": "100000.00"
        }
    ],
    "message": "Lấy danh sách phòng thành công"
}
```

---

### **2. Lấy thông tin chi tiết một phòng**

**Method:** `GET`  
**URL:** `http://127.0.0.1:8000/api/rooms/1`

**Response mong đợi:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Phòng A101",
        "room_type": {
            "id": 1,
            "name": "Phòng họp nhỏ",
            "description": "Phòng họp dành cho nhóm 5-10 người"
        },
        "devices": [
            {
                "id": 1,
                "name": "Máy chiếu Epson EB-X41",
                "device_type": {
                    "id": 1,
                    "name": "Máy chiếu",
                    "description": "Thiết bị chiếu hình ảnh lên màn hình"
                },
                "status": true,
                "description": "Máy chiếu độ phân giải cao, phù hợp cho thuyết trình"
            }
        ],
        "capacity": 8,
        "location": "Tầng 1 - Khu A",
        "description": "Phòng họp nhỏ với bàn tròn và ghế thoải mái",
        "status": true,
        "images": [
            "http://127.0.0.1:8000/images/rooms/room_a101_1.jpg",
            "http://127.0.0.1:8000/images/rooms/room_a101_2.jpg"
        ],
        "open_time": "08:00:00",
        "close_time": "22:00:00",
        "price": "100000.00"
    },
    "message": "Lấy thông tin phòng thành công"
}
```

---

### **3. Lọc phòng theo điều kiện**

**Method:** `GET`  
**URL:** `http://127.0.0.1:8000/api/rooms?status=true&room_type_id=1&min_capacity=10`

**Query Parameters:**
- `status=true` - Chỉ lấy phòng đang hoạt động
- `room_type_id=1` - Chỉ lấy phòng loại 1 (Phòng họp nhỏ)
- `min_capacity=10` - Chỉ lấy phòng có sức chứa >= 10 người

---

### **4. Lấy phòng có sẵn theo thời gian**

**Method:** `GET`  
**URL:** `http://127.0.0.1:8000/api/rooms/available?start_time=2025-01-15 09:00:00&end_time=2025-01-15 11:00:00`

**Query Parameters:**
- `start_time` - Thời gian bắt đầu (format: YYYY-MM-DD HH:mm:ss)
- `end_time` - Thời gian kết thúc (format: YYYY-MM-DD HH:mm:ss)

---

## 🔐 Authentication (nếu cần)

### **Đăng nhập để lấy token:**

**Method:** `POST`  
**URL:** `http://127.0.0.1:8000/api/login`

**Body (JSON):**
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "full_name": "Admin User",
            "email": "admin@example.com"
        },
        "token": "1|abc123def456..."
    },
    "message": "Đăng nhập thành công"
}
```

**Sử dụng token:**
```
Authorization: Bearer 1|abc123def456...
```

---

## 📱 Cách test trong Postman

### **Bước 1: Tạo Collection mới**
1. Mở Postman
2. Click "New" → "Collection"
3. Đặt tên: "Room Management API"

### **Bước 2: Tạo Request đầu tiên**
1. Click "Add request"
2. Đặt tên: "Get All Rooms"
3. Method: `GET`
4. URL: `http://127.0.0.1:8000/api/rooms`

### **Bước 3: Thêm Headers**
```
Content-Type: application/json
Accept: application/json
```

### **Bước 4: Test Response**
1. Click "Send"
2. Kiểm tra Status Code: `200`
3. Kiểm tra Response body có đúng format không

### **Bước 5: Tạo các Request khác**
- "Get Room Detail" - `GET http://127.0.0.1:8000/api/rooms/1`
- "Filter Rooms" - `GET http://127.0.0.1:8000/api/rooms?status=true`
- "Available Rooms" - `GET http://127.0.0.1:8000/api/rooms/available?start_time=2025-01-15 09:00:00&end_time=2025-01-15 11:00:00`

---

## 🧪 Test Cases

### **Test Case 1: Lấy tất cả phòng**
- **Expected:** Trả về 8 phòng với đầy đủ thông tin
- **Check:** Có trường `images` với đường dẫn đầy đủ

### **Test Case 2: Lấy phòng theo ID**
- **Expected:** Trả về 1 phòng với thông tin chi tiết
- **Check:** Có thông tin `room_type` và `devices`

### **Test Case 3: Lọc phòng**
- **Expected:** Trả về phòng theo điều kiện
- **Check:** Số lượng phòng giảm khi thêm filter

### **Test Case 4: Phòng có sẵn**
- **Expected:** Trả về phòng không bị booking trong thời gian đó
- **Check:** Chỉ có phòng `status: true`

---

## ⚠️ Lưu ý quan trọng

1. **Server phải đang chạy:** `php artisan serve`
2. **Database đã được seed:** `php artisan db:seed`
3. **Ảnh mẫu đã được tạo:** Trong `public/images/rooms/`
4. **CORS:** Nếu test từ frontend, có thể cần cấu hình CORS

---

## 🔍 Debug nếu có lỗi

### **Lỗi 404:**
- Kiểm tra URL có đúng không
- Kiểm tra server có đang chạy không

### **Lỗi 500:**
- Kiểm tra Laravel logs: `storage/logs/laravel.log`
- Kiểm tra database connection

### **Lỗi 401:**
- Kiểm tra authentication token
- Đăng nhập lại để lấy token mới

### **Response rỗng:**
- Kiểm tra database có dữ liệu không
- Chạy lại seeder: `php artisan db:seed` 