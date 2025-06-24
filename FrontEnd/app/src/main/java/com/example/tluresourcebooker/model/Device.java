package com.example.tluresourcebooker.model;

import com.google.gson.annotations.SerializedName;

public class Device {
    @SerializedName("id")
    private int id;

    @SerializedName("name")
    private String name;

    @SerializedName("device_type_id")
    private int deviceTypeId;

    @SerializedName("status")
    private boolean status; // 0 hoặc 1

    @SerializedName("description")
    private String description;

    @SerializedName("location")
    private String location;

    // --- Getters and Setters ---
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public String getName() { return name; }
    public void setName(String name) { this.name = name; }
    public int getDeviceTypeId() { return deviceTypeId; }
    public void setDeviceTypeId(int deviceTypeId) { this.deviceTypeId = deviceTypeId; }
    public boolean getStatus() { return status; }
    public void setStatus(boolean status) { this.status = status; }
    public String getDescription() { return description; }
    public void setDescription(String description) { this.description = description; }
    public String getLocation() { return location; }
    public void setLocation(String location) { this.location = location; }
}
