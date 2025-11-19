<?php

namespace App\Modules\Product\Http\Providers;

use App\Modules\Product\Http\Observers\ProductObserver;
use App\Modules\Product\Infrastructure\Persistence\Models\Product\Product;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
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
        //
        $this->loadMigrationsFrom(app_path('Modules/Product/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Product/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Product/Http/Routes/Dashboard.php'));
        foreach (glob(app_path('Modules/Product/Application/Helpers') . '/*.php') as $filename) {
            require_once $filename;
        }

    }
}
