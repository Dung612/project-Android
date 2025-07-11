package com.example.tluresourcebooker.Network;

import com.example.tluresourcebooker.model.BookingHistoryResponse;
import com.example.tluresourcebooker.model.BookingRequest;
import com.example.tluresourcebooker.model.BookingResponse;
import com.example.tluresourcebooker.model.LoginRequest;
import com.example.tluresourcebooker.model.LoginResponse;
import com.example.tluresourcebooker.model.MessageResponse;
import com.example.tluresourcebooker.model.RegisterRequest;
import com.example.tluresourcebooker.model.RoomListResponse;
import com.example.tluresourcebooker.model.RoomDetailResponse;
import com.example.tluresourcebooker.model.TimeSlotResponse;
import com.example.tluresourcebooker.model.UserResponse;
// Import các model khác nếu bạn có các API khác
// import com.example.tluresourcebooker.model.Room;

import java.util.List;

import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.Header;
import retrofit2.http.Headers;
import retrofit2.http.POST;
import retrofit2.http.GET;
import retrofit2.http.Path;
import retrofit2.http.Query;
// import retrofit2.http.Path; // Ví dụ cho API khác

public interface ApiService {
    @Headers("Accept: application/json")
    @POST("api/login") // Endpoint đăng nhập của bạn
    Call<LoginResponse> loginUser(@Body LoginRequest loginRequest);

    @POST("api/register") // Endpoint đăng ký của bạn
    Call<MessageResponse> registerUser(@Body RegisterRequest registerRequest);

    @GET("api/rooms/") // Giả sử endpoint của bạn là /api/rooms
    Call<RoomListResponse> getAllRooms();

    @Headers("Accept: application/json")
    @GET("api/rooms/{id}")
    Call<RoomDetailResponse> getRoomDetails(@Path("id") int roomId);

    @Headers("Accept: application/json")
    @GET("api/rooms/{id}/availability")
    Call<TimeSlotResponse> getRoomAvailability(
            @Path("id") int roomId,
            @Query("date") String date // ví dụ: "2025-06-26"
    );

    @Headers("Accept: application/json")
    @POST("api/bookings")
    Call<BookingResponse> createBooking(
            @Header("Authorization") String authToken,
            @Body BookingRequest bookingRequest
    );

    @Headers("Accept: application/json")
    @GET("api/bookings/history")
    Call<BookingHistoryResponse> getBookingHistory(
            @Header("Authorization") String authToken,
            @Query("status") String status // Thêm query parameter để lọc
    );

    @Headers("Accept: application/json")
    @GET("api/user/profile") // Hoặc endpoint lấy thông tin user của bạn
    Call<UserResponse> getUserProfile(@Header("Authorization") String authToken);

    @Headers("Accept: application/json")
    @GET("api/rooms/search") // Sử dụng endpoint search mới
    Call<RoomListResponse> searchRooms(
            @Query("start_time") String startTime,     // Ví dụ: "2024-05-15 08:00:00"
            @Query("end_time") String endTime,
            @Query("min_capacity") Integer minCapacity,
            @Query("device_ids[]") List<Integer> deviceIds
            // Thêm các tham số khác như room_type_id nếu cần
    );

    @Headers("Accept: application/json")
    @POST("api/logout")
    Call<ResponseBody> logout(@Header("Authorization") String authToken);



}