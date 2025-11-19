<?php

namespace App\Modules\Base\Application\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class MakeModuleDTO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module-dto {dto} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a dto class inside a specific module for specific model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dtoName = Str::studly($this->argument('dto'));
        $moduleName = Str::studly($this->argument('module'));

        $modulePath = app_path("Modules/{$moduleName}");
        $namespace = "App\\Modules\\{$moduleName}\\Application\\DTOS\\" . Str::studly($dtoName);

        if (!File::isDirectory($modulePath)) {
            $this->error("Module {$moduleName} does not exist.");
            return;
        }

        $targetDir = app_path("Modules/{$moduleName}/Application/DTOS/" . Str::studly($dtoName));
        $moduleDtoPath = "{$targetDir}/{$dtoName}DTO.php"; // e.g., UserDTO.php

        // Create directory if not exists
        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        // Check if file already exists
        if (File::exists($moduleDtoPath)) {
            $this->error("DTO already exists: {$moduleDtoPath}");
            return 1;
        }


        // $stubPath = base_path('app/Modules/Base/Application/Commands/Stups/moduleDTO.stub');
        $stubPath = __DIR__ . '/Stups/moduleDTO.stub'; // Use __DIR__ to locate stub
        if (!File::exists($stubPath)) {
            return $this->error("Stub file not found at: {$stubPath}");
        }

        /* $filesystem = new Filesystem();
        $filesystem->copy($stubPath, $moduleDtoPath);

        $filesystem->replace(
            ['DummyNamespace', 'DummyClass'],
            [$namespace, $dto],
            $moduleDtoPath
        ); */
        // Read stub content
        $content = File::get($stubPath);

        // Replace placeholders
        $modelSnake = Str::snake(Str::singular($dtoName)); // user
        $content = str_replace(
            ['{{DummyNamespace}}', '{{DummyClass}}', '{{model}}'],
            [$namespace, "{$dtoName}DTO", $modelSnake],
            $content
        );
        // $content = str_replace('{{DummyNamespace}}', $namespace, $content);
        // $content = str_replace('{{DummyClass}}', "{$dtoName}DTO", $content);
        // $content = str_replace('{{model}}', $modelSnake, $content);

        // Write file
        File::put($moduleDtoPath, $content);



        $this->info("DTO created successfully at: {$moduleDtoPath}");
    }
}
