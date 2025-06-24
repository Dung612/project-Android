package com.example.tluresourcebooker.model;
import com.google.gson.annotations.SerializedName;

public class UserResponse {
    @SerializedName("data")
    private User data;

    public User getData() {
        return data;
    }

    public void setData(User data) {
        this.data = data;
    }
}