package com.example.tluresourcebooker.filter;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.example.tluresourcebooker.R;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;

public class CapacityFilterBottomSheet extends BottomSheetDialogFragment {

    public static final String TAG = "CapacityFilterBottomSheet";
    public static final String REQUEST_KEY = "capacity_filter_request";
    public static final String RESULT_KEY_CAPACITY = "selected_capacity";

    private ChipGroup chipGroupCapacity;
    private MaterialButton buttonClear;
    private MaterialButton buttonApply;

    public static CapacityFilterBottomSheet newInstance() {
        return new CapacityFilterBottomSheet();
    }

    @Nullable
    @Override
    public View onCreateView(
            @NonNull LayoutInflater inflater,
            @Nullable ViewGroup container,
            @Nullable Bundle savedInstanceState
    ) {
        return inflater.inflate(R.layout.fragment_filter_capacity, container, false);
    }

    @Override
    public void onViewCreated(
            @NonNull View view,
            @Nullable Bundle savedInstanceState
    ) {
        super.onViewCreated(view, savedInstanceState);

        // Ánh xạ view
        chipGroupCapacity = view.findViewById(R.id.chipGroupCapacity);
        buttonClear = view.findViewById(R.id.buttonClearCapacity); // Đúng
        buttonApply = view.findViewById(R.id.buttonApplyCapacity); // Đúng


        // Xử lý nút Clear
        buttonClear.setOnClickListener(v -> {
            chipGroupCapacity.clearCheck();

            Bundle result = new Bundle();
            result.putInt(RESULT_KEY_CAPACITY, -1);
            getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);

            dismiss();
        });

        // Xử lý nút Apply
        buttonApply.setOnClickListener(v -> {
            int selectedChipId = chipGroupCapacity.getCheckedChipId();
            int selectedCapacity = -1;

            if (selectedChipId != View.NO_ID) {
                Chip selectedChip = view.findViewById(selectedChipId);
                try {
                    selectedCapacity = Integer.parseInt(selectedChip.getText().toString());
                } catch (NumberFormatException e) {
                    selectedCapacity = -1;
                }
            }

            Bundle result = new Bundle();
            result.putInt(RESULT_KEY_CAPACITY, selectedCapacity);
            getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);

            dismiss();
        });
    }
}
