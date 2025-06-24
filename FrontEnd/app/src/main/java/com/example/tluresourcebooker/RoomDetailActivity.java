package com.example.tluresourcebooker;

import android.app.DatePickerDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import android.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.viewpager2.widget.ViewPager2;

import com.example.tluresourcebooker.R;
import com.example.tluresourcebooker.adapter.ImageSliderAdapter;
import com.example.tluresourcebooker.model.BookingDeviceItem;
import com.example.tluresourcebooker.model.BookingRequest;
import com.example.tluresourcebooker.model.BookingResponse;
import com.example.tluresourcebooker.model.Device;
import com.example.tluresourcebooker.model.Room;
import com.example.tluresourcebooker.model.RoomDetailResponse;
import com.example.tluresourcebooker.model.TimeSlot;
import com.example.tluresourcebooker.model.TimeSlotResponse;
import com.example.tluresourcebooker.Network.ApiClient;
import com.example.tluresourcebooker.Network.ApiService;
import com.google.android.material.appbar.MaterialToolbar;
import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;
import com.google.android.material.textfield.TextInputEditText;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;
import java.util.Locale;
import java.util.concurrent.atomic.AtomicInteger;

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
    private ChipGroup chipGroupTimeSlots;
    private View infoRoomName, infoRoomType, infoCapacity, infoLocation, infoRules;
    private TextInputEditText editTextDate;
    private Button buttonBookRoom;

    private ChipGroup chipGroupSelectableDevices;
    private String selectedDateForApi = "";

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
        setupDatePicker();
        fetchRoomDetails();
        loadInitialTimeSlots();

        buttonBookRoom.setOnClickListener(v -> handleBooking());
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
        editTextDate = findViewById(R.id.editTextDate);
        chipGroupTimeSlots = findViewById(R.id.chipGroupTimeSlots);
        buttonBookRoom = findViewById(R.id.buttonBookRoom);
        chipGroupSelectableDevices = findViewById(R.id.chipGroupSelectableDevices);
    }

    private void setupToolbar() {
        toolbar.setNavigationOnClickListener(v -> finish());
    }

    private void loadInitialTimeSlots() {
        Calendar today = Calendar.getInstance();
        SimpleDateFormat apiFormat = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
        String todayDateForApi = apiFormat.format(today.getTime());

        SimpleDateFormat displayFormat = new SimpleDateFormat("dd/MM/yyyy", Locale.getDefault());
        editTextDate.setText(displayFormat.format(today.getTime()));

        fetchAvailableTimeSlots(todayDateForApi);
    }

    private void setupDatePicker() {
        editTextDate.setOnClickListener(v -> {
            final Calendar calendar = Calendar.getInstance();
            int year = calendar.get(Calendar.YEAR);
            int month = calendar.get(Calendar.MONTH);
            int day = calendar.get(Calendar.DAY_OF_MONTH);

            DatePickerDialog datePickerDialog = new DatePickerDialog(
                    RoomDetailActivity.this,
                    (view, selectedYear, selectedMonth, selectedDayOfMonth) -> {
                        calendar.set(selectedYear, selectedMonth, selectedDayOfMonth);
                        SimpleDateFormat displayFormat = new SimpleDateFormat("dd/MM/yyyy", Locale.getDefault());
                        editTextDate.setText(displayFormat.format(calendar.getTime()));

                        SimpleDateFormat apiFormat = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());

                        selectedDateForApi = apiFormat.format(calendar.getTime());
                        fetchAvailableTimeSlots(selectedDateForApi);
                    },
                    year, month, day);

            datePickerDialog.getDatePicker().setMinDate(System.currentTimeMillis() - 1000);
            datePickerDialog.show();
        });
    }

    private void fetchAvailableTimeSlots(String date) {
        chipGroupTimeSlots.removeAllViews();

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        Call<TimeSlotResponse> call = apiService.getRoomAvailability(roomId, date);

        call.enqueue(new Callback<TimeSlotResponse>() {
            @Override
            public void onResponse(Call<TimeSlotResponse> call, Response<TimeSlotResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    displayTimeSlots(response.body().getData());
                } else {
                    Toast.makeText(RoomDetailActivity.this, "Lỗi khi tải khung giờ.", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<TimeSlotResponse> call, Throwable t) {
                Toast.makeText(RoomDetailActivity.this, "Lỗi kết nối khi tải khung giờ.", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void displayTimeSlots(List<TimeSlot> slots) {
        chipGroupTimeSlots.removeAllViews();
        if (slots == null || slots.isEmpty()) {
            Toast.makeText(this, "Không có khung giờ nào trong ngày này.", Toast.LENGTH_SHORT).show();
            return;
        }

        for (TimeSlot slot : slots) {
            Chip chip = new Chip(this);
            chip.setTag(slot);
            String chipText = String.format("Tiết %d (%s - %s)", slot.getPeriod(), slot.getStartTime(), slot.getEndTime());
            chip.setText(chipText);
            chip.setCheckable(true);

            if ("booked".equals(slot.getStatus())) {
                chip.setEnabled(false);
                chip.setChipBackgroundColorResource(R.color.grey_200);
            } else {
                chip.setEnabled(true);
            }
            chipGroupTimeSlots.addView(chip);
        }
    }

    private void handleBooking() {
        List<Integer> checkedChipIds = chipGroupTimeSlots.getCheckedChipIds();
        if (checkedChipIds.isEmpty()) {
            Toast.makeText(this, "Vui lòng chọn ít nhất một khung giờ.", Toast.LENGTH_SHORT).show();
            return;
        }

        if (selectedDateForApi.isEmpty()) {
            Toast.makeText(this, "Vui lòng chọn ngày.", Toast.LENGTH_SHORT).show();
            return;
        }

        SharedPreferences prefs = getSharedPreferences("AppPrefs_TLUBooker", MODE_PRIVATE);
        String authToken = "Bearer " + prefs.getString("AUTH_TOKEN", "");

        if (authToken.equals("Bearer ")) {
            Toast.makeText(this, "Lỗi xác thực. Vui lòng đăng nhập lại.", Toast.LENGTH_LONG).show();
            return;
        }

        List<Integer> selectedDeviceChipIds = chipGroupSelectableDevices.getCheckedChipIds();
        ArrayList<BookingDeviceItem> selectedDevices = new ArrayList<>();
        for (Integer chipId : selectedDeviceChipIds) {
            Chip chip = chipGroupSelectableDevices.findViewById(chipId);
            if (chip != null && chip.getTag() instanceof Integer) {
                selectedDevices.add(new BookingDeviceItem((Integer) chip.getTag()));
            }
        }

        // Hiện dialog để người dùng nhập mục đích và ghi chú
        showBookingPurposeDialog(checkedChipIds, authToken, selectedDevices);
    }
    private void showBookingPurposeDialog(List<Integer> checkedChipIds, String authToken, ArrayList<BookingDeviceItem> selectedDevices) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        LayoutInflater inflater = this.getLayoutInflater();
        View dialogView = inflater.inflate(R.layout.dialog_booking_purpose, null); // Cần tạo layout này
        builder.setView(dialogView);

        final EditText purposeEditText = dialogView.findViewById(R.id.editTextPurpose);
        final EditText noteEditText = dialogView.findViewById(R.id.editTextNote);

        builder.setPositiveButton("Xác nhận đặt", (dialog, id) -> {
            String purpose = purposeEditText.getText().toString().trim();
            String note = noteEditText.getText().toString().trim();
            if(purpose.isEmpty()){
                Toast.makeText(this, "Vui lòng nhập mục đích sử dụng.", Toast.LENGTH_SHORT).show();
                return;
            }
                    processAndSendBookings(checkedChipIds, authToken, selectedDevices, purpose, note);
        })
                .setNegativeButton("Hủy", (dialog, id) -> dialog.cancel());

        builder.create().show();
    }
    private void processAndSendBookings(List<Integer> checkedChipIds, String authToken, ArrayList<BookingDeviceItem> selectedDevices, String purpose, String note) {
        // Bước 1: Lấy và sắp xếp các TimeSlot đã chọn
        ArrayList<TimeSlot> selectedSlots = new ArrayList<>();
        for (Integer id : checkedChipIds) {
            Chip chip = chipGroupTimeSlots.findViewById(id);
            if (chip != null && chip.getTag() instanceof TimeSlot) {
                selectedSlots.add((TimeSlot) chip.getTag());
            }
        }
        // Sắp xếp các tiết theo thứ tự
        Collections.sort(selectedSlots, Comparator.comparingInt(TimeSlot::getPeriod));

        // Bước 2: Nhóm các tiết liên tiếp thành các "khối" (chunks)
        List<List<TimeSlot>> bookingChunks = new ArrayList<>();
        if (!selectedSlots.isEmpty()) {
            List<TimeSlot> currentChunk = new ArrayList<>();
            currentChunk.add(selectedSlots.get(0));
            bookingChunks.add(currentChunk);

            for (int i = 1; i < selectedSlots.size(); i++) {
                TimeSlot currentSlot = selectedSlots.get(i);
                TimeSlot previousSlot = selectedSlots.get(i - 1);

                if (currentSlot.getPeriod() == previousSlot.getPeriod() + 1) {
                    // Nếu tiết này liên tiếp, thêm vào khối hiện tại
                    currentChunk.add(currentSlot);
                } else {
                    // Nếu không liên tiếp, tạo một khối mới
                    currentChunk = new ArrayList<>();
                    currentChunk.add(currentSlot);
                    bookingChunks.add(currentChunk);
                }
            }
        }

        // Bước 3: Gửi API cho từng khối
        if (bookingChunks.isEmpty()) return;

        final int totalRequests = bookingChunks.size();
        AtomicInteger successfulRequests = new AtomicInteger(0);
        AtomicInteger failedRequests = new AtomicInteger(0);

        ApiService apiService = ApiClient.getClient().create(ApiService.class);

        for (List<TimeSlot> chunk : bookingChunks) {
            TimeSlot startSlot = chunk.get(0);
            TimeSlot endSlot = chunk.get(chunk.size() - 1);

            String startTime = selectedDateForApi + " " + startSlot.getStartTime() + ":00";
            String endTime = selectedDateForApi + " " + endSlot.getEndTime() + ":00";

            BookingRequest request = new BookingRequest(roomId, startTime, endTime, purpose, note, selectedDevices);
            Call<BookingResponse> call = apiService.createBooking(authToken, request);

            call.enqueue(new Callback<BookingResponse>() {
                @Override
                public void onResponse(Call<BookingResponse> call, Response<BookingResponse> response) {
                    if (response.isSuccessful()) {
                        successfulRequests.incrementAndGet();
                    } else {
                        failedRequests.incrementAndGet();
                    }
                    checkAllBookingsFinished(totalRequests, successfulRequests.get(), failedRequests.get());
                }

                @Override
                public void onFailure(Call<BookingResponse> call, Throwable t) {
                    failedRequests.incrementAndGet();
                    checkAllBookingsFinished(totalRequests, successfulRequests.get(), failedRequests.get());
                }
            });
        }
    }
    private void checkAllBookingsFinished(int total, int success, int failed) {
        if (success + failed == total) {
            // Tất cả các request đã hoàn thành
            if (success > 0) {
                // Nếu có ít nhất một request thành công
                Intent intent = new Intent(RoomDetailActivity.this, com.example.tluresourcebooker.BookingSuccessActivity.class);
                String message = String.format(Locale.getDefault(), "Đã đặt thành công %d/%d yêu cầu.", success, total);
                intent.putExtra("SUCCESS_MESSAGE", message);
                startActivity(intent);
                finish();
            } else {
                // Nếu tất cả đều thất bại
                Toast.makeText(this, "Tất cả các yêu cầu đặt phòng đều thất bại.", Toast.LENGTH_LONG).show();
            }
        }
    }
    private void fetchRoomDetails() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        Call<RoomDetailResponse> call = apiService.getRoomDetails(roomId);

        call.enqueue(new Callback<RoomDetailResponse>() {
            @Override
            public void onResponse(Call<RoomDetailResponse> call, Response<RoomDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    populateUI(response.body().getData());
                }
            }
            @Override
            public void onFailure(Call<RoomDetailResponse> call, Throwable t) {
                // Xử lý lỗi
            }
        });
    }

    private void populateUI(Room room) {
        if (room == null) return;

        toolbar.setTitle(room.getName());

        if (room.getImages() != null && !room.getImages().isEmpty()) {
            ImageSliderAdapter sliderAdapter = new ImageSliderAdapter(this, room.getImages());
            imageViewPager.setAdapter(sliderAdapter);
            new TabLayoutMediator(tabLayoutIndicator, imageViewPager, (tab, position) -> {}).attach();
        } else {
            imageViewPager.setVisibility(View.GONE);
            tabLayoutIndicator.setVisibility(View.GONE);
        }

        setInfoRow(infoRoomName, "Tên phòng", room.getName());
        if (room.getRoomType() != null) {
            setInfoRow(infoRoomType, "Loại phòng", room.getRoomType().getName());
        }
        if (room.getCapacity() != null) {
            setInfoRow(infoCapacity, "Sức chứa", String.format(Locale.getDefault(), "%d người", room.getCapacity()));
        }
        setInfoRow(infoLocation, "Địa điểm", room.getLocation());
        setInfoRow(infoRules, "Quy định", room.getDescription());

        chipGroupSelectableDevices.removeAllViews();
        if (room.getDevices() != null && !room.getDevices().isEmpty()) {
            for (Device device : room.getDevices()) {
                Chip chip = new Chip(this);
                chip.setText(device.getName());
                chip.setTag(device.getId()); // Lưu ID của thiết bị vào tag
                chip.setCheckable(true);
                chipGroupSelectableDevices.addView(chip);
            }
        }

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
            findViewById(R.id.title_devices).setVisibility(View.GONE);
        }
    }

    private void setInfoRow(View infoRow, String label, String value) {
        if (infoRow != null) {
            ImageView icon = infoRow.findViewById(R.id.info_icon);
            TextView labelView = infoRow.findViewById(R.id.info_label);
            TextView valueView = infoRow.findViewById(R.id.info_value);
            if (icon != null) icon.setVisibility(View.GONE);
            if (labelView != null) labelView.setText(label);
            if (valueView != null) valueView.setText(value);
        }
    }
}