# Hướng dẫn API Room và Lưu trữ Ảnh

## 📁 Cấu trúc lưu trữ ảnh

### **Thư mục ảnh:**
```
public/
└── images/
    └── rooms/
        ├── room_a101_1.jpg
        ├── room_a101_2.jpg
        ├── room_b201_1.jpg
        └── ...
```

### **Database:**
- **Bảng:** `rooms`
- **Cột:** `images` (JSON)
- **Kiểu dữ liệu:** `["room_a101_1.jpg", "room_a101_2.jpg"]`

## 🚀 API Endpoints

### **1. Lấy danh sách phòng**
```http
GET /api/rooms
```

**Response:**
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
                "http://your-domain.com/images/rooms/room_a101_1.jpg",
                "http://your-domain.com/images/rooms/room_a101_2.jpg"
            ],
            "open_time": "08:00:00",
            "close_time": "22:00:00",
            "price": "100000.00"
        }
    ],
    "message": "Lấy danh sách phòng thành công"
}
```

### **2. Lấy thông tin chi tiết phòng**
```http
GET /api/rooms/{id}
```

### **3. Lọc phòng theo điều kiện**
```http
GET /api/rooms?status=true&room_type_id=1&min_capacity=10
```

### **4. Lấy phòng có sẵn**
```http
GET /api/rooms/available?start_time=2025-01-15 09:00:00&end_time=2025-01-15 11:00:00
```

## 📸 Cách lưu trữ ảnh

### **1. Upload ảnh:**
- Upload ảnh vào thư mục `public/images/rooms/`
- Đặt tên file theo format: `room_{room_id}_{index}.jpg`

### **2. Cập nhật database:**
```php
// Ví dụ cập nhật ảnh cho phòng
$room = Room::find(1);
$room->update([
    'images' => ['room_1_1.jpg', 'room_1_2.jpg', 'room_1_3.jpg']
]);
```

### **3. API Response:**
- Ảnh được trả về dưới dạng **đường dẫn đầy đủ**
- Format: `http://your-domain.com/images/rooms/filename.jpg`

## 🔧 Cấu hình

### **1. Tạo symbolic link (nếu cần):**
```bash
php artisan storage:link
```

### **2. Cấu hình filesystem trong `config/filesystems.php`:**
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

## 📝 Ví dụ sử dụng

### **Frontend (JavaScript):**
```javascript
// Lấy danh sách phòng
fetch('/api/rooms')
    .then(response => response.json())
    .then(data => {
        data.data.forEach(room => {
            console.log('Phòng:', room.name);
            console.log('Ảnh:', room.images); // Array các URL ảnh
        });
    });

// Hiển thị ảnh
room.images.forEach(imageUrl => {
    const img = document.createElement('img');
    img.src = imageUrl;
    img.alt = room.name;
    document.body.appendChild(img);
});
```

### **Mobile App:**
```dart
// Flutter example
class Room {
  final int id;
  final String name;
  final List<String> images;
  
  Room.fromJson(Map<String, dynamic> json)
      : id = json['id'],
        name = json['name'],
        images = List<String>.from(json['images']);
}

// Hiển thị ảnh
ListView.builder(
  itemCount: room.images.length,
  itemBuilder: (context, index) {
    return Image.network(room.images[index]);
  },
)
```

## ⚠️ Lưu ý quan trọng

1. **Bảo mật:** Chỉ cho phép upload file ảnh hợp lệ
2. **Kích thước:** Giới hạn kích thước file upload
3. **Tên file:** Sử dụng tên file an toàn, tránh ký tự đặc biệt
4. **Backup:** Backup thư mục ảnh thường xuyên
5. **CDN:** Cân nhắc sử dụng CDN cho production

## 🛠️ Tạo ảnh mẫu

Để test API, tạo một số file ảnh mẫu:

```bash
# Tạo file ảnh mẫu
echo "Sample image content" > public/images/rooms/room_a101_1.jpg
echo "Sample image content" > public/images/rooms/room_a101_2.jpg
echo "Sample image content" > public/images/rooms/room_b201_1.jpg
```

## 📊 Database Seeder

Seeder đã được cấu hình với dữ liệu mẫu:

```bash
php artisan db:seed
```

Sẽ tạo 8 phòng với ảnh mẫu sẵn sàng test. 