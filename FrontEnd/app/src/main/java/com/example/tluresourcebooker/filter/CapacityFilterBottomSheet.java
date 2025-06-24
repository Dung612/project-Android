package com.example.tluresourcebooker.filter;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.example.tluresourcebooker.R;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.button.MaterialButton; // Sử dụng MaterialButton
import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;

public class CapacityFilterBottomSheet extends BottomSheetDialogFragment {

    public static final String TAG = "CapacityFilterBottomSheet";
    public static final String REQUEST_KEY = "capacity_filter_request";
    public static final String RESULT_KEY_CAPACITY = "selected_capacity";

    // ===== BẠN CẦN KHAI BÁO CÁC BIẾN NÀY Ở ĐÂY =====
    private ChipGroup chipGroupCapacity;
    private MaterialButton buttonClearCapacity, buttonApplyCapacity;
    // ===================================================

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_filter_capacity, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // Ánh xạ các view với các biến đã khai báo ở trên
        chipGroupCapacity = view.findViewById(R.id.chipGroupCapacity);
        buttonClearCapacity = view.findViewById(R.id.buttonClearCapacity);
        buttonApplyCapacity = view.findViewById(R.id.buttonApplyCapacity);

        // Sự kiện nút Clear
        buttonClearCapacity.setOnClickListener(v -> {
            chipGroupCapacity.clearCheck();
            // Gửi về giá trị đặc biệt để báo hiệu clear
            Bundle result = new Bundle();
            result.putInt(RESULT_KEY_CAPACITY, -1);
            getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);
            dismiss();
        });

        // Sự kiện nút Apply
        buttonApplyCapacity.setOnClickListener(v -> {
            int selectedChipId = chipGroupCapacity.getCheckedChipId();
            int selectedCapacity = -1; // Giá trị mặc định nếu không có chip nào được chọn

            if (selectedChipId != View.NO_ID) {
                Chip selectedChip = view.findViewById(selectedChipId);
                try {
                    selectedCapacity = Integer.parseInt(selectedChip.getText().toString());
                } catch (NumberFormatException e) {
                    // Xử lý nếu text của chip không phải là số
                    selectedCapacity = -1;
                }
            }

            // Tạo Bundle để gửi kết quả về
            Bundle result = new Bundle();
            result.putInt(RESULT_KEY_CAPACITY, selectedCapacity);
            getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);
            dismiss();
        });
    }

    public static CapacityFilterBottomSheet newInstance() {
        return new CapacityFilterBottomSheet();
    }
}