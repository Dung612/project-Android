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

import java.util.ArrayList;

public class DeviceFilterBottomSheet extends BottomSheetDialogFragment {

    public static final String TAG = "DeviceFilterBottomSheet";
    public static final String REQUEST_KEY = "device_filter_request";
    public static final String RESULT_KEY = "selected_device_ids";

    private ChipGroup chipGroupDevices;
    private MaterialButton buttonClear;
    private MaterialButton buttonApply;

    public static DeviceFilterBottomSheet newInstance() {
        return new DeviceFilterBottomSheet();
    }

    @Nullable
    @Override
    public View onCreateView(
            @NonNull LayoutInflater inflater,
            @Nullable ViewGroup container,
            @Nullable Bundle savedInstanceState
    ) {
        return inflater.inflate(R.layout.fragment_filter_device, container, false);
    }

    @Override
    public void onViewCreated(
            @NonNull View view,
            @Nullable Bundle savedInstanceState
    ) {
        super.onViewCreated(view, savedInstanceState);

        chipGroupDevices = view.findViewById(R.id.chipGroupDevices);
        buttonClear = view.findViewById(R.id.buttonClear);
        buttonApply = view.findViewById(R.id.buttonApply);

        Chip chipComputer = view.findViewById(R.id.chipComputer);
        Chip chipAirConditioner = view.findViewById(R.id.chipAirConditioner);
        Chip chipProjector = view.findViewById(R.id.chipProjector);
        Chip chipSpeaker = view.findViewById(R.id.chipSpeaker);
        Chip chipMicrophone = view.findViewById(R.id.chipMicrophone);

// Gán ID thật của thiết bị
        chipComputer.setTag(1);         // ví dụ id thiết bị máy tính = 1
        chipAirConditioner.setTag(2);   // id điều hòa = 2
        chipProjector.setTag(3);        // id máy chiếu = 3
        chipSpeaker.setTag(4);          // id loa = 4
        chipMicrophone.setTag(5);

        // Nút Clear
        buttonClear.setOnClickListener(v -> chipGroupDevices.clearCheck());

        // Nút Apply
        buttonApply.setOnClickListener(v -> {
            ArrayList<Integer> selectedDeviceIds = new ArrayList<>();
            for (Integer chipId : chipGroupDevices.getCheckedChipIds()) {
                Chip chip = view.findViewById(chipId);
                Object tag = chip.getTag();
                if (tag instanceof Integer) {
                    selectedDeviceIds.add((Integer) tag);
                }
            }

            Bundle result = new Bundle();
            result.putIntegerArrayList(RESULT_KEY, selectedDeviceIds);
            getParentFragmentManager().setFragmentResult(REQUEST_KEY, result);

            dismiss();
        });



    }
}
