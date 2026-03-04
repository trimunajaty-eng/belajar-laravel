<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\WorkSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $today = Carbon::today();
        $existing = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already checked in today'], 400);
        }

        $workSetting = WorkSetting::first();
        $checkInTime = Carbon::now();
        $lateThreshold = $workSetting ? Carbon::parse($workSetting->late_threshold) : Carbon::parse('09:00:00');
        
        $status = $checkInTime->gt($lateThreshold) ? 'late' : 'present';

        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'date' => $today,
            'check_in' => $checkInTime->format('H:i:s'),
            'status' => $status,
        ]);

        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    public function checkOut(Request $request)
    {
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['message' => 'No check-in record found'], 400);
        }

        if ($attendance->check_out) {
            return response()->json(['message' => 'Already checked out'], 400);
        }

        $attendance->update([
            'check_out' => Carbon::now()->format('H:i:s'),
        ]);

        return response()->json(['success' => true, 'attendance' => $attendance]);
    }
}
