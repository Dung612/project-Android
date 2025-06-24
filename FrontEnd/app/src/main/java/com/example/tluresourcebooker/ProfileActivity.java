package com.example.tluresourcebooker;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

import com.example.tluresourcebooker.LoginActivity;
import com.example.tluresourcebooker.R;
import com.example.tluresourcebooker.model.User;
import com.example.tluresourcebooker.model.UserResponse;
import com.example.tluresourcebooker.Network.ApiClient;
import com.example.tluresourcebooker.Network.ApiService;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ProfileActivity extends AppCompatActivity {
    private TextView textViewFullName, textViewEmail;
    private View optionEditProfile, optionChangePassword, optionLogout;
    private BottomNavigationView bottomNavigationView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile);

        // Ánh xạ views
        textViewFullName = findViewById(R.id.textViewFullName);
        textViewEmail = findViewById(R.id.textViewEmail);
        optionEditProfile = findViewById(R.id.option_edit_profile);
        optionChangePassword = findViewById(R.id.option_change_password);
        optionLogout = findViewById(R.id.option_logout);
        // === SỬA Ở ĐÂY: Ánh xạ vào biến thành viên ===
        bottomNavigationView = findViewById(R.id.bottom_navigation_profile);

        // Thiết lập nội dung cho các mục lựa chọn
        // TODO: Bạn cần thêm các icon ic_edit, ic_lock, ic_logout vào drawable
        setupOption(optionEditProfile, R.drawable.ic_edit, "Sửa thông tin cá nhân", "Cập nhật thông tin của bạn");
        setupOption(optionChangePassword, R.drawable.ic_lock, "Đổi mật khẩu", "Thay đổi mật khẩu hiện tại");
        setupOption(optionLogout, R.drawable.ic_logout, "Đăng xuất", "Thoát khỏi tài khoản");

        // Tải thông tin người dùng
        fetchUserProfile();

        // Xử lý sự kiện click
        optionLogout.setOnClickListener(v -> handleLogout());

        // Gọi hàm thiết lập điều hướng
        setupBottomNavigation();
    }

    private void setupBottomNavigation() {
        if (bottomNavigationView == null) return; // Thêm kiểm tra null để an toàn

        bottomNavigationView.setSelectedItemId(R.id.nav_user);
        bottomNavigationView.setOnItemSelectedListener(item -> {
            int itemId = item.getItemId();
            if (itemId == R.id.nav_home) {
                startActivity(new Intent(getApplicationContext(), MainActivity.class));
                overridePendingTransition(0, 0);
                finish();
                return true;
            } else if (itemId == R.id.nav_schedule) {
                startActivity(new Intent(getApplicationContext(), BookingCalendarActivity.class));
                overridePendingTransition(0, 0);
                finish();
                return true;
            } else if (itemId == R.id.nav_history) {
                startActivity(new Intent(getApplicationContext(), BookingHistoryActivity.class));
                overridePendingTransition(0, 0);
                finish();
                return true;
            } else if (itemId == R.id.nav_user) {
                return true;
            }
            return false;
        });
    }
    private void setupOption(View optionView, int iconResId, String title, String subtitle) {
        ImageView icon = optionView.findViewById(R.id.option_icon);
        TextView titleView = optionView.findViewById(R.id.option_title);
        TextView subtitleView = optionView.findViewById(R.id.option_subtitle);

        icon.setImageResource(iconResId);
        titleView.setText(title);
        subtitleView.setText(subtitle);
    }

    private void fetchUserProfile() {
        SharedPreferences prefs = getSharedPreferences("AppPrefs_TLUBooker", MODE_PRIVATE);
        String token = "Bearer " + prefs.getString("AUTH_TOKEN", "");
        if (token.equals("Bearer ")) {
            // Xử lý khi không có token
            return;
        }

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        Call<UserResponse> call = apiService.getUserProfile(token);

        call.enqueue(new Callback<UserResponse>() {
            @Override
            public void onResponse(Call<UserResponse> call, Response<UserResponse> response) {
                // Sửa lại điều kiện kiểm tra
                if(response.isSuccessful() && response.body() != null) {
                    User user = response.body().getData();
                    if (user != null) {
                        textViewFullName.setText(user.getFullName());
                        textViewEmail.setText(user.getEmail());
                    } else {
                        Toast.makeText(ProfileActivity.this, "Không nhận được dữ liệu người dùng", Toast.LENGTH_SHORT).show();
                    }
                } else {
                    Toast.makeText(ProfileActivity.this, "Không thể tải thông tin người dùng", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<UserResponse> call, Throwable t) {
                Toast.makeText(ProfileActivity.this, "Lỗi kết nối mạng", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void handleLogout() {
        // TODO: Gọi API logout

        // Xóa thông tin đã lưu
        SharedPreferences prefs = getSharedPreferences("AppPrefs_TLUBooker", MODE_PRIVATE);
        SharedPreferences.Editor editor = prefs.edit();
        editor.remove("AUTH_TOKEN");
        editor.remove("USER_ID");
        editor.apply();

        // Chuyển về màn hình đăng nhập
        Intent intent = new Intent(ProfileActivity.this, LoginActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(intent);
        finish();
    }
}