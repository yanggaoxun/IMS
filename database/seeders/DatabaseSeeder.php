<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 权限按「模块.动作」命名，新业务模块照此模式添加
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        Role::firstOrCreate(['name' => 'user']);

        User::where('email', 'admin@example.com')->first()?->assignRole('admin');
    }
}
