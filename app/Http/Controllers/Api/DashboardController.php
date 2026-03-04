<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Announcement;
use App\Models\User;
use App\Models\WorkSetting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->employeeDashboard($user);
        }
    }
    
    private function adminDashboard()
    {
        $today = now()->toDateString();
        $workSetting = WorkSetting::first();
        
        $todayAttendances = Attendance::with('user')
            ->where('date', $today)
            ->get();
            
        $totalEmployees = User::where('role', 'employee')->count();
        $presentToday = $todayAttendances->count();
        $lateToday = $todayAttendances->where('status', 'late')->count();
        $absentToday = $totalEmployees - $presentToday;
        
        return response()->json([
            'success' => true,
            'data' => [
                'todayAttendances' => $todayAttendances,
                'totalEmployees' => $totalEmployees,
                'presentToday' => $presentToday,
                'lateToday' => $lateToday,
                'absentToday' => $absentToday,
                'workSetting' => $workSetting
            ]
        ]);
    }
    
    private function employeeDashboard($user)
    {
        $today = now()->toDateString();
        
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        $announcements = Announcement::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentAttendance = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'todayAttendance' => $todayAttendance,
                'announcements' => $announcements,
                'recentAttendance' => $recentAttendance
            ]
        ]);
    }
}