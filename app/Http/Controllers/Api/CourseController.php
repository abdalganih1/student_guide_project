<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Specialization;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource; // ستحتاج لإنشاء هذا الـ Resource
use App\Http\Resources\CourseCollection;

class CourseController extends Controller
{
    /**
     * عرض قائمة بجميع المقررات (يمكن فلترتها حسب الاختصاص).
     */
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->has('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }
        // يمكنك إضافة المزيد من الفلاتر هنا (مثل الفصل الدراسي، السنة)

        // جلب المقررات التي يمكن للطالب التسجيل فيها أو المنشورة
        // $query->where('is_enrollable', true); // أو أي شرط آخر للحالة

        $courses = $query->with('specialization')->get(); // تحميل معلومات الاختصاص مع كل مقرر
        return new CourseCollection($courses);
    }

    /**
     * عرض تفاصيل مقرر معين مع موارده (ملفات PDF، أسئلة الدورات).
     */
    public function show(Course $course)
    {
        // يمكنك إضافة شروط هنا للتأكد من أن المقرر متاح للعرض
        $course->load(['resources', 'instructors' => function ($query) {
            // جلب المدرسين المعينين لهذا المقرر لفصل معين (إذا لزم الأمر)
            // $query->wherePivot('semester_of_assignment', 'الفصل الحالي');
        }]); // افترض وجود علاقات 'resources' و 'instructors' في نموذج Course
        return new CourseResource($course);
    }

    /**
     * عرض المقررات الخاصة باختصاص معين.
     * هذه دالة بديلة إذا كنت تفضل مسارًا مخصصًا.
     */
    public function getCoursesBySpecialization(Specialization $specialization)
    {
        // تأكد أن الاختصاص منشور
        if ($specialization->status !== 'published') {
            return response()->json(['message' => 'Specialization not found or not published.'], 404);
        }
        $courses = $specialization->courses()->get(); // جلب المقررات المنشورة فقط
        return new CourseCollection($courses);
    }
}