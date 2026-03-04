<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $userId = $request->input('user_id');

        $query = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // Statistics
        $totalPresent = $attendances->whereIn('status', ['present', 'late'])->count();
        $totalLate = $attendances->where('status', 'late')->count();
        $totalAbsent = $attendances->where('status', 'absent')->count();
        
        // Calculate total working hours
        $totalWorkingHours = 0;
        foreach ($attendances as $attendance) {
            if ($attendance->check_in && $attendance->check_out) {
                $checkIn = Carbon::parse($attendance->check_in);
                $checkOut = Carbon::parse($attendance->check_out);
                $totalWorkingHours += $checkIn->diffInHours($checkOut);
            }
        }

        // Get all employees for filter
        $users = User::where('role', 'employee')->orderBy('name')->get();

        // Group by employee
        $reportByEmployee = $attendances->groupBy('user_id')->map(function ($items) {
            return [
                'user' => $items->first()->user,
                'total_days' => $items->count(),
                'present' => $items->whereIn('status', ['present', 'late'])->count(),
                'late' => $items->where('status', 'late')->count(),
                'absent' => $items->where('status', 'absent')->count(),
                'attendance_rate' => $items->count() > 0 
                    ? round(($items->whereIn('status', ['present', 'late'])->count() / $items->count()) * 100, 1)
                    : 0
            ];
        });

        return view('admin.reports.index', compact(
            'attendances',
            'users',
            'startDate',
            'endDate',
            'userId',
            'totalPresent',
            'totalLate',
            'totalAbsent',
            'totalWorkingHours',
            'reportByEmployee'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $userId = $request->input('user_id');

        $query = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        $filename = "attendance_report_" . $startDate . "_to_" . $endDate . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Employee', 'Email', 'Check In', 'Check Out', 'Status', 'Working Hours']);

            foreach ($attendances as $attendance) {
                $workingHours = '-';
                if ($attendance->check_in && $attendance->check_out) {
                    $checkIn = Carbon::parse($attendance->check_in);
                    $checkOut = Carbon::parse($attendance->check_out);
                    $hours = $checkIn->diffInHours($checkOut);
                    $minutes = $checkIn->diffInMinutes($checkOut) % 60;
                    $workingHours = sprintf('%d:%02d', $hours, $minutes);
                }

                fputcsv($file, [
                    $attendance->date,
                    $attendance->user->name,
                    $attendance->user->email,
                    $attendance->check_in ? Carbon::parse($attendance->check_in)->format('H:i:s') : '-',
                    $attendance->check_out ? Carbon::parse($attendance->check_out)->format('H:i:s') : '-',
                    ucfirst($attendance->status),
                    $workingHours
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
