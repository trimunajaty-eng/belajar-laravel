<?php

namespace App\Http\Controllers;

use App\Models\WorkSetting;
use Illuminate\Http\Request;

class WorkSettingController extends Controller
{
    public function index()
    {
        $workSetting = WorkSetting::first() ?? new WorkSetting();
        return view('admin.work-settings', compact('workSetting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i',
            'late_threshold' => 'required|date_format:H:i',
        ]);

        $workSetting = WorkSetting::first();
        
        if ($workSetting) {
            $workSetting->update([
                'work_start_time' => $request->work_start_time . ':00',
                'work_end_time' => $request->work_end_time . ':00',
                'late_threshold' => $request->late_threshold . ':00',
            ]);
        } else {
            WorkSetting::create([
                'work_start_time' => $request->work_start_time . ':00',
                'work_end_time' => $request->work_end_time . ':00',
                'late_threshold' => $request->late_threshold . ':00',
            ]);
        }

        return redirect()->back()->with('success', 'Work schedule updated successfully!');
    }
}