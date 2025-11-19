<?php

namespace App\Modules\Auth\Http\Providers;

use App\Models\Payment\PaymentMethod;
use App\Modules\PaymentGateway\Domain\Services\PaymentGatewayDomainService;
use App\Services\Payment\PaymentGatewayEnum;
use Illuminate\Support\ServiceProvider;
use App\Modules\Auth\Domain\Repositories\Api\Customer\CustomerRepositoryInterface;
use App\Modules\Auth\Infrastructure\Persistence\Repositories\Customer\CustomerRepository;

class AuthServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(app_path('Modules/Auth/Infrastructure/DataBase/Migrations'));
//        $this->loadViewsFrom(app_path('Modules/Auth/Http/Views'), 'Auth');
        $this->loadRoutesFrom(app_path('Modules/Auth/Http/Routes/Api.php'));
        $this->loadRoutesFrom(app_path('Modules/Auth/Http/Routes/Dashboard.php'));

    }

}
