<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'settings.manage',
            'homepage.manage',
            'pages.manage',
            'navigation.manage',
            'catalog.manage',
            'menu.manage',
            'gallery.manage',
            'journal.manage',
            'orders.manage',
            'customers.manage',
            'analytics.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findOrCreate('super-admin', 'web');
        $admin = Role::findOrCreate('admin', 'web');
        $editor = Role::findOrCreate('editor', 'web');

        $superAdmin->givePermissionTo($permissions);
        $admin->givePermissionTo($permissions);
        $editor->givePermissionTo(['homepage.manage', 'pages.manage', 'navigation.manage', 'gallery.manage', 'journal.manage']);
        $editor->revokePermissionTo('analytics.view');

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
