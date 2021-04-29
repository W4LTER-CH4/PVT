<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Sismu;
use App\Observers\SismuObserver;
use Illuminate\Support\Facades\Schema;

class SismuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::connection('platform')->hasTable('sismus')) Sismu::observe(SismuObserver::class);
    }
}
