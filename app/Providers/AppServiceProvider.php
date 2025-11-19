<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $featuresPath = app_path('Modules');
        $features = File::directories($featuresPath);

        foreach ($features as $feature) {
            $featureName = basename($feature);
            $providerClass = "App\\Modules\\{$featureName}\\Http\\Providers\\{$featureName}ServiceProvider";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }
}
