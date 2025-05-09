<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //

		$this->app->bind(
			\App\Interfaces\Auth\AuthServiceInterface::class,
			\App\Services\Auth\AuthService::class
		);

		$this->app->bind(
			\App\Interfaces\HelloServiceInterface::class,
			\App\Services\HelloService::class
		);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
