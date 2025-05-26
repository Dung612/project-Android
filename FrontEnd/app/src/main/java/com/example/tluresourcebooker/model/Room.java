package com.example.tluresourcebooker.model;

import com.google.gson.annotations.SerializedName;
 import java.util.List;

public class Room {
    @SerializedName("id")
    private int id;

    @SerializedName("name")
    private String name;

    @SerializedName("room_type_id")
    private int roomTypeId;

    @SerializedName("location")
    private String location;

    @SerializedName("capacity")
    private Integer capacity; // Dùng Integer để có thể là null

    @SerializedName("status")
    private int status; // 1 là true, 0 là false

    @SerializedName("description")
    private String description;

    @SerializedName("images")
    private String images; // JSON, có thể cần parse thành List<String> hoặc object riêng

    @SerializedName("open_time")
    private String openTime; // Dạng "HH:mm:ss"

    @SerializedName("close_time")
    private String closeTime; // Dạng "HH:mm:ss"

    @SerializedName("price")
    private Double price; // Dùng Double để có thể là null

    @SerializedName("created_at")
    private String createdAt;

    @SerializedName("updated_at")
    private String updatedAt;

    // --- Getters and Setters ---
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    public int getRoomTypeId() { return roomTypeId; }
    public void setRoomTypeId(int roomTypeId) { this.roomTypeId = roomTypeId; }
    public String getLocation() { return location; }
    public void setLocation(String location) { this.location = location; }
    public Integer getCapacity() { return capacity; }
    public void setCapacity(Integer capacity) { this.capacity = capacity; }
    public boolean getStatus() { return status == 1; }
    public void setStatus(int status) { this.status = status; }
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    public String getImages() { return images; }
    public void setImages(String images) { this.images = images; }
    public String getOpenTime() { return openTime; }
    public void setOpenTime(String openTime) { this.openTime = openTime; }
    public String getCloseTime() { return closeTime; }
    public void setCloseTime(String closeTime) { this.closeTime = closeTime; }
    public Double getPrice() { return price; }
    public void setPrice(Double price) { this.price = price; }
    public String getCreatedAt() { return createdAt; }
    public void setCreatedAt(String createdAt) { this.createdAt = createdAt; }
    public String getUpdatedAt() { return updatedAt; }
    public void setUpdatedAt(String updatedAt) { this.updatedAt = updatedAt; }
}
