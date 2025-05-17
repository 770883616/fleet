<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\Blade;
// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    

// public function boot()
// {
//     Blade::component('app-layout', \App\View\Components\AppLayout::class);
// }
public function boot(): void
    {
        // الطريقة الصحيحة لتسجيل مجموعة routes مع middleware
        $this->app->booted(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     //
    // }
}
