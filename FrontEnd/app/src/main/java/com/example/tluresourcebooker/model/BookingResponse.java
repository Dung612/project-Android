package com.example.tluresourcebooker.model;
import com.google.gson.annotations.SerializedName;
public class BookingResponse {
    @SerializedName("success")
    private boolean success;
    @SerializedName("data")
    private Booking data; // Sử dụng lớp Booking đã có
    @SerializedName("message")
    private String message;
    public boolean isSuccess() { return success; }
    public Booking getData() { return data; }
    public String getMessage() { return message; }
}