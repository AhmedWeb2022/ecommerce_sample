<?php

namespace App\Modules\Category\Infrastructure\DataBase\Seeders;

use App\Modules\Category\Infrastructure\Persistence\Models\Category\Category;
use App\Modules\Statistics\Infrastructure\Persistence\Models\Banner\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data1 = ['title' => 'Medicines'];
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data1[$localeCode] = ['title' => 'Medicines', 'subtitle' => 'Medicines', 'description' => 'Medicines'];
        }

        $banner1 = Banner::create($data1);
        
        $data2 = ['title' => 'Cosmetics'];
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data2[$localeCode] = ['title' => 'Cosmetics', 'subtitle' => 'Cosmetics', 'description' => 'Cosmetics'];
        }
        $banner2 = Banner::create($data2);
    }
}
