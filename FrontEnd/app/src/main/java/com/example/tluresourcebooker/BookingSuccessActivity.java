package com.example.tluresourcebooker;
import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;
import com.example.tluresourcebooker.MainActivity;
import com.example.tluresourcebooker.R;

public class BookingSuccessActivity extends AppCompatActivity {
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_booking_success);

        Button buttonBackToHome = findViewById(R.id.buttonBackToHome);
        TextView textViewSuccessMessage = findViewById(R.id.textViewSuccessMessage);

        // Nhận thông báo từ Intent (nếu có)
        String message = getIntent().getStringExtra("SUCCESS_MESSAGE");
        if (message != null && !message.isEmpty()) {
            textViewSuccessMessage.setText(message);
        }

        buttonBackToHome.setOnClickListener(v -> {
            Intent intent = new Intent(BookingSuccessActivity.this, MainActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(intent);
            finish();
        });
    }
}