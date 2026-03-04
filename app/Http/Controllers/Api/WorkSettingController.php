<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkSetting;
use Illuminate\Http\Request;

class WorkSettingController extends Controller
{
    public function index()
    {
        $setting = WorkSetting::first();
        
        if (!$setting) {
            return response()->json([
                'work_start_time' => '08:00:00',
                'late_threshold' => '09:00:00',
                'work_end_time' => '17:00:00',
            ]);
        }

        return response()->json($setting);
    }

    public function store(Request $request)
    {
        $request->validate([
            'work_start_time' => 'required',
            'late_threshold' => 'required',
            'work_end_time' => 'required',
        ]);

        $setting = WorkSetting::first();

        if ($setting) {
            $setting->update([
                'work_start_time' => $request->work_start_time,
                'late_threshold' => $request->late_threshold,
                'work_end_time' => $request->work_end_time,
            ]);
        } else {
            $setting = WorkSetting::create([
                'work_start_time' => $request->work_start_time,
                'late_threshold' => $request->late_threshold,
                'work_end_time' => $request->work_end_time,
            ]);
        }

        return response()->json($setting);
    }
}
