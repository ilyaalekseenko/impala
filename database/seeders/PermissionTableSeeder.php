<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['slug' => 'all_permissions', 'name' => 'Все права'],
            ['slug' => 'see_self_orders', 'name' => 'Видит свои заявки'],
            ['slug' => 'see_all_logists_orders', 'name' => 'Видит все заявки логистов'],
            ];
        foreach ($permissions as $item) {
            $permission = new Permission();
            $permission->name = $item['name'];
            $permission->slug = $item['slug'];
            $permission->save();
        }
    }
}
