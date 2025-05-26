package com.example.tluresourcebooker.model;

import com.google.gson.annotations.SerializedName;

public class Booking {
    @SerializedName("id")
    private int id;

    @SerializedName("user_id")
    private int userId;

    @SerializedName("room_id")
    private int roomId;

    @SerializedName("start_time")
    private String startTime; // Dạng "YYYY-MM-DD HH:mm:ss"

    @SerializedName("end_time")
    private String endTime; // Dạng "YYYY-MM-DD HH:mm:ss"

    @SerializedName("purpose")
    private String purpose;

    @SerializedName("note")
    private String note;

    @SerializedName("status")
    private String status; // 'pending', 'approved', 'rejected', 'cancelled'

    @SerializedName("approved_by")
    private Integer approvedBy; // Có thể null

    @SerializedName("rejection_reason")
    private String rejectionReason;

    @SerializedName("created_at")
    private String createdAt;

    @SerializedName("updated_at")
    private String updatedAt;

    // --- Getters and Setters ---
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public int getUserId() { return userId; }
    public void setUserId(int userId) { this.userId = userId; }
    public int getRoomId() { return roomId; }
    public void setRoomId(int roomId) { this.roomId = roomId; }
    public String getStartTime() { return startTime; }
    public void setStartTime(String startTime) { this.startTime = startTime; }
    public String getEndTime() { return endTime; }
    public void setEndTime(String endTime) { this.endTime = endTime; }
    public String getPurpose() { return purpose; }
    public void setPurpose(String purpose) { this.purpose = purpose; }
    public String getNote() { return note; }
    public void setNote(String note) { this.note = note; }
    public String getStatus() { return status; }
    public void setStatus(String status) { this.status = status; }
    public Integer getApprovedBy() { return approvedBy; }
    public void setApprovedBy(Integer approvedBy) { this.approvedBy = approvedBy; }
    public String getRejectionReason() { return rejectionReason; }
    public void setRejectionReason(String rejectionReason) { this.rejectionReason = rejectionReason; }
    public String getCreatedAt() { return createdAt; }
    public void setCreatedAt(String createdAt) { this.createdAt = createdAt; }
    public String getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(String updatedAt) { this.updatedAt = updatedAt; }
}
