<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WorkSettingController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Employee\PasswordController;
use App\Http\Controllers\Employee\LeaveRequestController;
use App\Http\Controllers\Admin\LeaveRequestController as AdminLeaveRequestController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    
    Route::get('/employee/change-password', [PasswordController::class, 'edit'])->name('employee.change-password');
    Route::post('/employee/change-password', [PasswordController::class, 'update'])->name('employee.change-password.update');

    Route::get('/employee/leave-requests', [LeaveRequestController::class, 'index'])->name('employee.leave-requests.index');
    Route::get('/employee/leave-requests/create', [LeaveRequestController::class, 'create'])->name('employee.leave-requests.create');
    Route::post('/employee/leave-requests', [LeaveRequestController::class, 'store'])->name('employee.leave-requests.store');
    
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('/work-settings', [WorkSettingController::class, 'index'])->name('work-settings.index');
        Route::post('/work-settings', [WorkSettingController::class, 'store'])->name('work-settings.store');
        Route::resource('announcements', AnnouncementController::class);
        Route::get('/announcements-trash', [AnnouncementController::class, 'trash'])->name('announcements.trash');
        Route::post('/announcements-trash/{id}/restore', [AnnouncementController::class, 'restore'])->name('announcements.restore');
        Route::delete('/announcements-trash/{id}/force-delete', [AnnouncementController::class, 'forceDelete'])->name('announcements.force-delete');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('/admin/leave-requests', [AdminLeaveRequestController::class, 'index'])->name('admin.leave-requests.index');
        Route::post('/admin/leave-requests/{leaveRequest}/approve', [AdminLeaveRequestController::class, 'approve'])->name('admin.leave-requests.approve');
        Route::post('/admin/leave-requests/{leaveRequest}/reject', [AdminLeaveRequestController::class, 'reject'])->name('admin.leave-requests.reject');
    });
});
