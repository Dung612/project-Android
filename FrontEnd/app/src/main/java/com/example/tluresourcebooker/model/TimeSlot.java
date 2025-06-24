package com.example.tluresourcebooker.model;

import com.google.gson.annotations.SerializedName;

public class TimeSlot {
    @SerializedName("period")
    private int period;

    @SerializedName("start_time")
    private String startTime;

    @SerializedName("end_time")
    private String endTime;

    @SerializedName("status")
    private String status; // "available" hoặc "booked"

    public int getPeriod() { return period; }
    public String getStartTime() { return startTime; }
    public String getEndTime() { return endTime; }
    public String getStatus() { return status; }
}
