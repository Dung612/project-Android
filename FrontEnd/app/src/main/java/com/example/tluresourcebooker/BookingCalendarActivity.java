package com.example.tluresourcebooker;

import android.app.DatePickerDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.tluresourcebooker.R;
import com.example.tluresourcebooker.adapter.BookingsOnDateAdapter;
import com.example.tluresourcebooker.model.Booking;
import com.example.tluresourcebooker.model.BookingHistoryResponse;
import com.example.tluresourcebooker.Network.ApiClient;
import com.example.tluresourcebooker.Network.ApiService;
import com.google.android.material.appbar.MaterialToolbar;
import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.android.material.textfield.TextInputEditText;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class BookingCalendarActivity extends AppCompatActivity {

    private TextInputEditText editTextSelectDate;
    private RecyclerView recyclerView;
    private TextView textViewNoBookings;
    private List<Booking> allApprovedBookings = new ArrayList<>();
    private BookingsOnDateAdapter adapter;
    private BottomNavigationView bottomNavigationView;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_booking_calendar);

        MaterialToolbar toolbar = findViewById(R.id.topAppBarCalendar);
        toolbar.setNavigationOnClickListener(v -> finish());

        editTextSelectDate = findViewById(R.id.editTextSelectDate);
        recyclerView = findViewById(R.id.recyclerViewBookingsOnDate);
        textViewNoBookings = findViewById(R.id.textViewNoBookings);

        setupDatePicker();
        setupRecyclerView();
        fetchApprovedBookings(); // Tải tất cả lịch đặt trước
        bottomNavigationView = findViewById(R.id.bottom_navigation_calendar);

        setupBottomNavigation();
    }
    private void setupBottomNavigation() {
        if (bottomNavigationView == null) {
            Log.e("BookingCalendar", "BottomNavigationView not found in layout!");
            return;
        }
        bottomNavigationView.setSelectedItemId(R.id.nav_schedule);
        bottomNavigationView.setOnItemSelectedListener(item -> {
            int itemId = item.getItemId();
            if (itemId == R.id.nav_home) {
                startActivity(new Intent(getApplicationContext(), MainActivity.class));
                overridePendingTransition(0, 0);
                finish(); // Đóng activity hiện tại
                return true;
            } else if (itemId == R.id.nav_schedule) {
                // Đang ở màn hình này rồi
                return true;
            } else if (itemId == R.id.nav_history) {
                startActivity(new Intent(getApplicationContext(), BookingHistoryActivity.class));
                overridePendingTransition(0, 0);
                finish(); // Đóng activity hiện tại
                return true;
            } else if (itemId == R.id.nav_user) {
                startActivity(new Intent(getApplicationContext(), ProfileActivity.class));
                overridePendingTransition(0, 0);
                finish(); // Đóng activity hiện tại
                return true;
            }
            return false;
        });
    }

    private void setupDatePicker() {
        editTextSelectDate.setOnClickListener(v -> {
            final Calendar calendar = Calendar.getInstance();
            int year = calendar.get(Calendar.YEAR);
            int month = calendar.get(Calendar.MONTH);
            int day = calendar.get(Calendar.DAY_OF_MONTH);

            DatePickerDialog datePickerDialog = new DatePickerDialog(
                    BookingCalendarActivity.this,
                    (view, selectedYear, selectedMonth, selectedDayOfMonth) -> {
                        calendar.set(selectedYear, selectedMonth, selectedDayOfMonth);

                        // Cập nhật text và lọc danh sách
                        updateDateAndFilter(calendar);
                    },
                    year, month, day);
            datePickerDialog.show();
        });
    }

    private void setupRecyclerView() {
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        adapter = new BookingsOnDateAdapter(new ArrayList<>());
        recyclerView.setAdapter(adapter);
    }

    private void fetchApprovedBookings() {
        SharedPreferences prefs = getSharedPreferences("AppPrefs_TLUBooker", MODE_PRIVATE);
        String token = "Bearer " + prefs.getString("AUTH_TOKEN", "");

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        Call<BookingHistoryResponse> call = apiService.getBookingHistory(token, "approved");

        call.enqueue(new Callback<BookingHistoryResponse>() {
            @Override
            public void onResponse(Call<BookingHistoryResponse> call, Response<BookingHistoryResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    allApprovedBookings = response.body().getData();
                    // Tự động chọn ngày hôm nay và hiển thị lịch đặt
                    updateDateAndFilter(Calendar.getInstance());
                }
            }
            @Override
            public void onFailure(Call<BookingHistoryResponse> call, Throwable t) {
                Toast.makeText(BookingCalendarActivity.this, "Lỗi tải lịch đặt", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void updateDateAndFilter(Calendar selectedCal) {
        // Cập nhật text trên EditText
        SimpleDateFormat displayFormat = new SimpleDateFormat("dd/MM/yyyy", Locale.getDefault());
        editTextSelectDate.setText(displayFormat.format(selectedCal.getTime()));

        // Lọc danh sách booking cho ngày đã chọn
        List<Booking> bookingsForSelectedDate = new ArrayList<>();
        for (Booking booking : allApprovedBookings) {
            try {
                SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
                Date bookingDate = sdf.parse(booking.getStartTime());
                if(bookingDate != null) {
                    Calendar bookingCal = Calendar.getInstance();
                    bookingCal.setTime(bookingDate);

                    if(bookingCal.get(Calendar.YEAR) == selectedCal.get(Calendar.YEAR) &&
                            bookingCal.get(Calendar.DAY_OF_YEAR) == selectedCal.get(Calendar.DAY_OF_YEAR)) {
                        bookingsForSelectedDate.add(booking);
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        }

        // Cập nhật RecyclerView
        if (bookingsForSelectedDate.isEmpty()) {
            recyclerView.setVisibility(View.GONE);
            textViewNoBookings.setVisibility(View.VISIBLE);
        } else {
            recyclerView.setVisibility(View.VISIBLE);
            textViewNoBookings.setVisibility(View.GONE);
            adapter = new BookingsOnDateAdapter(bookingsForSelectedDate);
            recyclerView.setAdapter(adapter);
        }
    }
}