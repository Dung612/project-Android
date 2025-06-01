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
import com.google.gson.Gson; // Dùng để parse lỗi JSON

import java.io.IOException; // Dùng cho việc đọc errorBody

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginActivity extends AppCompatActivity {

    private static final String TAG = "LoginActivity"; // Tag để logcat

    // 1. Khai báo các thành phần giao diện
    private EditText editTextAccount; // Trong XML của bạn là email/tài khoản
    private EditText editTextPassword;
    private Button buttonLogin;
    private ImageView imageViewLogo;
    private ProgressBar progressBarLogin; // Khai báo ProgressBar

    private Button buttonGoToRegister;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login); // Liên kết với file activity_login.xml

        // 2. Ánh xạ các View từ layout XML
        imageViewLogo = findViewById(R.id.imageViewLogo);
        editTextAccount = findViewById(R.id.editTextAccount);
        editTextPassword = findViewById(R.id.editTextPassword);
        buttonLogin = findViewById(R.id.buttonLogin);
        buttonGoToRegister = findViewById(R.id.buttonRegister);

        // Giả sử bạn đã thêm ProgressBar vào activity_login.xml với id "progressBarLogin"
        // Ví dụ: <ProgressBar android:id="@+id/progressBarLogin" ... />
        // progressBarLogin = findViewById(R.id.progressBarLogin);
        // if (progressBarLogin != null) {
        //     progressBarLogin.setVisibility(View.GONE); // Ẩn ban đầu
        // }


        // 3. Thiết lập sự kiện click cho nút Đăng nhập
        buttonLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Gọi phương thức xử lý đăng nhập
                handleLogin();
            }
        });

        if (buttonGoToRegister != null) {
            buttonGoToRegister.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent intent = new Intent(LoginActivity.this, RegisterActivity.class);
                    startActivity(intent);
                }
            });
        }
    }

    /**
     * Phương thức xử lý logic đăng nhập
     */
    private void handleLogin() {
        // 4. Lấy dữ liệu từ EditText
        String email = editTextAccount.getText().toString().trim();
        String password = editTextPassword.getText().toString().trim();

        // 5. Kiểm tra dữ liệu đầu vào cơ bản
        if (TextUtils.isEmpty(email)) {
            editTextAccount.setError("Vui lòng nhập tài khoản");
            editTextAccount.requestFocus();
            return;
        }

        if (TextUtils.isEmpty(password)) {
            editTextPassword.setError("Vui lòng nhập mật khẩu");
            editTextPassword.requestFocus();
            return;
        }

        // Hiển thị ProgressBar và vô hiệu hóa nút (nếu có)
        // if (progressBarLogin != null) {
        //     progressBarLogin.setVisibility(View.VISIBLE);
        // }
        buttonLogin.setEnabled(false);

        // 6. Tạo đối tượng LoginRequest để gửi đi
        LoginRequest loginRequest = new LoginRequest(email, password);

        // 7. Lấy instance của ApiService thông qua ApiClient
        ApiService apiService = ApiClient.getClient().create(ApiService.class);

        // 8. Thực hiện cuộc gọi API bất đồng bộ
        Call<LoginResponse> call = apiService.loginUser(loginRequest);
        call.enqueue(new Callback<LoginResponse>() {
            @Override
            public void onResponse(Call<LoginResponse> call, Response<LoginResponse> response) {
                // Ẩn ProgressBar và kích hoạt lại nút (nếu có)
                // if (progressBarLogin != null) {
                //     progressBarLogin.setVisibility(View.GONE);
                // }
                buttonLogin.setEnabled(true);

                // 9. Xử lý kết quả trả về từ API
                if (response.isSuccessful() && response.body() != null) {
                    // Đăng nhập thành công
                    LoginResponse loginResponse = response.body();

                    // Hiển thị thông báo thành công (tùy chọn, dựa vào message từ API)
                    // Nếu API của bạn có trường "message" trong response thành công:
                    // if (!TextUtils.isEmpty(loginResponse.getMessage())) {
                    //     Toast.makeText(LoginActivity.this, loginResponse.getMessage(), Toast.LENGTH_LONG).show();
                    // } else {
                    //     Toast.makeText(LoginActivity.this, "Đăng nhập thành công!", Toast.LENGTH_LONG).show();
                    // }

                    Log.d(TAG, "Login successful!");
                    Log.d(TAG, "Access Token: " + loginResponse.getAccessToken());
                    if (loginResponse.getUser() != null) {
                        Log.d(TAG, "User: " + loginResponse.getUser().getFullName());
                        // Bạn có thể truy cập các thông tin khác của user nếu cần
                    }

                    // TODO: Lưu access_token vào SharedPreferences hoặc nơi an toàn khác
                    saveAuthToken(loginResponse.getAccessToken());


                    // Chuyển sang MainActivity
                    Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                    // Bạn có thể truyền dữ liệu người dùng sang MainActivity nếu cần
                    // intent.putExtra("USER_FULL_NAME", loginResponse.getUser().getFullName());
                    startActivity(intent);
                    finish(); // Đóng LoginActivity để người dùng không quay lại bằng nút Back

                } else {
                    // Đăng nhập thất bại (lỗi từ server như 401, 403, 500...)
                    String errorMessage = "Đăng nhập thất bại. Vui lòng thử lại."; // Thông báo mặc định
                    if (response.errorBody() != null) {
                        try {
                            String errorBodyString = response.errorBody().string();
                            Log.e(TAG, "Login Error Body: " + errorBodyString);
                            // Cố gắng parse JSON lỗi từ server
                            // API Laravel của bạn trả về {"message": "Thông tin đăng nhập không chính xác"}
                            Gson gson = new Gson();
                            LoginResponse errorResponse = gson.fromJson(errorBodyString, LoginResponse.class); // Thử dùng LoginResponse để parse
                            if (errorResponse != null && !TextUtils.isEmpty(errorResponse.getMessage())) {
                                errorMessage = errorResponse.getMessage();
                            } else if (response.code() == 401) { // Fallback cho mã lỗi 401
                                errorMessage = "Thông tin đăng nhập không chính xác";
                            } else if (response.code() == 403) { // Fallback cho mã lỗi 403
                                errorMessage = "Tài khoản chưa được xác thực hoặc không có quyền.";
                            }
                        } catch (IOException e) {
                            Log.e(TAG, "Error parsing error body", e);
                        }
                    }
                    Toast.makeText(LoginActivity.this, errorMessage, Toast.LENGTH_LONG).show();
                    Log.e(TAG, "Login failed. Code: " + response.code());
                }
            }

            @Override
            public void onFailure(Call<LoginResponse> call, Throwable t) {
                // Ẩn ProgressBar và kích hoạt lại nút (nếu có)
                // if (progressBarLogin != null) {
                //     progressBarLogin.setVisibility(View.GONE);
                // }
                buttonLogin.setEnabled(true);

                // 10. Xử lý lỗi kết nối mạng hoặc các lỗi khác không thuộc về HTTP response
                Log.e(TAG, "API call failed entirely: " + t.getMessage(), t);
                Toast.makeText(LoginActivity.this, "Lỗi kết nối mạng hoặc server không phản hồi.", Toast.LENGTH_LONG).show();
            }
        });
    }

    /**
     * Phương thức ví dụ để lưu token (bạn nên có cơ chế lưu trữ tốt hơn)
     */
    private void saveAuthToken(String token) {
        SharedPreferences sharedPreferences = getSharedPreferences("AppPrefs_TLUBooker", MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString("AUTH_TOKEN", token);
        editor.apply();
        Log.i(TAG, "Auth token saved.");
    }
}