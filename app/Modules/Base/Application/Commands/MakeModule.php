<?php

namespace App\Modules\Base\Application\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:make-module';
    protected $signature = 'make:module {name}';
    
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module with default folders';
    
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $moduleName = ucfirst($this->argument('name'));
        $modulePath = app_path("Modules/{$moduleName}");

        if ($this->files->exists($modulePath)) {
            $this->error("Module {$moduleName} already exists!");
            return;
        }


        // Create module folder and subfolders
        $directories = [
            //APPLICATION
            "{$modulePath}/Application/UseCases",
            "{$modulePath}/Application/DTOS",
            //DOMAIN
            "{$modulePath}/Domain/Repositories",
            //INFRASTRUCTURE
            "{$modulePath}/Infrastructure/Persistence/Models",
            "{$modulePath}/Infrastructure/Persistence/Repositories",
            "{$modulePath}/Infrastructure/DataBase/Migrations",
            //HTTP
            "{$modulePath}/Http/Controllers",
            "{$modulePath}/Http/Requests",
            "{$modulePath}/Http/Providers",
            "{$modulePath}/Http/Routes",
            "{$modulePath}/Http/Resources",
        ];

        foreach ($directories as $dir) {
            $this->files->makeDirectory($dir, 0755, true);
            $this->files->put("{$dir}/.gitkeep", '');
        }

        //HTTP FILES
        $this->files->put("{$modulePath}/Http/Providers/{$moduleName}ServiceProvider.php", "");
        $this->files->put("{$modulePath}/Http/Routes/Api.php", "");

        $this->info("Module {$moduleName} created successfully.");
    }
}
