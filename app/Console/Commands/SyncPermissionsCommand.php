<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SyncPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically scan all Eloquent models in app/Models and generate missing CRUD permissions';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $modelsPath = app_path('Models');
        if (! File::exists($modelsPath)) {
            $this->error('Directory app/Models does not exist.');

            return self::FAILURE;
        }

        $files = File::allFiles($modelsPath);
        $totalCreated = 0;

        foreach ($files as $file) {
            $modelName = $file->getFilenameWithoutExtension();
            $module = Str::snake(Str::pluralStudly($modelName));
            $actions = ['view', 'create', 'edit', 'delete', 'export'];

            foreach ($actions as $action) {
                $permissionName = "{$module}.{$action}";
                $perm = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);

                if ($perm->wasRecentlyCreated) {
                    $totalCreated++;
                    $this->line("  ✓ Created {$permissionName}");
                }
            }
        }

        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Assign to Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
        }

        $this->info("Permissions sync completed. Total new permissions created: {$totalCreated}");

        return self::SUCCESS;
    }
}
