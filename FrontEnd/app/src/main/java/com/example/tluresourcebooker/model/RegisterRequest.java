
package com.example.tluresourcebooker.model;

import com.google.gson.annotations.SerializedName;

public class RegisterRequest {
    @SerializedName("full_name")
    private String fullName;

    @SerializedName("email")
    private String email;

    @SerializedName("password")
    private String password;


     @SerializedName("password_confirmation")
     private String passwordConfirmation;

    public RegisterRequest(String fullName, String email, String password,String passwordConfirmation) {
        this.fullName = fullName;
        this.email = email;
        this.password = password;
         this.passwordConfirmation = password;
    }
}