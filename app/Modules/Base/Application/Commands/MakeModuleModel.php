<?php

namespace App\Modules\Base\Application\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class MakeModuleModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:moduleModel {model} {module} {--translation} {--migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a model inside a specific module ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = Str::studly($this->argument('model'));
        $module = Str::studly($this->argument('module'));
        $isTranslation = $this->option('translation');
        $isMigration = $this->option('migration');
        $dbtable = strtolower(Str::plural($model));
        $modulePath = app_path("Modules/{$module}");
        $namespace = "App\\Modules\\{$module}\\Infrastructure\\Persistence\\Models\\" . Str::studly($model);

        if (!File::isDirectory($modulePath)) {
            $this->error("Module {$module} does not exist.");
            return;
        }

        $stubPath = base_path('app/Modules/Base/Application/Commands/Stups/moduleModel.stub');
        if ($isTranslation) {
            $stubPath = base_path('app/Modules/Base/Application/Commands/Stups/moduleModelWithTranslation.stub');
        }

        $targetDir = app_path("Modules/{$module}/Infrastructure/Persistence/Models/" . Str::studly($model));
        $modelPath = "{$targetDir}/{$model}.php";

        if (!File::exists($stubPath)) {
            return $this->error("Stub file not found at: {$stubPath}");
        }

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $stub = file_get_contents($stubPath);

        $stub = str_replace(
            ['{{model}}', '{{Module}}', '{{dbTable}}', '{{namespace}}'],
            [$model, $module, $dbtable, $namespace],
            $stub
        );


        File::put($modelPath, $stub);

        if ($isMigration) {
            //migration file start
            $migrationsPath = app_path("Modules/{$module}/Infrastructure/DataBase/Migrations");
            $migrationsStubPath = $isTranslation ? base_path('app/Modules/Base/Application/Commands/Stups/MigrationWithTranslation.stup') : base_path('app/Modules/Base/Application/Commands/Stups/Migration.stup');
            if (!File::exists($migrationsStubPath)) {
                return $this->error("Stub file not found at: {$migrationsStubPath}");
            }

            if (!File::exists($migrationsPath)) {
                File::makeDirectory($migrationsPath, 0755, true);
            }

            $migrationsStub = file_get_contents($migrationsStubPath);
            $time = now()->format('Y_m_d_His');
            $migrationsPath = app_path("Modules/{$module}/Infrastructure/DataBase/Migrations/{$time}_create_{$dbtable}_table.php");

            $migrationsStub = str_replace(
                ['{{Module}}', '{{dbTable}}'],
                [$module, $dbtable],
                $migrationsStub
            );

            File::put($migrationsPath, $migrationsStub);
            //migration file end
        }

        if ($isTranslation) {
            $dbtable = strtolower($model . '_translations');
            $stubTranslationPath = base_path('app/Modules/Base/Application/Commands/Stups/moduleTranslationModel.stub');
            if (!File::exists($stubTranslationPath)) {
                return $this->error("Stub file not found at: {$stubTranslationPath}");
            }
            $modelPath = "{$targetDir}/{$model}Translation.php";

            $stub = file_get_contents($stubTranslationPath);

            $stub = str_replace(
                ['{{model}}', '{{Module}}', '{{dbTable}}', '{{namespace}}'],
                [$model, $module, $dbtable, $namespace],
                $stub
            );

            File::put($modelPath, $stub);
        }

        $this->info("Model {$model} created in module {$module}.");
    }
}
