package com.example.tluresourcebooker;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.tluresourcebooker.model.LoginRequest;
import com.example.tluresourcebooker.model.LoginResponse;
import com.example.tluresourcebooker.Network.ApiClient;
import com.example.tluresourcebooker.Network.ApiService;
import com.google.gson.Gson;

import java.io.IOException;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginActivity extends AppCompatActivity {

    private static final String TAG = "LoginActivity";

    private EditText editTextAccount;
    private EditText editTextPassword;
    private Button buttonLogin;
    private Button buttonGoToRegister;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        // Ánh xạ Views
        editTextAccount = findViewById(R.id.editTextAccount);
        editTextPassword = findViewById(R.id.editTextPassword);
        buttonLogin = findViewById(R.id.buttonLogin);
        buttonGoToRegister = findViewById(R.id.buttonRegister);

        buttonLogin.setOnClickListener(v -> handleLogin());

        buttonGoToRegister.setOnClickListener(v -> {
            Intent intent = new Intent(LoginActivity.this, RegisterActivity.class);
            startActivity(intent);
        });
    }

    private void handleLogin() {
        String email = editTextAccount.getText().toString().trim();
        String password = editTextPassword.getText().toString().trim();

        if (TextUtils.isEmpty(email) || TextUtils.isEmpty(password)) {
            Toast.makeText(this, "Vui lòng nhập đầy đủ thông tin", Toast.LENGTH_SHORT).show();
            return;
        }

        buttonLogin.setEnabled(false);

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        LoginRequest loginRequest = new LoginRequest(email, password);

        Call<LoginResponse> call = apiService.loginUser(loginRequest);
        call.enqueue(new Callback<LoginResponse>() {
            @Override
            public void onResponse(Call<LoginResponse> call, Response<LoginResponse> response) {
                buttonLogin.setEnabled(true);

                if (response.isSuccessful() && response.body() != null) {
                    // Đăng nhập thành công
                    LoginResponse loginResponse = response.body();

                    Log.d(TAG, "Login successful!");
                    Log.d(TAG, "Access Token: " + loginResponse.getAccessToken());

                    // === THAY ĐỔI QUAN TRỌNG: LƯU CẢ TOKEN VÀ USER_ID ===
                    if (loginResponse.getUser() != null) {
                        Log.d(TAG, "User ID: " + loginResponse.getUser().getId());
                        // Gọi phương thức mới để lưu dữ liệu
                        saveLoginData(loginResponse);
                    } else {
                        // Xử lý trường hợp hiếm gặp khi user là null
                        Toast.makeText(LoginActivity.this, "Lỗi: Không nhận được thông tin người dùng.", Toast.LENGTH_LONG).show();
                        return;
                    }

                    // Chuyển sang MainActivity
                    Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                    startActivity(intent);
                    finish(); // Đóng LoginActivity

                } else {
                    // Xử lý lỗi từ server (401, 403, etc.)
                    String errorMessage = "Đăng nhập thất bại.";
                    if (response.errorBody() != null) {
                        try {
                            String errorBodyString = response.errorBody().string();
                            Log.e(TAG, "Login Error Body: " + errorBodyString);
                            Gson gson = new Gson();
                            LoginResponse errorResponse = gson.fromJson(errorBodyString, LoginResponse.class);
                            if (errorResponse != null && !TextUtils.isEmpty(errorResponse.getMessage())) {
                                errorMessage = errorResponse.getMessage();
                            } else if (response.code() == 401) {
                                errorMessage = "Thông tin đăng nhập không chính xác";
                            }
                        } catch (Exception e) {
                            Log.e(TAG, "Error parsing error body", e);
                        }
                    }
                    Toast.makeText(LoginActivity.this, errorMessage, Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<LoginResponse> call, Throwable t) {
                buttonLogin.setEnabled(true);
                Log.e(TAG, "API call failed entirely: " + t.getMessage(), t);
                Toast.makeText(LoginActivity.this, "Lỗi kết nối mạng hoặc server không phản hồi.", Toast.LENGTH_LONG).show();
            }
        });
    }

    /**
     *  lưu cả token và ID người dùng vào SharedPreferences.
     */
    private void saveLoginData(LoginResponse loginResponse) {
        SharedPreferences sharedPreferences = getSharedPreferences("AppPrefs_TLUBooker", MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();

        // Lưu access token
        editor.putString("AUTH_TOKEN", loginResponse.getAccessToken());

        // Lưu ID của người dùng
        if (loginResponse.getUser() != null) {
            editor.putInt("USER_ID", loginResponse.getUser().getId());
        }

        editor.apply();
        Log.i(TAG, "Auth token and User ID saved.");
    }
}