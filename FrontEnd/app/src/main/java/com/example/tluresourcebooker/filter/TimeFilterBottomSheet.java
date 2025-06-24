package com.example.tluresourcebooker.filter;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.CalendarView;
import android.widget.TimePicker;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.example.tluresourcebooker.R;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;

import java.util.Calendar;

public class TimeFilterBottomSheet extends BottomSheetDialogFragment {

    public static final String TAG = "TimeFilterBottomSheet";
    public static final String REQUEST_KEY = "time_filter_request";
    public static final String RESULT_KEY_TIMESTAMP = "selected_timestamp"; // Sẽ gửi về một timestamp (long)

    private CalendarView calendarView;
    private TimePicker timePicker;
    private Button buttonClearTime, buttonApplyTime;

    private Calendar selectedDateTime = Calendar.getInstance(); // Lưu ngày giờ được chọn

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_filter_time, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        calendarView = view.findViewById(R.id.calendarView);
        timePicker = view.findViewById(R.id.timePicker);
        buttonClearTime = view.findViewById(R.id.buttonClearTime);
        buttonApplyTime = view.findViewById(R.id.buttonApplyTime);

        // Đặt định dạng 24h cho TimePicker (tùy chọn)
        timePicker.setIs24HourView(true);

        // Lắng nghe sự kiện thay đổi ngày
        calendarView.setOnDateChangeListener((view1, year, month, dayOfMonth) -> {
            selectedDateTime.set(Calendar.YEAR, year);
            selectedDateTime.set(Calendar.MONTH, month);
            selectedDateTime.set(Calendar.DAY_OF_MONTH, dayOfMonth);
        });

        // Lắng nghe sự kiện thay đổi giờ/phút
        timePicker.setOnTimeChangedListener((view12, hourOfDay, minute) -> {
            selectedDateTime.set(Calendar.HOUR_OF_DAY, hourOfDay);
            selectedDateTime.set(Calendar.MINUTE, minute);
        });

        // Sự kiện nút Clear
        buttonClearTime.setOnClickListener(v -> {
            // TODO: Xử lý logic clear bộ lọc
            // Ví dụ: gửi về một giá trị đặc biệt (-1L) để báo hiệu clear
            Bundle result = new Bundle();
            result.putLong(RESULT_KEY_TIMESTAMP, -1L);
            getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);
            dismiss();
        });

        // Sự kiện nút Apply
        buttonApplyTime.setOnClickListener(v -> {
            // Lấy giờ và phút hiện tại từ TimePicker vì listener có thể không được gọi nếu không thay đổi
            selectedDateTime.set(Calendar.HOUR_OF_DAY, timePicker.getHour());
            selectedDateTime.set(Calendar.MINUTE, timePicker.getMinute());

            // Tạo Bundle để gửi kết quả về
            Bundle result = new Bundle();
            result.putLong(RESULT_KEY_TIMESTAMP, selectedDateTime.getTimeInMillis());
            getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);
            dismiss();
        });
    }

    public static TimeFilterBottomSheet newInstance() {
        return new TimeFilterBottomSheet();
    }
}
