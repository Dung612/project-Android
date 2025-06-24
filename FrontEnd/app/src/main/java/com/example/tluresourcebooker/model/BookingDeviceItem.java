package com.example.tluresourcebooker.model;
import com.google.gson.annotations.SerializedName;
public class BookingDeviceItem {
    @SerializedName("device_id")
    private int deviceId;

    @SerializedName("quantity")
    private int quantity = 1; // Mặc định số lượng là 1

    public BookingDeviceItem(int deviceId) {
        this.deviceId = deviceId;
    }
}