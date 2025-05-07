<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash; // لاستخدام Hash::make

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::create([
            'username' => 'superadmin',
            'name_ar' => 'المدير العام',
            'name_en' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'password_hash' => Hash::make('password'), // تشفير كلمة المرور
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        AdminUser::create([
            'username' => 'contentmanager',
            'name_ar' => 'مدير المحتوى',
            'name_en' => 'Content Manager',
            'email' => 'contentmanager@example.com',
            'password_hash' => Hash::make('password'),
            'role' => 'content_manager',
            'is_active' => true,
        ]);
    }
}