package com.example.tluresourcebooker;

import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.tluresourcebooker.model.RegisterRequest;
import com.example.tluresourcebooker.model.MessageResponse;
import com.example.tluresourcebooker.Network.ApiClient;
import com.example.tluresourcebooker.Network.ApiService;
import com.google.gson.Gson;

import java.io.IOException;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RegisterActivity extends AppCompatActivity {

    private static final String TAG = "RegisterActivity";

    private EditText editTextFullName, editTextEmailRegister, editTextPasswordRegister, editTextConfirmPassword;
    private Button buttonPerformRegister, buttonGoToLogin;
    private ProgressBar progressBarRegister;
    private ImageView imageViewLogoRegister;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);

        imageViewLogoRegister = findViewById(R.id.imageViewLogoRegister);
        editTextFullName = findViewById(R.id.editTextFullName);
        editTextEmailRegister = findViewById(R.id.editTextEmailRegister);
        editTextPasswordRegister = findViewById(R.id.editTextPasswordRegister);
        editTextConfirmPassword = findViewById(R.id.editTextConfirmPassword);
        buttonPerformRegister = findViewById(R.id.buttonPerformRegister);
        buttonGoToLogin = findViewById(R.id.buttonGoToLogin);

        // progressBarRegister = findViewById(R.id.progressBarRegister);
        // if(progressBarRegister != null) {
        //     progressBarRegister.setVisibility(View.GONE);
        // }

        buttonPerformRegister.setOnClickListener(v -> handleRegistration()); // Sử dụng lambda cho gọn

        buttonGoToLogin.setOnClickListener(v -> {
            Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(intent);
            finish();
        });
    }

    private void handleRegistration() {
        String fullName = editTextFullName.getText().toString().trim();
        String email = editTextEmailRegister.getText().toString().trim();
        String password = editTextPasswordRegister.getText().toString().trim();
        String confirmPassword = editTextConfirmPassword.getText().toString().trim();

        if (TextUtils.isEmpty(fullName)) {
            editTextFullName.setError("Vui lòng nhập họ và tên");
            editTextFullName.requestFocus();
            return;
        }
        if (TextUtils.isEmpty(email)) {
            editTextEmailRegister.setError("Vui lòng nhập email");
            editTextEmailRegister.requestFocus();
            return;
        }
        if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            editTextEmailRegister.setError("Vui lòng nhập email hợp lệ");
            editTextEmailRegister.requestFocus();
            return;
        }
        if (TextUtils.isEmpty(password)) {
            editTextPasswordRegister.setError("Vui lòng nhập mật khẩu");
            editTextPasswordRegister.requestFocus();
            return;
        }
        if (password.length() < 8) { // Laravel RegisterRequest yêu cầu min:8
            editTextPasswordRegister.setError("Mật khẩu phải có ít nhất 8 ký tự");
            editTextPasswordRegister.requestFocus();
            return;
        }
        if (TextUtils.isEmpty(confirmPassword)) {
            editTextConfirmPassword.setError("Vui lòng nhập lại mật khẩu");
            editTextConfirmPassword.requestFocus();
            return;
        }
        if (!password.equals(confirmPassword)) {
            editTextConfirmPassword.setError("Mật khẩu nhập lại không khớp");
            editTextConfirmPassword.requestFocus();
            return;
        }

        // if(progressBarRegister != null) progressBarRegister.setVisibility(View.VISIBLE);
        buttonPerformRegister.setEnabled(false);
        buttonGoToLogin.setEnabled(false);

        // Tạo đối tượng RegisterRequest
        // Giả sử RegisterRequest.java của bạn có constructor nhận passwordConfirmation
        // Hoặc bạn đã thêm trường passwordConfirmation và setter cho nó.
        // Laravel yêu cầu trường "password_confirmation" cho rule "confirmed"
        RegisterRequest registerRequest = new RegisterRequest(fullName, email, password, password); // Truyền password làm password_confirmation

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        Call<MessageResponse> call = apiService.registerUser(registerRequest); // <<=== SỬ DỤNG MessageResponse

        call.enqueue(new Callback<MessageResponse>() {
            @Override
            public void onResponse(Call<MessageResponse> call, Response<MessageResponse> response) {
                // if(progressBarRegister != null) progressBarRegister.setVisibility(View.GONE);
                buttonPerformRegister.setEnabled(true);
                buttonGoToLogin.setEnabled(true);

                if (response.isSuccessful() && response.body() != null) {
                    MessageResponse simpleResponse = response.body();
                    Log.d(TAG, "Registration successful: " + simpleResponse.getMessage());
                    Toast.makeText(RegisterActivity.this, simpleResponse.getMessage(), Toast.LENGTH_LONG).show();

                    // Chuyển về màn hình đăng nhập sau khi đăng ký thành công
                    Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                    startActivity(intent);
                    finish();

                } else {
                    String errorMessage = "Đăng ký thất bại.";
                    if (response.errorBody() != null) {
                        try {
                            String errorBodyString = response.errorBody().string();
                            Log.e(TAG, "Registration Error Body: " + errorBodyString);
                            Gson gson = new Gson();
                            // Thử parse bằng MessageResponse nếu lỗi server cũng trả về dạng {"message": "..."}
                            // Hoặc một lớp ErrorResponse riêng nếu cấu trúc lỗi phức tạp hơn (ví dụ: có 'errors' object)
                            MessageResponse errorResponse = gson.fromJson(errorBodyString, MessageResponse.class);
                            if (errorResponse != null && !TextUtils.isEmpty(errorResponse.getMessage())) {
                                errorMessage = errorResponse.getMessage();
                            } else {
                                // Nếu API trả về lỗi validation từ Laravel (thường là 422)
                                // bạn có thể cần parse một cấu trúc lỗi chi tiết hơn
                                // ví dụ: {"message": "The given data was invalid.", "errors": {"email": ["Email đã tồn tại."] }}
                                // Tạm thời hiển thị mã lỗi.
                                errorMessage = "Lỗi " + response.code() + ". Vui lòng thử lại.";
                                if (response.code() == 422) {
                                    // TODO: Parse lỗi validation chi tiết từ errorBodyString
                                    // Ví dụ, bạn có thể tạo một lớp `ValidationErrorsResponse`
                                    // để parse object `errors`.
                                    // Tạm thời hiển thị thông báo chung cho lỗi 422.
                                    errorMessage = "Dữ liệu không hợp lệ. Vui lòng kiểm tra lại thông tin đã nhập.";
                                }
                            }
                        } catch (IOException e) {
                            Log.e(TAG, "Error parsing error body for registration", e);
                        }
                    } else if (response.code() == 422) {
                        errorMessage = "Dữ liệu không hợp lệ. Vui lòng kiểm tra lại thông tin đã nhập.";
                    } else {
                        errorMessage = "Lỗi không xác định từ server (Code: " + response.code() + ")";
                    }
                    Toast.makeText(RegisterActivity.this, errorMessage, Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<MessageResponse> call, Throwable t) {
                // if(progressBarRegister != null) progressBarRegister.setVisibility(View.GONE);
                buttonPerformRegister.setEnabled(true);
                buttonGoToLogin.setEnabled(true);

                Log.e(TAG, "Registration API call failed entirely: " + t.getMessage(), t);
                Toast.makeText(RegisterActivity.this, "Lỗi kết nối mạng. Vui lòng thử lại.", Toast.LENGTH_LONG).show();
            }
        });
    }
}