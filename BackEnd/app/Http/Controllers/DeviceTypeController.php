<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;
use App\Http\Resources\DeviceTypeResource;

class DeviceTypeController extends Controller
{
    public function index()
    {
        return DeviceTypeResource::collection(DeviceType::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $type = DeviceType::create($validated);
        return new DeviceTypeResource($type);
    }

    public function update(Request $request, $id)
    {
        $type = DeviceType::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $type->update($validated);
        return new DeviceTypeResource($type);
    }

    public function destroy($id)
    {
        $type = DeviceType::findOrFail($id);
        $type->delete();
        return response()->json(['message' => 'Xóa loại thiết bị thành công']);
    }
} 