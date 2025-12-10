<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\Impersonation\ImpersonationManager::class);
        $this->app->singleton(\App\Repositories\SettingRepository::class);
        $this->app->singleton(\App\Repositories\ReleaseNoteRepository::class);
        $this->app->bind(\App\Services\Iugu\IuguClient::class, function ($app) {
            $token = $app->make(\App\Repositories\SettingRepository::class)
                ->get('integrations.iugu', 'api_token');

            return new \App\Services\Iugu\IuguClient($token);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
