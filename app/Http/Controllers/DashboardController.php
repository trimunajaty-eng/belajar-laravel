<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Announcement;
use App\Models\User;
use App\Models\WorkSetting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->employeeDashboard();
        }
    }
    
    private function adminDashboard()
    {
        $today = now()->toDateString();
        
        // Get or create default work settings
        $workSetting = WorkSetting::first();
        if (!$workSetting) {
            $workSetting = WorkSetting::create([
                'work_start_time' => '08:00:00',
                'work_end_time' => '17:00:00', 
                'late_threshold' => '09:00:00'
            ]);
        }
        
        // Get all employees
        $employees = User::where('role', 'employee')->get();
        
        // Get today's attendances with user relationship
        $todayAttendances = Attendance::with('user')
            ->where('date', $today)
            ->get();
            
        // Calculate statistics
        $totalEmployees = $employees->count();
        $presentToday = $todayAttendances->count();
        $lateToday = $todayAttendances->where('status', 'late')->count();
        $absentToday = $totalEmployees - $presentToday;
        
        // Get employees who haven't checked in
        $checkedInUserIds = $todayAttendances->pluck('user_id')->toArray();
        $absentEmployees = $employees->whereNotIn('id', $checkedInUserIds);
        
        return view('admin.dashboard', compact(
            'todayAttendances', 
            'totalEmployees', 
            'presentToday', 
            'lateToday', 
            'absentToday',
            'workSetting',
            'absentEmployees'
        ));
    }
    
    private function employeeDashboard()
    {
        $user = auth()->user();
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
        
        return view('employee.dashboard', compact('todayAttendance', 'announcements', 'recentAttendance'));
    }
}