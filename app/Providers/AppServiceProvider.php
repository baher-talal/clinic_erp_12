<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fix for older MySQL versions (< 5.7.7) — key too long error
        Schema::defaultStringLength(191);

        // Prevent lazy loading issues in development
        // Model::preventLazyLoading(! app()->isProduction());
    }
}
