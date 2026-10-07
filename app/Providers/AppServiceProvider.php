<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.app', function ($view): void {
            $user = Auth::user();

            $view->with([
                'navNotifications' => $user?->notifications()->latest()->limit(6)->get() ?? collect(),
                'unreadNotificationCount' => $user?->unreadNotifications()->count() ?? 0,
            ]);
        });
    }
}
