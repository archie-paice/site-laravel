<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    private array $permissions = [
        'core' => ['edit own profile'],
        'staff' => ['view dashboard'],
        'admin' => ['manage users', 'assign roles', 'manage roles', 'view audit logs', 'manage visiting controllers', 'manage loas' ],
        'events' => ['manage events', 'assign event positions', 'publish events'],
        'facilities' => ['manage statistics prefixes', 'manage certification facilities'],
        'training' => ['create training tickets', 'edit training tickets', 'claim students', 'issue solo certs' ],
        'instructor' => ['revoke solo certs', 'manage training tickets', 'manage students']
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->permissions as $group => $groupPermissions) {
            $role = Role::firstOrCreate(['name' => $group]);

            foreach ($groupPermissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
                $role->givePermissionTo($permission);
            }
        }
    }
}
