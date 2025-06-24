package com.example.tluresourcebooker;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.FragmentResultListener;
import androidx.recyclerview.widget.RecyclerView;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;
import android.widget.Toast;

import com.example.tluresourcebooker.adapter.HorizontalRoomAdapter;
import com.example.tluresourcebooker.adapter.MainListAdapter;
import com.example.tluresourcebooker.filter.CapacityFilterBottomSheet;
import com.example.tluresourcebooker.filter.DeviceFilterBottomSheet;
import com.example.tluresourcebooker.filter.TimeFilterBottomSheet;
import com.example.tluresourcebooker.model.Room;
import com.example.tluresourcebooker.model.RoomListResponse;
import com.example.tluresourcebooker.Network.ApiClient;
import com.example.tluresourcebooker.Network.ApiService;
import com.example.tluresourcebooker.RoomDetailActivity;


import com.google.android.material.button.MaterialButton;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends AppCompatActivity implements HorizontalRoomAdapter.OnRoomClickListener{

    private MaterialButton buttonFilterDevice;
    private MaterialButton buttonFilterTime;
    private MaterialButton buttonFilterCapacity;

    private static final String TAG = "MainActivity";
    private RecyclerView mainRecyclerView;
    private MainListAdapter mainListAdapter;
    private List<Object> displayList = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        mainRecyclerView = findViewById(R.id.mainRecyclerView);
        mainListAdapter = new MainListAdapter(displayList, this);
        mainRecyclerView.setAdapter(mainListAdapter);

        // --- Ánh xạ nút ---
        // buttonFilterDevice = findViewById(R.id.buttonFilterDevice);
        // buttonFilterTime = findViewById(R.id.buttonFilterTime);
        // buttonFilterCapacity = findViewById(R.id.buttonFilterCapacity); // Giả sử ID là buttonFilterCapacity

        // --- Sự kiện click cho các nút lọc ---
        // buttonFilterDevice.setOnClickListener(v -> { ... });
        // buttonFilterTime.setOnClickListener(v -> { ... });
        // buttonFilterCapacity.setOnClickListener(v -> {
        //     CapacityFilterBottomSheet capacitySheet = CapacityFilterBottomSheet.newInstance();
        //     capacitySheet.show(getSupportFragmentManager(), CapacityFilterBottomSheet.TAG);
        // });

        fetchRooms();
        // --- Lắng nghe kết quả từ các BottomSheet ---
        setupFragmentResultListeners();
    }

    private void setupFragmentResultListeners() {
        // Lắng nghe kết quả từ bộ lọc thiết bị
        getSupportFragmentManager().setFragmentResultListener(DeviceFilterBottomSheet.REQUEST_KEY, this, (requestKey, bundle) -> {
            // ... (code xử lý lọc thiết bị giữ nguyên)
        });

        // Lắng nghe kết quả từ bộ lọc khung giờ
        getSupportFragmentManager().setFragmentResultListener(TimeFilterBottomSheet.REQUEST_KEY, this, (requestKey, bundle) -> {
            // ... (code xử lý lọc khung giờ giữ nguyên)
        });

        // Lắng nghe kết quả từ bộ lọc sức chứa
        getSupportFragmentManager().setFragmentResultListener(CapacityFilterBottomSheet.REQUEST_KEY, this, (requestKey, bundle) -> {
            int selectedCapacity = bundle.getInt(CapacityFilterBottomSheet.RESULT_KEY_CAPACITY);

            if (selectedCapacity != -1) { // Kiểm tra nếu có giá trị hợp lệ được chọn
                String message = "Lọc theo sức chứa: " + selectedCapacity + " người";
                Toast.makeText(MainActivity.this, message, Toast.LENGTH_LONG).show();
                // TODO: Thực hiện logic lọc danh sách phòng với sức chứa đã chọn
            } else {
                Toast.makeText(MainActivity.this, "Đã xóa bộ lọc sức chứa.", Toast.LENGTH_SHORT).show();
                // TODO: Tải lại danh sách phòng không có bộ lọc sức chứa
            }
        });
    }
    private void fetchRooms() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        Call<RoomListResponse> call = apiService.getAllRooms();

        call.enqueue(new Callback<RoomListResponse>() {
            @Override
            public void onResponse(Call<RoomListResponse> call, Response<RoomListResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    List<Room> allRooms = response.body().getData();
                    // Nếu lấy dữ liệu thành công, gọi phương thức để xử lý và hiển thị
                    processAndDisplayRooms(allRooms);
                } else {
                    Toast.makeText(MainActivity.this, "Lỗi khi tải dữ liệu phòng", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<RoomListResponse> call, Throwable t) {
                Log.e(TAG, "Lỗi API: " + t.getMessage());
                Toast.makeText(MainActivity.this, "Lỗi kết nối mạng", Toast.LENGTH_SHORT).show();
            }
        });
    }

    /**
     * Phương thức này nhận danh sách phòng phẳng từ API,
     * nhóm chúng lại theo loại phòng và chuẩn bị dữ liệu cho MainListAdapter.
     */
    private void processAndDisplayRooms(List<Room> rooms) {
        if (rooms == null || rooms.isEmpty()) {
            Toast.makeText(this, "Không có phòng nào để hiển thị.", Toast.LENGTH_SHORT).show();
            return;
        }

        // Bước 1: Nhóm các phòng theo tên của loại phòng (room_type.name).
        // Sử dụng LinkedHashMap để duy trì thứ tự các loại phòng như khi chúng xuất hiện lần đầu.
        Map<String, List<Room>> groupedRooms = new LinkedHashMap<>();
        for (Room room : rooms) {
            if (room.getRoomType() != null && room.getRoomType().getName() != null) {
                String roomTypeName = room.getRoomType().getName();
                // Nếu chưa có nhóm nào cho loại phòng này, hãy tạo một nhóm mới.
                if (!groupedRooms.containsKey(roomTypeName)) {
                    groupedRooms.put(roomTypeName, new ArrayList<>());
                }
                // Thêm phòng hiện tại vào nhóm tương ứng.
                groupedRooms.get(roomTypeName).add(room);
            }
        }

        // Bước 2: Tạo danh sách hiển thị cho adapter.
        // Danh sách này sẽ chứa xen kẽ: Tiêu đề (String), Danh sách phòng (List<Room>), Tiêu đề, Danh sách phòng...
        displayList.clear(); // Xóa dữ liệu cũ trước khi thêm mới
        for (Map.Entry<String, List<Room>> entry : groupedRooms.entrySet()) {
            displayList.add(entry.getKey());      // Thêm tiêu đề nhóm (ví dụ: "Phòng họp nhỏ")
            displayList.add(entry.getValue());    // Thêm danh sách các phòng thuộc nhóm đó
        }

        // Bước 3: Thông báo cho adapter rằng dữ liệu đã thay đổi để nó cập nhật lại giao diện.
        mainListAdapter.notifyDataSetChanged();
    }
    @Override
    public void onRoomClick(Room room) {
        Toast.makeText(this, "Bạn đã chọn: " + room.getName(), Toast.LENGTH_SHORT).show();
        Intent intent = new Intent(MainActivity.this, RoomDetailActivity.class);
        intent.putExtra("ROOM_ID", room.getId()); // Truyền ID của phòng
        startActivity(intent);
    }
    // Tạm thời, bạn có thể thêm một nút vào activity_main.xml để test việc mở BottomSheet
}
