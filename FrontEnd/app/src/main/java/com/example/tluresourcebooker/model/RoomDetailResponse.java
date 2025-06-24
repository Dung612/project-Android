package com.example.tluresourcebooker.model;

import com.google.gson.annotations.SerializedName;

public class RoomDetailResponse {
    @SerializedName("success")
    private boolean success;

    @SerializedName("data")
    private Room data; // API trả về một đối tượng Room duy nhất trong key 'data'

    public boolean isSuccess() { return success; }
    public Room getData() { return data; }
}