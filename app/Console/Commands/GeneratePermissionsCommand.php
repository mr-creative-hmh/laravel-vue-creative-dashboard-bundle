<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class GeneratePermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:generate {module : The name of the module or model (e.g. Product, orders)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate standard CRUD permissions for a module/model and assign to Super Admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $input = $this->argument('module');
        $module = Str::snake(Str::pluralStudly($input));

        $actions = ['view', 'create', 'edit', 'delete', 'export'];
        $created = [];

        foreach ($actions as $action) {
            $permissionName = "{$module}.{$action}";
            $perm = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);

            if ($perm->wasRecentlyCreated) {
                $created[] = $permissionName;
            }
        }

        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Automatically assign all permissions to Super Admin role
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
        }

        if (count($created) > 0) {
            $this->info('Successfully generated '.count($created)." permissions for module [{$module}]:");
            foreach ($created as $permName) {
                $this->line("  ✓ {$permName}");
            }
        } else {
            $this->warn("Permissions for module [{$module}] already exist.");
        }

        return self::SUCCESS;
    }
}
