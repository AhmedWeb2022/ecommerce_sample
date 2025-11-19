<?php

namespace App\Modules\Base\Domain\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make module structure with DDD structure';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $baseName = ucfirst($this->argument('name'));
        $basePath = app_path('Modules/' . $baseName);
        $structure = [
            'Domain/DTO',
            'Domain/Entity',
            'Domain/Holders',
            'Domain/Repositories',
            'Domain/Request',
            'Domain/Services',
            'Application/DTOS',
            'Application/Queries',
            'Infrastructure/DataBase/Migrations',
            'Infrastructure/DataBase/Seeders',
            "Infrastructure/Persistence/Models/{$baseName}",
            'Infrastructure/Persistence/Repositories/',
            'Http/Middleware',
            'Http/Providers',
            'Http/Routes',
            'Http/Enums',
            'Http/Requests',
            'Http/DataTables',
            'Http/Controllers',
            'Http/Resources',
            'Http/Views',
        ];
        foreach ($structure as $path) {
            $fullPath = "$basePath/$path";
            File::makeDirectory($fullPath, 0755, true, true);
            $this->info("Created: $fullPath");
        }
        $files = [
            "Http/Providers/{$baseName}ServiceProvider.php",
            'Http/Routes/Api.php',
            'Http/Routes/Web.php',
        ];
        foreach ($files as $file) {
            $filePath = "$basePath/$file";
            File::put($filePath, "<?php\n\n// $file");
            $this->info("Created file: $filePath");
        }
        $this->info(":white_check_mark: {$baseName} module structure created successfully.");
    }
}
