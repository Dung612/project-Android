package com.example.tluresourcebooker.model;

import com.google.gson.annotations.SerializedName;

public class Notification {
    @SerializedName("id")
    private int id;

    @SerializedName("user_id")
    private int userId;

    @SerializedName("message")
    private String message;

    @SerializedName("is_read")
    private int isRead; // 0 hoặc 1

    @SerializedName("created_at")
    private String createdAt;

    // --- Getters and Setters ---
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public int getUserId() { return userId; }
    public void setUserId(int userId) { this.userId = userId; }
    public String getMessage() { return message; }
    public void setMessage(String message) { this.message = message; }
    public boolean isRead() { return isRead == 1; }
    public void setIsRead(int isRead) { this.isRead = isRead; }
    public String getCreatedAt() { return createdAt; }
    public void setCreatedAt(String createdAt) { this.createdAt = createdAt; }
}
