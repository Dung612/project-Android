package com.example.tluresourcebooker.Network;

import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import java.util.concurrent.TimeUnit;

public class ApiClient {
    public static final String BASE_URL = "http://127.0.0.1:8000/";

    private static Retrofit retrofit = null; // Biến static để giữ instance duy nhất

    public static Retrofit getClient() {
        // Chỉ khởi tạo Retrofit nếu nó chưa tồn tại (Singleton pattern)
        if (retrofit == null) {
            HttpLoggingInterceptor loggingInterceptor = new HttpLoggingInterceptor();
            loggingInterceptor.setLevel(HttpLoggingInterceptor.Level.BODY);

            OkHttpClient okHttpClient = new OkHttpClient.Builder()
                    .addInterceptor(loggingInterceptor)
                    .connectTimeout(30, TimeUnit.SECONDS)
                    .readTimeout(30, TimeUnit.SECONDS)
                    .writeTimeout(30, TimeUnit.SECONDS)
                    .build();

            // KHỞI TẠO RETROFIT Ở ĐÂY
            retrofit = new Retrofit.Builder()
                    .baseUrl(BASE_URL) // Đặt BASE_URL
                    .client(okHttpClient) // Sử dụng OkHttpClient đã cấu hình
                    .addConverterFactory(GsonConverterFactory.create()) // Thêm Gson converter
                    .build();
        }
        return retrofit; // Trả về instance đã được tạo hoặc đã tồn tại
    }
}