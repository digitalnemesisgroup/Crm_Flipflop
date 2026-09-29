<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Safe to run multiple times — uses firstOrCreate.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles (idempotent)
        $superAdmin  = Role::firstOrCreate(['name' => 'SUPER ADMIN',   'guard_name' => 'web']);
        $admin       = Role::firstOrCreate(['name' => 'ADMIN',         'guard_name' => 'web']);
        $manager     = Role::firstOrCreate(['name' => 'MANAGER',       'guard_name' => 'web']);
        $accountTeam = Role::firstOrCreate(['name' => 'ACCOUNT TEAM',  'guard_name' => 'web']);
        $employee    = Role::firstOrCreate(['name' => 'EMPLOYEE',      'guard_name' => 'web']);
        $freelancer  = Role::firstOrCreate(['name' => 'FREELANCER',    'guard_name' => 'web']);

        // Create default Super Admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@flipflop.com'],
            [
                'name'     => 'FlipFlop Admin',
                'password' => bcrypt('FlipFlop@Admin2026'),
            ]
        );

        if (!$user->hasRole('SUPER ADMIN')) {
            $user->assignRole($superAdmin);
        }

        $this->command->info("✅ Roles created: SUPER ADMIN, ADMIN, MANAGER, ACCOUNT TEAM, EMPLOYEE, FREELANCER");
        $this->command->info("✅ Admin user: admin@flipflop.com | Password: FlipFlop@Admin2026");
        $this->command->warn("⚠️  Change the admin password immediately after first login!");
    }
}
