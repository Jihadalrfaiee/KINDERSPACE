<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'مدير النظام',
            'description' => 'صلاحيات كاملة',
            'is_active' => true
        ]);
        $admin->permissions()->attach(Permission::all());

        $teacher = Role::create([
            'name' => 'teacher',
            'display_name' => 'معلمة',
            'description' => 'متابعة الطلاب',
            'is_active' => true
        ]);
    }
}