<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['slug' => 'admin', 'name' => 'Админ'],
            ['slug' => 'logist', 'name' => 'Логист'],
            ['slug' => 'manager', 'name' => 'Менеджер']
        ];
        foreach ($roles as $item) {
            $role = new Role();
            $role->name = $item['name'];
            $role->slug = $item['slug'];
            $role->save();
        }
    }
}
