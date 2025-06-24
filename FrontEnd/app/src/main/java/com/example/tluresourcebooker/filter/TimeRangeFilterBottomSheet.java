package com.example.tluresourcebooker.filter;

import android.app.DatePickerDialog;
import android.app.TimePickerDialog;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.example.tluresourcebooker.R;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.button.MaterialButton;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Locale;

public class TimeRangeFilterBottomSheet extends BottomSheetDialogFragment {

    public static final String TAG = "TimeRangeFilterSheet";
    public static final String REQUEST_KEY = "time_range_request";
    public static final String RESULT_START_TIME = "start_time";
    public static final String RESULT_END_TIME = "end_time";

    private TextView textViewStartTime, textViewEndTime;
    private MaterialButton buttonSelectDate, buttonClear, buttonApply;

    private final Calendar startTime = Calendar.getInstance();
    private final Calendar endTime = Calendar.getInstance();

    private boolean isStartTimeSet = false;
    private boolean isEndTimeSet = false;
    private boolean isDateSet = false;

    public static TimeRangeFilterBottomSheet newInstance() {
        return new TimeRangeFilterBottomSheet();
    }

    @Nullable
    @Override
    public View onCreateView(
            @NonNull LayoutInflater inflater,
            @Nullable ViewGroup container,
            @Nullable Bundle savedInstanceState
    ) {
        return inflater.inflate(R.layout.fragment_filter_time_range, container, false);
    }

    @Override
    public void onViewCreated(
            @NonNull View view,
            @Nullable Bundle savedInstanceState
    ) {
        super.onViewCreated(view, savedInstanceState);

        textViewStartTime = view.findViewById(R.id.textViewStartTime);
        textViewEndTime = view.findViewById(R.id.textViewEndTime);
        buttonSelectDate = view.findViewById(R.id.buttonSelectDate);
        buttonClear = view.findViewById(R.id.buttonClear);
        buttonApply = view.findViewById(R.id.buttonApply);

        setupClickListeners();
    }

    private void setupClickListeners() {
        // Chọn ngày
        buttonSelectDate.setOnClickListener(v -> showDatePicker());
        // Chọn giờ bắt đầu
        textViewStartTime.setOnClickListener(v -> showTimePicker(true));
        // Chọn giờ kết thúc
        textViewEndTime.setOnClickListener(v -> showTimePicker(false));

        // Áp dụng filter
        buttonApply.setOnClickListener(v -> {
            if (isDateSet && isStartTimeSet && isEndTimeSet) {
                // Format thời gian
                SimpleDateFormat sdf = new SimpleDateFormat(
                        "yyyy-MM-dd HH:mm:ss",
                        Locale.getDefault()
                );

                String formattedStart = sdf.format(startTime.getTime());
                String formattedEnd = sdf.format(endTime.getTime());

                Bundle result = new Bundle();
                result.putString(RESULT_START_TIME, formattedStart);
                result.putString(RESULT_END_TIME, formattedEnd);
                getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);

                dismiss();
            } else {
                Toast.makeText(
                        getContext(),
                        "Vui lòng chọn đủ ngày và thời gian bắt đầu/kết thúc!",
                        Toast.LENGTH_SHORT
                ).show();
            }
        });

        // Xóa filter
        buttonClear.setOnClickListener(v -> {
            Bundle result = new Bundle();
            result.putString(RESULT_START_TIME, null);
            result.putString(RESULT_END_TIME, null);
            getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);

            dismiss();
        });
    }

    private void showDatePicker() {
        final Calendar c = Calendar.getInstance();

        DatePickerDialog dialog = new DatePickerDialog(
                requireContext(),
                (datePicker, year, month, dayOfMonth) -> {
                    // Set ngày được chọn
                    startTime.set(year, month, dayOfMonth);
                    endTime.set(year, month, dayOfMonth);
                    isDateSet = true;

                    SimpleDateFormat sdf = new SimpleDateFormat(
                            "dd/MM/yyyy",
                            Locale.getDefault()
                    );

                    buttonSelectDate.setText(sdf.format(startTime.getTime()));
                },
                c.get(Calendar.YEAR),
                c.get(Calendar.MONTH),
                c.get(Calendar.DAY_OF_MONTH)
        );

        dialog.show();
    }

    private void showTimePicker(boolean isStart) {
        if (!isDateSet) {
            Toast.makeText(
                    getContext(),
                    "Vui lòng chọn ngày trước",
                    Toast.LENGTH_SHORT
            ).show();
            return;
        }

        final Calendar c = isStart ? startTime : endTime;

        TimePickerDialog dialog = new TimePickerDialog(
                getContext(),
                (timePicker, hourOfDay, minute) -> {
                    c.set(Calendar.HOUR_OF_DAY, hourOfDay);
                    c.set(Calendar.MINUTE, minute);

                    SimpleDateFormat sdf = new SimpleDateFormat(
                            "HH:mm",
                            Locale.getDefault()
                    );

                    if (isStart) {
                        textViewStartTime.setText("Giờ bắt đầu: " + sdf.format(c.getTime()));
                        isStartTimeSet = true;
                    } else {
                        textViewEndTime.setText("Giờ kết thúc: " + sdf.format(c.getTime()));
                        isEndTimeSet = true;
                    }
                },
                c.get(Calendar.HOUR_OF_DAY),
                c.get(Calendar.MINUTE),
                true
        );

        dialog.show();
    }
}
