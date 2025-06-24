package com.example.tluresourcebooker.filter;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.example.tluresourcebooker.R;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.chip.ChipGroup;

import java.util.ArrayList;

public class DeviceFilterBottomSheet extends BottomSheetDialogFragment {

    public static final String TAG = "DeviceFilterBottomSheet";
    public static final String REQUEST_KEY = "device_filter_request";
    public static final String RESULT_KEY = "selected_device_ids";

    private ChipGroup chipGroupDevices;
    private Button buttonClear, buttonApply;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Inflate layout cho bottom sheet
        return inflater.inflate(R.layout.fragment_filter_device, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // Ánh xạ views từ layout
        chipGroupDevices = view.findViewById(R.id.chipGroupDevices);
        buttonClear = view.findViewById(R.id.buttonClear);
        buttonApply = view.findViewById(R.id.buttonApply);

        // Xử lý sự kiện cho nút "Clear"
        buttonClear.setOnClickListener(v -> {
            chipGroupDevices.clearCheck();
        });

        // Xử lý sự kiện cho nút "Apply"
        buttonApply.setOnClickListener(v -> {
            // Lấy danh sách ID của các chip được chọn
            ArrayList<Integer> selectedChipIds = new ArrayList<>(chipGroupDevices.getCheckedChipIds());

            // Tạo một Bundle để chứa kết quả
            Bundle result = new Bundle();
            result.putIntegerArrayList(RESULT_KEY, selectedChipIds);

            // Gửi kết quả lại cho Activity/Fragment đã gọi nó
            getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);

            // Đóng bottom sheet
            dismiss();
        });
    }

    // Phương thức để tạo một instance mới của BottomSheet
    public static DeviceFilterBottomSheet newInstance() {
        return new DeviceFilterBottomSheet();
    }
}
