<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Announcement;
use App\Models\WorkSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function checkin()
    {
        $today = now()->toDateString();
        $user = Auth::user();
        $workSetting = WorkSetting::first();
        $lateThreshold = $workSetting ? $workSetting->late_threshold : '09:00:00';
        
        $attendance = Attendance::firstOrCreate([
            'user_id' => $user->id,
            'date' => $today,
        ], [
            'check_in' => now()->format('H:i:s'),
            'status' => now()->format('H:i:s') > $lateThreshold ? 'late' : 'present'
        ]);

        return redirect()->back()->with('success', 'Checked in successfully!');
    }

    public function checkout()
    {
        $today = now()->toDateString();
        $user = Auth::user();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance) {
            $attendance->update([
                'check_out' => now()->format('H:i:s')
            ]);
        }

        return redirect()->back()->with('success', 'Checked out successfully!');
    }
}