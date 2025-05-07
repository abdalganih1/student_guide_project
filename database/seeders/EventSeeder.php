<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Faculty;
use App\Models\AdminUser;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $facultyInformatics = Faculty::where('name_en', 'Faculty of Informatics Engineering')->first();
        $admin = AdminUser::where('username', 'contentmanager')->first();

        if ($facultyInformatics && $admin) {
            Event::create([
                'title_ar' => 'ورشة عمل تطوير تطبيقات الموبايل',
                'title_en' => 'Mobile App Development Workshop',
                'description_ar' => 'ورشة عمل عملية لتعلم أساسيات تطوير تطبيقات الموبايل.',
                'event_start_datetime' => now()->addDays(10)->setHour(10)->setMinute(0),
                'event_end_datetime' => now()->addDays(10)->setHour(14)->setMinute(0),
                'location_text' => 'مدرج الكلية الرئيسي',
                'category' => 'Workshop',
                'requires_registration' => true,
                'max_attendees' => 50,
                'organizing_faculty_id' => $facultyInformatics->id,
                'status' => 'scheduled',
                'created_by_admin_id' => $admin->id,
            ]);
        }
    }
}