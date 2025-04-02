<?php

namespace App\Providers;

use App\Contracts\{
    TeamRepositoryInterface,
    PlayerRepositoryInterface,
    GameRepositoryInterface,
    AuthRepositoryInterface,
    ExternalApiTokenRepositoryInterface
};
use App\Repositories\{
    TeamRepository,
    PlayerRepository,
    GameRepository,
    AuthRepository,
    ExternalApiTokenRepository
};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            TeamRepositoryInterface::class,
            TeamRepository::class
        );

        $this->app->bind(
            PlayerRepositoryInterface::class,
            PlayerRepository::class
        );

        $this->app->bind(
            GameRepositoryInterface::class,
            GameRepository::class
        );

        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
        );

        $this->app->bind(
            ExternalApiTokenRepositoryInterface::class,
            ExternalApiTokenRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
