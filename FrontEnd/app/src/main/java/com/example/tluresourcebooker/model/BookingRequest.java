package com.example.tluresourcebooker.model;
import com.google.gson.annotations.SerializedName;
import java.util.List;
public class BookingRequest {
    // Lưu ý: user_id sẽ được lấy từ token ở phía server, không cần gửi từ client
    @SerializedName("room_id")
    private int roomId;
    @SerializedName("start_time")
    private String startTime;
    @SerializedName("end_time")
    private String endTime;
    @SerializedName("purpose")
    private String purpose;
    @SerializedName("note")
    private String note;
    @SerializedName("devices")
    private List<BookingDeviceItem> devices; // Danh sách các thiết bị được yêu cầu

    public BookingRequest(int roomId, String startTime, String endTime, String purpose, String note, List<BookingDeviceItem> devices) {
        this.roomId = roomId;
        this.startTime = startTime;
        this.endTime = endTime;
        this.purpose = purpose;
        this.note = note;
        this.devices = devices;
    }
}