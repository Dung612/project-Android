package com.example.tluresourcebooker.Network;

import com.example.tluresourcebooker.model.LoginRequest;
import com.example.tluresourcebooker.model.LoginResponse;
import com.example.tluresourcebooker.model.MessageResponse;
import com.example.tluresourcebooker.model.RegisterRequest;
// Import các model khác nếu bạn có các API khác
// import com.example.tluresourcebooker.model.Room;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.Headers;
import retrofit2.http.POST;
// import retrofit2.http.Path; // Ví dụ cho API khác

public interface ApiService {
    @Headers("Accept: application/json")
    @POST("api/login") // Endpoint đăng nhập của bạn
    Call<LoginResponse> loginUser(@Body LoginRequest loginRequest);

    @POST("api/register") // Endpoint đăng ký của bạn
    Call<MessageResponse> registerUser(@Body RegisterRequest registerRequest);

    // --- Ví dụ cho các API khác bạn có thể thêm sau này ---
    // @GET("api/user")
    // Call<User> getCurrentUser(); // Yêu cầu token trong Header (sẽ cấu hình trong ApiClient)

    // @GET("api/rooms")
    // Call<List<Room>> getAllRooms();

    // @GET("api/rooms/{id}")
    // Call<Room> getRoomDetails(@Path("id") int roomId);
}