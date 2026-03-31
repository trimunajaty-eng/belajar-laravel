<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Ambil notifikasi unread milik user yang login
    Route::get('/notifications/unread', function () {
        $notifications = auth()->user()
            ->unreadNotifications
            ->map(fn($n) => [
                'id'      => $n->id,
                'data'    => $n->data,
                'created' => $n->created_at->locale('id')->diffForHumans(),
            ]);

        return response()->json($notifications);
    });

    // Tandai satu notifikasi sudah dibaca
    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()
            ->unreadNotifications
            ->where('id', $id)
            ->first()
            ?->markAsRead();

        return response()->json(['ok' => true]);
    });

    // Tandai semua notifikasi sudah dibaca
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['ok' => true]);
    });
});
