<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Xác thực quyền gửi request
     */
    public function authorize(): bool
    {
        return true; // ✅ Cho phép tất cả, hoặc tự kiểm tra quyền nếu cần
    }

    /**
     * Xác thực dữ liệu đầu vào
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }
}
