package com.example.tluresourcebooker.model;
import com.google.gson.annotations.SerializedName;
import java.util.List;

public class TimeSlotResponse {
    @SerializedName("success")
    private boolean success;

    @SerializedName("data")
    private List<TimeSlot> data;

    public boolean isSuccess() { return success; }
    public List<TimeSlot> getData() { return data; }
}