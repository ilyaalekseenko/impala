<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles= Role::all();
        $permissions= Permission::all();
        foreach ($roles as $role) {
            foreach ($permissions as $perm) {
                $role->permissions()->attach([$perm['id']]);
                    }
        }
    }
}
