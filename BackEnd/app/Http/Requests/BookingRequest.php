<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'room_id'    => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
            'purpose'    => 'required|string',
            'note'       => 'nullable|string',
            'devices'           => 'nullable|array',
            'devices.*.device_id'  => 'required|exists:devices,id',
            'devices.*.quantity'   => 'nullable|integer|min:1',
            'devices.*.note'       => 'nullable|string|max:255',
        ];
    }
}
