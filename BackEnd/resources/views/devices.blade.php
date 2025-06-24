<style>
    /* Style tương tự rooms.blade.php, có thể chỉnh lại cho phù hợp thiết bị */
    .main {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        padding: 32px 28px;
        margin-bottom: 40px;
    }
    .main h2 {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 24px;
    }
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
    .edit-btn { background: #2196f3; color: #fff; border: none; border-radius: 4px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold; margin-right: 6px; padding: 0; transition: background 0.2s; }
    .edit-btn:hover { background: #1565c0; }
    .delete-btn { background: #e53935; color: #fff; border: none; border-radius: 4px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold; padding: 0; transition: background 0.2s; }
    .delete-btn:hover { background: #b71c1c; }
    .pagination { display: flex; justify-content: center; align-items: center; margin-top: 18px; gap: 8px; }
    .pagination button { border: none; background: none; font-size: 18px; cursor: pointer; color: #1976d2; }
    .pagination .active { font-weight: bold; border-bottom: 2px solid #1976d2; }
    .confirm-modal { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; z-index: 1000; }
    .confirm-box { background: #fff; padding: 32px 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.12); text-align: center; }
    .confirm-box button { margin: 0 12px; }
    .action-btns { display: flex; gap: 6px; align-items: center; }
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
<main class="main">
    <div class="page-title">Danh sách thiết bị</div>
    <div class="table-container">
        <div class="search-bar">
            <input type="text" id="device-search" placeholder="Nhập tên thiết bị">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <button class="btn" style="margin-left:auto;" id="add-device-btn">Thêm thiết bị</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Tên thiết bị</th>
                    <th>Loại</th>
                    <th>Vị trí</th>
                    <th>Trạng thái</th>
                    <th>Mô tả</th>
                    <th>Chỉnh sửa</th>
                </tr>
            </thead>
            <tbody id="device-table-body">
                <!-- Dữ liệu sẽ được render ở đây, cần đảm bảo JS không render cột id -->
            </tbody>
        </table>
        <div class="pagination" id="device-pagination"></div>
    </div>
    <!-- Modal xác nhận xóa thiết bị -->
    <div id="device-confirm-modal" class="confirm-modal" style="display:none;">
        <div class="confirm-box">
            <div style="margin-bottom:18px;font-size:17px;">Bạn có thật sự muốn xóa thiết bị không?</div>
            <div class="form-actions" style="justify-content:center;">
                <button class="btn" id="device-confirm-yes">Có</button>
                <button class="btn" id="device-confirm-no" style="background:#e53935;">Không</button>
            </div>
        </div>
    </div>
    <!-- Popup form Thêm/Sửa thiết bị -->
    <div id="device-modal" class="confirm-modal" style="display:none;">
        <div class="confirm-box" style="min-width:350px;">
            <h3 id="device-modal-title">Thêm thiết bị</h3>
            <form id="device-form">
                <input type="hidden" id="device-id">
                <div class="form-row">
                    <label for="device-name">Tên thiết bị</label>
                    <input type="text" id="device-name" placeholder="Tên thiết bị">
                </div>
                <div class="form-row">
                    <label for="device-type-id">Loại thiết bị</label>
                    <select id="device-type-id"></select>
                </div>
                <div class="form-row">
                    <label for="device-location">Vị trí</label>
                    <input type="text" id="device-location" placeholder="Vị trí">
                </div>
                <div class="form-row">
                    <label for="device-status">Trạng thái</label>
                    <select id="device-status">
                        <option value="1">Đang hoạt động</option>
                        <option value="0">Bảo trì</option>
                    </select>
                </div>
                <div class="form-row">
                    <label for="device-description">Mô tả</label>
                    <textarea id="device-description" placeholder="Mô tả"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn" id="device-save">Lưu</button>
                    <button type="button" class="btn" id="device-cancel">Hủy</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Thông báo -->
    <div id="device-alert-box" style="display:none;position:fixed;top:30px;right:30px;z-index:2000;padding:16px 28px;border-radius:8px;font-weight:bold;"></div>
</main>
<!-- Modal thêm/sửa thiết bị, alert-box, ... sẽ được load động bằng JS nếu cần -->
<script src="/js/devices.js"></script>
<script>initDeviceEvents();</script>
</body>
</html> 