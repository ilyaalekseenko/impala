<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Role::all() as $role) {
            if ($role->slug == 'admin') { // для роли супер-админа все права
                foreach (Permission::all() as $perm) {
                    $role->permissions()->attach($perm->id);
                }
            }
            if ($role->slug == 'logist') {
                $slugs = [
                    'see_self_orders'
                ];
                foreach ($slugs as $slug) {
                    $perm = Permission::where('slug', $slug)->first();
                    $role->permissions()->attach($perm->id);
                }
            }
            if ($role->slug == 'manager') {
                $slugs = [
                    'see_all_logists_orders'
                ];
                foreach ($slugs as $slug) {
                    $perm = Permission::where('slug', $slug)->first();
                    $role->permissions()->attach($perm->id);
                }
            }


        }
    }
}
