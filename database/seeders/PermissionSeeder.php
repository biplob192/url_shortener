<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'create'],
            ['name' => 'read'],
            ['name' => 'update'],
            ['name' => 'delete'],
            ['name' => 'create_super_admin'],
            ['name' => 'delete_super_admin'],
        ];

        foreach ($items as $item) {
            Permission::create($item);
        }
    }
}
