package com.example.tluresourcebooker.model;
import com.google.gson.annotations.SerializedName;
import java.util.List;

public class BookingHistoryResponse {
    @SerializedName("success") private boolean success;
    @SerializedName("data") private List<Booking> data;
    // message có thể không cần thiết nếu chỉ lấy dữ liệu
    public boolean isSuccess() { return success; }
    public List<Booking> getData() { return data; }
}