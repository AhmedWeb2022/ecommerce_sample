<?php

namespace App\Modules\Category\Http\Providers;

use App\Modules\Category\Http\Observers\BrandObserver;
use App\Modules\Category\Http\Observers\CategoryObserver;
use App\Modules\Category\Infrastructure\Persistence\Models\Brand\Brand;
use App\Modules\Category\Infrastructure\Persistence\Models\Category\Category;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(app_path('Modules/Category/Infrastructure/DataBase/Migrations'));

        $this->loadRoutesFrom(app_path('Modules/Category/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Category/Http/Routes/Dashboard.php'));
        foreach (glob(app_path('Modules/Category/Application/Helpers') . '/*.php') as $filename) {
            require_once $filename;
        }


    }
}
