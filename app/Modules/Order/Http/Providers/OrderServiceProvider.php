<?php

namespace App\Modules\Order\Http\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Order\Http\Observers\OrderObserver;
use App\Modules\Order\Infrastructure\Persistence\Models\Order\Order;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(app_path('Modules/Order/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Order/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Order/Http/Routes/Dashboard.php'));

        // Order::observe(OrderObserver::class);
    }
}
