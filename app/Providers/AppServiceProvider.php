<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->addNamespace('senaapicola', base_path('Modules/SENAAPICOLA/Resources/views'));
        // AdminLTE usa Bootstrap; evita paginación con estilos grandes.
        Paginator::useBootstrapFour();
    }
}
