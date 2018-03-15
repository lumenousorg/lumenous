<?php

namespace lumenous\Providers;

use Illuminate\Support\ServiceProvider;
use lumenous\Repositories\Interfaces;
use lumenous\Repositories;

class RepositoryServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      
        $this->app->singleton(
            Interfaces\UsersRepositoryInterface::class,
            Repositories\EloquentUsersRepository::class
        );
        $this->app->singleton(
            Interfaces\ActiveAccountsRepositoryInterface::class,
            Repositories\EloquentActiveAccountsRepository::class
        );
        $this->app->singleton(
            Interfaces\InflationEffectsRepositoryInterface::class,
            Repositories\EloquentInflationEffectsRepository::class
        );
        $this->app->singleton(
            Interfaces\LedgersRepositoryInterface::class,
            Repositories\EloquentLedgersRepository::class
        );
        $this->app->singleton(
            Interfaces\PayoutsRepositoryInterface::class,
            Repositories\EloquentPayoutsRepository::class
        );
        $this->app->singleton(
            Interfaces\TransactionsRepositoryInterface::class,
            Repositories\EloquentTransactionsRepository::class
        );
        $this->app->singleton(
        Interfaces\TestAccountsBatchesRepositoryInterface::class,
        Repositories\EloquentTestAccountsBatchesRepository::class
        );
    }

}
