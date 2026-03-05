<?php

namespace App\Providers;

use App\Models\Personal;
use App\Observers\PersonalObserver;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('path.public', function() {
        //     return base_path('adm');
        // });
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        DB::listen(function ($query) {
            if ($query->time > 100) { // solo las lentas (>100ms)
                Log::warning('Query lenta', [
                    'sql'  => $query->sql,
                    'time' => $query->time,
                ]);
            }
        });
        // Filament::serving(function () {
        //     // Using Laravel Mix
        //     Filament::registerTheme(
        //         mix('css/filament.css'),
        //     );
        // });
    }
}
