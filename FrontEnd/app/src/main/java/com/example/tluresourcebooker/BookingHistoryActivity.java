package com.example.tluresourcebooker;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.example.tluresourcebooker.R;
import com.example.tluresourcebooker.adapter.BookingHistoryAdapter;
import com.example.tluresourcebooker.model.Booking;
import com.example.tluresourcebooker.model.BookingHistoryResponse;
import com.example.tluresourcebooker.Network.ApiClient;
import com.example.tluresourcebooker.Network.ApiService;
import com.google.android.material.appbar.MaterialToolbar;
import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.android.material.chip.ChipGroup;
import java.util.ArrayList;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class BookingHistoryActivity extends AppCompatActivity {

    private RecyclerView recyclerViewHistory;
    private BookingHistoryAdapter adapter;
    private List<Booking> bookingList = new ArrayList<>();
    private ChipGroup chipGroupStatus;

    private BottomNavigationView bottomNavigationView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_booking_history);
        MaterialToolbar toolbar = findViewById(R.id.topAppBarHistory);
        toolbar.setNavigationOnClickListener(v -> finish());

        recyclerViewHistory = findViewById(R.id.recyclerViewHistory);
        recyclerViewHistory.setLayoutManager(new LinearLayoutManager(this));
        adapter = new BookingHistoryAdapter(this, bookingList);
        recyclerViewHistory.setAdapter(adapter);

        chipGroupStatus = findViewById(R.id.chipGroupStatus);
        bottomNavigationView = findViewById(R.id.bottom_navigation_history);

        setupFilterListeners();
        setupBottomNavigation();

        chipGroupStatus.check(R.id.chip_all);

    }
    private void setupBottomNavigation() {
        // Đặt mục "Lịch sử đặt" là được chọn
        bottomNavigationView.setSelectedItemId(R.id.nav_history);
        bottomNavigationView.setOnItemSelectedListener(item -> {
            int itemId = item.getItemId();
            if (itemId == R.id.nav_home) {
                startActivity(new Intent(getApplicationContext(), MainActivity.class));
                overridePendingTransition(0, 0);
                return true;
            } else if (itemId == R.id.nav_schedule) {
                startActivity(new Intent(getApplicationContext(), BookingCalendarActivity.class));
                overridePendingTransition(0, 0);
                return true;
            } else if (itemId == R.id.nav_history) {
                return true;
            } else if (itemId == R.id.nav_user) {
                startActivity(new Intent(getApplicationContext(), ProfileActivity.class));
                overridePendingTransition(0, 0);
                return true;
            }
            return false;
        });
    }

    private void setupFilterListeners() {
        chipGroupStatus.setOnCheckedChangeListener((group, checkedId) -> {
            String statusFilter = null;
            if (checkedId == R.id.chip_completed) {
                statusFilter = "approved";
            } else if (checkedId == R.id.chip_cancelled) {
                statusFilter = "cancelled";
            } else if (checkedId == R.id.chip_absent) {
                statusFilter = "absent";
            }
            fetchBookingHistory(statusFilter);
        });
    }

    private void fetchBookingHistory(String status) {
        SharedPreferences prefs = getSharedPreferences("AppPrefs_TLUBooker", MODE_PRIVATE);
        String token = "Bearer " + prefs.getString("AUTH_TOKEN", "");
        if (token.equals("Bearer ")) {
            Toast.makeText(this, "Lỗi xác thực, vui lòng đăng nhập lại", Toast.LENGTH_SHORT).show();
            return;
        }

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        Call<BookingHistoryResponse> call = apiService.getBookingHistory(token, status);

        call.enqueue(new Callback<BookingHistoryResponse>() {
            @Override
            public void onResponse(Call<BookingHistoryResponse> call, Response<BookingHistoryResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    adapter.updateData(response.body().getData());
                } else {
                    Toast.makeText(BookingHistoryActivity.this, "Lỗi khi tải lịch sử", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<BookingHistoryResponse> call, Throwable t) {
                Toast.makeText(BookingHistoryActivity.this, "Lỗi kết nối mạng", Toast.LENGTH_SHORT).show();
            }
        });
    }
}