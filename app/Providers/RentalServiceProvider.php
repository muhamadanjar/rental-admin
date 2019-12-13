<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RentalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    protected $defer = false;
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
        $services = [
            'App\Rental\RepositoryInterface' => 'App\Rental\EloquentRepository',
            'App\Rental\Contract\IOrderRepository' => 'App\Rental\Repository\Order\OrderRepository',
        ];

        foreach ($services as $contract => $service) {
            $this->app->bind($contract, $service);
        }
    }
}
