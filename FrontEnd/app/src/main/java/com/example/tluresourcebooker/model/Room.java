package com.example.tluresourcebooker.model;
import com.google.gson.annotations.SerializedName;
import java.util.List;
public class Room {
    @SerializedName("id") private int id;
    @SerializedName("name") private String name;
    @SerializedName("capacity") private Integer capacity;
    @SerializedName("location") private String location;
    @SerializedName("status") private boolean status;
    @SerializedName("images") private List<String> images;
    @SerializedName("room_type") private RoomType roomType;
    @SerializedName("devices") private List<Device> devices;

    // === THÊM TRƯỜNG DESCRIPTION ===
    @SerializedName("description")
    private String description;

    // Getters
    public int getId() { return id; }
    public String getName() { return name; }
    public Integer getCapacity() { return capacity; }
    public String getLocation() { return location; }
    public boolean getStatus() { return status; }
    public List<String> getImages() { return images; }
    public RoomType getRoomType() { return roomType; }
    public List<Device> getDevices() { return devices; }

    // === THÊM GETTER CHO DESCRIPTION ===
    public String getDescription() { return description; }
}
