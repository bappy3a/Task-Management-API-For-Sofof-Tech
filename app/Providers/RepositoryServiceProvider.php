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

		$this->app->bind(
			\App\Interfaces\Task\TaskServiceInterface::class,
			\App\Services\Task\TaskService::class
		);

		$this->app->bind(
			\App\Interfaces\Auth\AuthServiceInterface::class,
			\App\Services\Auth\AuthService::class
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
