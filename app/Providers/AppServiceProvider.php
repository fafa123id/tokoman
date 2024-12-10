<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Misc\MiscManager;
use App\Repositories\RepositoryManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RepositoryManager::class, function () {
            return new RepositoryManager();
        });
        $this->app->singleton(MiscManager::class,function (){
            return new MiscManager();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') != 'local') {
            \URL::forceScheme('https');
        }
    }
}
