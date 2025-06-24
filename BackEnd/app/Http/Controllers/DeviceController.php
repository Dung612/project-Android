<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceType;
use Illuminate\Http\Request;
use App\Http\Resources\DeviceResource;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $query = Device::with('deviceType');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%");
        }
        if ($request->has('all')) {
            return DeviceResource::collection($query->get());
        }
        $devices = $query->paginate(10);
        return DeviceResource::collection($devices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'device_type_id' => 'required|exists:device_types,id',
            'status' => 'required|boolean',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        $device = Device::create($validated);
        $device->load('deviceType');
        return new DeviceResource($device);
    }

    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'device_type_id' => 'sometimes|required|exists:device_types,id',
            'status' => 'sometimes|required|boolean',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        $device->update($validated);
        $device->load('deviceType');
        return new DeviceResource($device);
    }

    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();
        return response()->json(['message' => 'Xóa thiết bị thành công']);
    }

    public function show($id)
    {
        $device = Device::with('deviceType')->findOrFail($id);
        return new \App\Http\Resources\DeviceResource($device);
    }
} 