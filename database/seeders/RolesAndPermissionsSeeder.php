<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $resources = [
            'user'        => ['view', 'create', 'edit', 'delete'],
            'donation'    => ['view', 'create', 'edit', 'delete'],
            'project'     => ['view', 'create', 'edit', 'delete'],
            'expense'     => ['view', 'create', 'edit', 'delete'],
            'subscription'=> ['view', 'create', 'edit', 'delete'],
            'gallery'     => ['view', 'create', 'edit', 'delete'],
            'inquiry'     => ['view', 'delete'],
            'page'        => ['view', 'create', 'edit', 'delete'],
            'setting'     => ['view', 'edit'],
            'testimonial' => ['view', 'create', 'edit', 'delete'],
            'designation' => ['view', 'create', 'edit', 'delete'],
            'district'    => ['view', 'create', 'edit', 'delete'],
            'upazila'     => ['view', 'create', 'edit', 'delete'],
            'activity'    => ['view'],  // logs are read-only
        ];

        foreach ($resources as $resource => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $resource . '_' . $action]);
            }
        }

        // super_admin — bypass via before(), no need to assign permissions
        Role::firstOrCreate(['name' => 'super_admin']);

        // admin — everything
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // editor — content only, no users/settings/districts/upazilas
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->syncPermissions([
            'donation_view',
            'project_view', 'project_create', 'project_edit',
            'expense_view',
            'subscription_view',
            'gallery_view', 'gallery_create', 'gallery_edit', 'gallery_delete',
            'inquiry_view', 'inquiry_delete',
            'page_view', 'page_create', 'page_edit',
            'testimonial_view', 'testimonial_create', 'testimonial_edit', 'testimonial_delete',
        ]);

        // viewer — read only
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->syncPermissions([
            'donation_view',
            'project_view',
            'expense_view',
            'subscription_view',
            'user_view',
            'inquiry_view',
            'gallery_view',
            'activity_view',
        ]);

        $this->command->info('✅ All roles and permissions seeded.');
    }
}