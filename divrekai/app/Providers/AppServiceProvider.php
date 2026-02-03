<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Unit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // DATA UNIT UNTUK NAVBAR
        View::composer('layouts.app', function ($view) {
            $view->with('navbarUnits', Unit::orderBy('nama_unit')->get());
        });
    }
}
