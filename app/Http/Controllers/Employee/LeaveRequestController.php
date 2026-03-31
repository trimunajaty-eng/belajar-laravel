<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('employee.leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('employee.leave-requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:cuti,sakit,izin',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:1000',
        ], [
            'type.required'            => 'Jenis pengajuan wajib dipilih.',
            'start_date.required'      => 'Tanggal mulai wajib diisi.',
            'start_date.after_or_equal'=> 'Tanggal mulai tidak boleh sebelum hari ini.',
            'end_date.required'        => 'Tanggal akhir wajib diisi.',
            'end_date.after_or_equal'  => 'Tanggal akhir tidak boleh sebelum tanggal mulai.',
            'reason.required'          => 'Alasan pengajuan wajib diisi.',
        ]);

        LeaveRequest::create([
            'user_id'    => Auth::id(),
            'type'       => $request->type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => $request->reason,
            'status'     => 'pending',
        ]);

        return redirect()->route('employee.leave-requests.index')
            ->with('success', 'Pengajuan berhasil dikirim. Menunggu konfirmasi admin.');
    }
}
