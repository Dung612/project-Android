package com.example.tluresourcebooker.model;

import com.google.gson.annotations.SerializedName;

public class BookingDevice {
    @SerializedName("id")
    private int id;

    @SerializedName("booking_id")
    private int bookingId;

    @SerializedName("device_id")
    private int deviceId;

    @SerializedName("quantity")
    private int quantity;

    @SerializedName("note")
    private String note;

    // --- Getters and Setters ---
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public int getBookingId() { return bookingId; }
    public void setBookingId(int bookingId) { this.bookingId = bookingId; }
    public int getDeviceId() { return deviceId; }
    public void setDeviceId(int deviceId) { this.deviceId = deviceId; }
    public int getQuantity() { return quantity; }
    public void setQuantity(int quantity) { this.quantity = quantity; }
    public String getNote() { return note; }
    public void setNote(String note) { this.note = note; }
}