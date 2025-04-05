<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    protected $superAdminNamespace = 'App\\SuperAdmin\\Http\\Controllers';

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
        Route::middleware('web')
                    ->namespace($this->superAdminNamespace)
                    ->group(base_path('app/SuperAdmin/routes/main.php'));
    }
}
