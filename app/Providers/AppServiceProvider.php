<?php

namespace App\Providers;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('admin.*', function ($view) {
            if (Auth::check()) {
                $view->with('pendingCount', LeaveRequest::where('status', 'pending')->count());
            }
        });
    }
}
