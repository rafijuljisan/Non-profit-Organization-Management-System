<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ক্যাশ ক্লিয়ার করা (Spatie Permission এর বেস্ট প্র্যাকটিস)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // কিছু বেসিক পারমিশন তৈরি করা
        $permissions = [
            'manage_districts',
            'manage_users',
            'manage_finances',
            'manage_projects',
            'view_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // রোল তৈরি করা এবং পারমিশন অ্যাসাইন করা
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $districtAdminRole = Role::firstOrCreate(['name' => 'District Admin']);
        $districtAdminRole->givePermissionTo(['manage_users', 'manage_projects', 'view_reports']);

        $memberRole = Role::firstOrCreate(['name' => 'Member']);
        $memberRole->givePermissionTo(['view_reports']);

        // একজন ডিফল্ট সুপার এডমিন তৈরি করা
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // লগইন পাসওয়ার্ড: password
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // সুপার এডমিন রোল অ্যাসাইন করা
        $admin->assignRole($superAdminRole);
    }
}