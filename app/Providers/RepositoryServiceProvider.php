<?php

namespace App\Providers;

use App\Repositories\UrlRepository;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\UrlRepositoryInterface;
use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UrlRepositoryInterface::class, UrlRepository::class);

        // Bind multiple classes
        // $this->app->bind([
        //     BaseRepositoryInterface::class => BaseRepository::class,
        //     UserRepositoryInterface::class => UserRepository::class
        // ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
