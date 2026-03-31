<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Notifications\Notification;

class LeaveRequestReviewed extends Notification
{
    public function __construct(public LeaveRequest $leaveRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $type  = match($this->leaveRequest->type) {
            'cuti'  => 'Cuti',
            'sakit' => 'Sakit',
            'izin'  => 'Izin',
        };

        $status = $this->leaveRequest->status === 'approved' ? 'disetujui' : 'ditolak';

        return [
            'leave_request_id' => $this->leaveRequest->id,
            'type'             => $this->leaveRequest->type,
            'status'           => $this->leaveRequest->status,
            'message'          => "Pengajuan {$type} Anda telah {$status} oleh admin.",
            'admin_note'       => $this->leaveRequest->admin_note,
            'start_date'       => $this->leaveRequest->start_date->format('d M Y'),
            'end_date'         => $this->leaveRequest->end_date->format('d M Y'),
        ];
    }
}
