<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->paginate(15);
        $pendingCount  = LeaveRequest::where('status', 'pending')->count();

        return view('admin.leave-requests.index', compact('leaveRequests', 'pendingCount'));
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate(['admin_note' => 'nullable|string|max:500']);

        $leaveRequest->update([
            'status'      => 'approved',
            'admin_note'  => $request->admin_note,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Pengajuan {$leaveRequest->user->name} telah disetujui.");
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate(['admin_note' => 'nullable|string|max:500']);

        $leaveRequest->update([
            'status'      => 'rejected',
            'admin_note'  => $request->admin_note,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Pengajuan {$leaveRequest->user->name} telah ditolak.");
    }
}
