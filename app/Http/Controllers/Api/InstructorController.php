<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Resources\InstructorResource;
use App\Http\Resources\InstructorCollection;

class InstructorController extends Controller
{
    /**
     * عرض قائمة بأسماء أعضاء هيئة التدريس النشطين.
     * حسب الملاحظة: "عرض أسماء أعضاء الهيئة التدريسية فقط، لا يتم ربطهم بالمقررات بشكل مباشر في هذه الواجهة"
     */
    public function index()
    {
        // جلب المدرسين النشطين فقط مع تحديد الأعمدة المطلوبة (الاسم)
        $instructors = Instructor::where('is_active', true)
                                ->select('id', 'name_ar', 'name_en', 'title') // تحديد الأعمدة
                                ->get();
        return new InstructorCollection($instructors);
    }

    /**
     * (اختياري) عرض تفاصيل مدرس معين إذا كان هناك صفحة لملفه الشخصي.
     */
    public function show(Instructor $instructor)
    {
        if (!$instructor->is_active) {
            return response()->json(['message' => 'Instructor not found or not active.'], 404);
        }
        // تحميل المقررات التي يدرسها حاليًا إذا أردت عرضها هنا
        // $instructor->load('courseAssignments.course');
        return new InstructorResource($instructor);
    }
}