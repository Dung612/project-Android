package com.example.tluresourcebooker;

import android.app.DatePickerDialog;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import android.app.DatePickerDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.viewpager2.widget.ViewPager2;
import com.google.android.material.textfield.TextInputEditText;
import java.text.SimpleDateFormat;
import android.widget.DatePicker;
import java.util.Calendar;
import com.example.tluresourcebooker.R;
import com.example.tluresourcebooker.adapter.ImageSliderAdapter;
import com.example.tluresourcebooker.model.Device;
import com.example.tluresourcebooker.model.Room;
import com.example.tluresourcebooker.model.RoomDetailResponse;
import com.example.tluresourcebooker.Network.ApiClient;
import com.example.tluresourcebooker.Network.ApiService;
import com.google.android.material.appbar.MaterialToolbar;
import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;
import com.google.android.material.textfield.TextInputEditText;

import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RoomDetailActivity extends AppCompatActivity {

    private static final String TAG = "RoomDetailActivity";
    private int roomId;

    // Khai báo các Views
    private MaterialToolbar toolbar;
    private ViewPager2 imageViewPager;
    private TabLayout tabLayoutIndicator;
    private ChipGroup chipGroupRoomDevices;
    private View infoRoomType, infoCapacity, infoLocation, infoRules, infoRoomName;
    private TextInputEditText editTextDate; // <<=== THÊM BIẾN NÀY

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_room_detail);

        if (getIntent().hasExtra("ROOM_ID")) {
            roomId = getIntent().getIntExtra("ROOM_ID", -1);
        }

        if (roomId == -1) {
            Toast.makeText(this, "Không tìm thấy thông tin phòng.", Toast.LENGTH_SHORT).show();
            finish();
            return;
        }

        initViews();
        setupToolbar();
        setupDatePicker(); // <<=== GỌI PHƯƠNG THỨC THIẾT LẬP LỊCH
        fetchRoomDetails();
    }

    private void initViews() {
        toolbar = findViewById(R.id.topAppBarDetail);
        imageViewPager = findViewById(R.id.imageViewPager);
        tabLayoutIndicator = findViewById(R.id.tabLayoutIndicator);
        chipGroupRoomDevices = findViewById(R.id.chipGroupRoomDevices);
        infoRoomName = findViewById(R.id.info_room_name);
        infoRoomType = findViewById(R.id.info_room_type);
        infoCapacity = findViewById(R.id.info_room_capacity);
        infoLocation = findViewById(R.id.info_room_location);
        infoRules = findViewById(R.id.info_room_rules);
        editTextDate = findViewById(R.id.editTextDate); // <<=== ÁNH XẠ VIEW
    }

    private void setupToolbar() {
        toolbar.setNavigationOnClickListener(v -> finish());
    }

    // === PHƯƠNG THỨC MỚI ĐỂ HIỂN THỊ DATE PICKER ===
    private void setupDatePicker() {
        editTextDate.setOnClickListener(v -> {
            // Lấy ngày tháng năm hiện tại để làm giá trị mặc định cho DatePickerDialog
            final Calendar calendar = Calendar.getInstance();
            int year = calendar.get(Calendar.YEAR);
            int month = calendar.get(Calendar.MONTH);
            int day = calendar.get(Calendar.DAY_OF_MONTH);

            // Tạo DatePickerDialog
            DatePickerDialog datePickerDialog = new DatePickerDialog(
                    RoomDetailActivity.this,
                    (view, selectedYear, selectedMonth, selectedDayOfMonth) -> {
                        // Khi người dùng chọn xong ngày
                        // Cập nhật Calendar object
                        calendar.set(selectedYear, selectedMonth, selectedDayOfMonth);

                        // Định dạng lại ngày để hiển thị trên EditText
                        SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy", Locale.getDefault());
                        editTextDate.setText(sdf.format(calendar.getTime()));

                        // TODO: Sau khi chọn ngày, bạn có thể gọi API khác để lấy các khung giờ khả dụng cho ngày đó
                        // và cập nhật chipGroupTimeSlots
                    },
                    year, month, day);

            // Giới hạn chỉ cho phép chọn từ ngày hôm nay trở đi (tùy chọn)
            datePickerDialog.getDatePicker().setMinDate(System.currentTimeMillis() - 1000);

            // Hiển thị dialog
            datePickerDialog.show();
        });
    }

    private void fetchRoomDetails() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        Call<RoomDetailResponse> call = apiService.getRoomDetails(roomId);

        call.enqueue(new Callback<RoomDetailResponse>() {
            @Override
            public void onResponse(Call<RoomDetailResponse> call, Response<RoomDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    Room room = response.body().getData();
                    populateUI(room);
                } else {
                    Toast.makeText(RoomDetailActivity.this, "Lỗi khi tải chi tiết phòng.", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<RoomDetailResponse> call, Throwable t) {
                Log.e(TAG, "API call failed: " + t.getMessage());
                Toast.makeText(RoomDetailActivity.this, "Lỗi kết nối mạng.", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void populateUI(Room room) {
        if (room == null) return;

        toolbar.setTitle(room.getName());

        // Thiết lập Image Slider
        if (room.getImages() != null && !room.getImages().isEmpty()) {
            ImageSliderAdapter sliderAdapter = new ImageSliderAdapter(this, room.getImages());
            imageViewPager.setAdapter(sliderAdapter);
            new TabLayoutMediator(tabLayoutIndicator, imageViewPager, (tab, position) -> {}).attach();
        } else {
            tabLayoutIndicator.setVisibility(View.GONE);
        }

        // Điền thông tin phòng
        setInfoRow(findViewById(R.id.info_room_name), "Tên phòng", room.getName());
        if (room.getRoomType() != null) {
            setInfoRow(infoRoomType, "Loại phòng", room.getRoomType().getName());
        }
        if (room.getCapacity() != null) {
            setInfoRow(infoCapacity, "Sức chứa", String.format(Locale.getDefault(), "%d người", room.getCapacity()));
        }
        setInfoRow(infoLocation, "Địa điểm", room.getLocation());
        setInfoRow(infoRules, "Quy định", room.getDescription());

        // Điền danh sách thiết bị
        chipGroupRoomDevices.removeAllViews();
        if (room.getDevices() != null && !room.getDevices().isEmpty()) {
            for (Device device : room.getDevices()) {
                Chip chip = new Chip(this);
                chip.setText(device.getName());
                chip.setClickable(false);
                chip.setCheckable(false);
                chipGroupRoomDevices.addView(chip);
            }
        } else {
            findViewById(R.id.chipGroupRoomDevices).setVisibility(View.GONE);
            // Có thể ẩn cả tiêu đề "Thiết bị trong phòng" nếu muốn
        }
    }

    // === PHIÊN BẢN setInfoRow ĐÃ ĐƯỢC SỬA LẠI (KHÔNG DÙNG ICON) ===
    private void setInfoRow(View infoRow, String label, String value) {
        if (infoRow != null) {
            ImageView icon = infoRow.findViewById(R.id.info_icon);
            TextView labelView = infoRow.findViewById(R.id.info_label);
            TextView valueView = infoRow.findViewById(R.id.info_value);

            // Ẩn icon đi
            icon.setVisibility(View.GONE);

            // Set text cho label và value
            if (labelView != null) labelView.setText(label);
            if (valueView != null) valueView.setText(value);
        }
    }
}