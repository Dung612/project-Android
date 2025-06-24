package com.example.tluresourcebooker.model;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class RoomListResponse {
    @SerializedName("success")
    private boolean success;

    @SerializedName("data")
    private List<Room> data;

    // --- Getters and Setters ---
    public boolean isSuccess() { return success; }
    public List<Room> getData() { return data; }
}
