package com.example.tluresourcebooker;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.inputmethod.EditorInfo;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.RecyclerView;
import java.util.Calendar;
import java.text.SimpleDateFormat;
import java.util.Locale;
import com.example.tluresourcebooker.Network.ApiClient;
import com.example.tluresourcebooker.Network.ApiService;
import com.example.tluresourcebooker.adapter.MainListAdapter;
import com.example.tluresourcebooker.filter.CapacityFilterBottomSheet;
import com.example.tluresourcebooker.filter.DeviceFilterBottomSheet;
import com.example.tluresourcebooker.filter.TimeRangeFilterBottomSheet;
import com.example.tluresourcebooker.model.Room;
import com.example.tluresourcebooker.model.RoomListResponse;
import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.android.material.button.MaterialButton;

import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends AppCompatActivity {

    private static final String TAG = "MainActivity";

    private RecyclerView mainRecyclerView;
    private MainListAdapter mainListAdapter;
    private final List<Object> displayList = new ArrayList<>();

    private BottomNavigationView bottomNavigationView;
    private MaterialButton buttonFilterDevice, buttonFilterTime, buttonFilterCapacity;
    private EditText editTextSearch;
    private ImageButton buttonSearch;

    private String currentStartTime = null;
    private String currentEndTime = null;
    private Integer currentMinCapacity = null;
    private List<Integer> currentDeviceIds = new ArrayList<>();
    private String currentSearchTerm = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        initViews();

        mainListAdapter = new MainListAdapter(displayList, null);
        mainRecyclerView.setAdapter(mainListAdapter);

        setupListeners();

        // Tải danh sách phòng ban đầu (không filter)
        setDefaultTimeRange();
        fetchRooms();
    }

    private void initViews() {
        mainRecyclerView = findViewById(R.id.mainRecyclerView);
        bottomNavigationView = findViewById(R.id.bottom_navigation);
        buttonFilterDevice = findViewById(R.id.buttonFilterDevice);
        buttonFilterTime = findViewById(R.id.buttonFilterTime);
        buttonFilterCapacity = findViewById(R.id.buttonFilterCapacity);
        editTextSearch = findViewById(R.id.editTextSearch);
        buttonSearch = findViewById(R.id.buttonSearch);
    }

    private void setupListeners() {
        setupBottomNavigation();
        setupFilterButtons();
        setupFilterResultListeners();
        setupSearch();
    }

    private void setupBottomNavigation() {
        bottomNavigationView.setSelectedItemId(R.id.nav_home);
        bottomNavigationView.setOnItemSelectedListener(item -> {
            int id = item.getItemId();
            if (id == R.id.nav_home) {
                return true;
            } else if (id == R.id.nav_schedule) {
                startActivity(new Intent(this, BookingCalendarActivity.class));
                return true;
            } else if (id == R.id.nav_history) {
                startActivity(new Intent(this, BookingHistoryActivity.class));
                return true;
            } else if (id == R.id.nav_user) {
                startActivity(new Intent(this, ProfileActivity.class));
                return true;
            }
            return false;
        });
    }

    private void setupFilterButtons() {
        buttonFilterDevice.setOnClickListener(v ->
                DeviceFilterBottomSheet.newInstance().show(getSupportFragmentManager(), DeviceFilterBottomSheet.TAG));
        buttonFilterTime.setOnClickListener(v ->
                TimeRangeFilterBottomSheet.newInstance().show(getSupportFragmentManager(), TimeRangeFilterBottomSheet.TAG));
        buttonFilterCapacity.setOnClickListener(v ->
                CapacityFilterBottomSheet.newInstance().show(getSupportFragmentManager(), CapacityFilterBottomSheet.TAG));
    }

    private void setupFilterResultListeners() {
        // Thời gian
        getSupportFragmentManager().setFragmentResultListener(
                TimeRangeFilterBottomSheet.REQUEST_KEY, this, (requestKey, bundle) -> {
                    currentStartTime = bundle.getString(TimeRangeFilterBottomSheet.RESULT_START_TIME);
                    currentEndTime = bundle.getString(TimeRangeFilterBottomSheet.RESULT_END_TIME);
                    fetchRooms();
                }
        );


        // Sức chứa
        getSupportFragmentManager().setFragmentResultListener(
                CapacityFilterBottomSheet.REQUEST_KEY, this, (requestKey, bundle) -> {
                    int capacity = bundle.getInt(CapacityFilterBottomSheet.RESULT_KEY_CAPACITY, -1);
                    currentMinCapacity = (capacity != -1) ? capacity : null;
                    fetchRooms();
                }
        );

        // Thiết bị
        getSupportFragmentManager().setFragmentResultListener(
                DeviceFilterBottomSheet.REQUEST_KEY, this, (requestKey, bundle) -> {
                    currentDeviceIds = bundle.getIntegerArrayList(DeviceFilterBottomSheet.RESULT_KEY);
                    if (currentDeviceIds == null) currentDeviceIds = new ArrayList<>();
                    fetchRooms();
                }
        );
    }
    private void setDefaultTimeRange() {
        Calendar now = Calendar.getInstance();
        // Start time = thời điểm hiện tại
        currentStartTime = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault()).format(now.getTime());

        // End time = +1 tiếng so với hiện tại
        now.add(Calendar.HOUR_OF_DAY, 1);
        currentEndTime = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault()).format(now.getTime());

        Log.d(TAG, "Default time range set: start=" + currentStartTime + ", end=" + currentEndTime);
    }

    private void setupSearch() {
        buttonSearch.setOnClickListener(v -> {
            currentSearchTerm = editTextSearch.getText().toString().trim();
            fetchRooms();
        });

        editTextSearch.setOnEditorActionListener((v, actionId, event) -> {
            if (actionId == EditorInfo.IME_ACTION_SEARCH) {
                currentSearchTerm = editTextSearch.getText().toString().trim();
                fetchRooms();
                return true;
            }
            return false;
        });
    }

    private void fetchRooms() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        Call<RoomListResponse> call = apiService.searchRooms(
                currentStartTime,
                currentEndTime,
                currentMinCapacity,
                currentDeviceIds
        );

        Log.d(TAG, "Fetching rooms with params: start=" + currentStartTime
                + ", end=" + currentEndTime
                + ", capacity=" + currentMinCapacity
                + ", devices=" + currentDeviceIds);

        call.enqueue(new Callback<RoomListResponse>() {
            @Override
            public void onResponse(Call<RoomListResponse> call, Response<RoomListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    processAndDisplayRooms(response.body().getData());
                } else {
                    Toast.makeText(MainActivity.this, "Lỗi tải danh sách phòng", Toast.LENGTH_SHORT).show();
                    Log.e(TAG, "Error: " + response.code() + ", " + response.message());
                }
            }

            @Override
            public void onFailure(Call<RoomListResponse> call, Throwable t) {
                Toast.makeText(MainActivity.this, "Lỗi mạng: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }


    private void processAndDisplayRooms(List<Room> rooms) {
        displayList.clear();
        if (rooms == null || rooms.isEmpty()) {
            Toast.makeText(this, "Không tìm thấy phòng phù hợp.", Toast.LENGTH_SHORT).show();
        } else {
            Map<String, List<Room>> groupedRooms = new LinkedHashMap<>();
            for (Room room : rooms) {
                if (room.getRoomType() != null && room.getRoomType().getName() != null) {
                    groupedRooms.computeIfAbsent(room.getRoomType().getName(), k -> new ArrayList<>()).add(room);
                }
            }
            for (Map.Entry<String, List<Room>> entry : groupedRooms.entrySet()) {
                displayList.add(entry.getKey());
                displayList.add(entry.getValue());
            }
        }
        mainListAdapter.notifyDataSetChanged();
    }
}
