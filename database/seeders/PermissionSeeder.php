<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'students.view', 'display_name' => 'عرض الطلاب', 'group' => 'students'],
            ['name' => 'students.create', 'display_name' => 'إضافة طالب', 'group' => 'students'],
            ['name' => 'students.edit', 'display_name' => 'تعديل طالب', 'group' => 'students'],
            ['name' => 'students.delete', 'display_name' => 'حذف طالب', 'group' => 'students'],
            
            ['name' => 'attendance.view', 'display_name' => 'عرض الحضور', 'group' => 'attendance'],
            ['name' => 'attendance.mark', 'display_name' => 'تسجيل الحضور', 'group' => 'attendance'],
            ['name' => 'attendance.edit', 'display_name' => 'تعديل الحضور', 'group' => 'attendance'],
            
            ['name' => 'roles.view', 'display_name' => 'عرض الأدوار', 'group' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'إضافة دور', 'group' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'تعديل دور', 'group' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'حذف دور', 'group' => 'roles'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}