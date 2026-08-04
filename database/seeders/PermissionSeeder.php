<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    private array $permissions = [
        'core' => ['edit own profile'],
        'rostered' => ['create atc booking'],
        'staff' => ['view dashboard'],
        'admin' => ['manage users', 'assign roles', 'manage roles', 'view audit logs', 'manage visiting controllers', 'certifications:write', 'documents:write'],
        'events' => ['manage events', 'assign event positions', 'publish events'],
        'facilities' => ['manage statistics prefixes', 'certification-facilities:write', 'documents:write', 'statistics:write'],
        'training' => ['create training tickets', 'edit training tickets', 'claim students', 'issue solo certs', 'training-tickets:read', 'training-tickets:write', 'training-assignments:read', 'solo-certs:read'],
        'instructor' => ['revoke solo certs', 'manage training tickets', 'manage students', 'certifications:write'],
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
