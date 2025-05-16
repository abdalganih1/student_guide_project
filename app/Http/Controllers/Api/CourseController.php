<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Specialization;
use App\Models\StudentCourseEnrollment;

use Illuminate\Http\Request;
use App\Http\Resources\CourseResource; // ستحتاج لإنشاء هذا الـ Resource
use App\Http\Resources\CourseCollection;
use App\Http\Resources\StudentCourseEnrollmentResource;


use Illuminate\Support\Facades\Auth; // <<-- يجب أن يكون هذا موجوداً
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
    // في ApiCourseController.php
    public function getCoursesByYearLevel(Request $request, int $yearLevel)
    {
        $query = Course::where('year_level', $yearLevel);

        // يمكنك إضافة فلاتر إضافية هنا إذا أردت، مثل الاختصاص
        if ($request->has('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }
        // يمكنك أيضًا إضافة شروط للتحقق من أن المقرر متاح للعرض
        // $query->where('is_enrollable', true); // أو أي شروط أخرى

        $courses = $query->with('specialization')->get();
        return new CourseCollection($courses);
    }
        /**
     * تسجيل الطالب المسجل دخوله في مقرر معين.
     * هذا التسجيل فوري ولا يتطلب موافقة مدير.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function enroll(Request $request, Course $course)
    {
        $student = Auth::guard('sanctum')->user(); // الطالب المسجل دخوله حاليًا

        // 1. التحقق مما إذا كان المقرر متاحاً للتسجيل حالياً
        if (!$course->is_enrollable) {
            return response()->json(['message' => 'This course is not currently available for enrollment.'], 400); // Bad Request
        }

        // 2. التحقق من سعة التسجيل (إذا كانت محددة)
        if ($course->enrollment_capacity) {
            $currentEnrollmentsCount = StudentCourseEnrollment::where('course_id', $course->id)
                                         ->whereIn('status', ['enrolled', 'completed']) // أو الحالات التي تعتبر 'مشغولة'
                                         ->count();
            if ($currentEnrollmentsCount >= $course->enrollment_capacity) {
                return response()->json(['message' => 'This course has reached its maximum enrollment capacity.'], 400);
            }
        }

        // 3. التحقق مما إذا كان الطالب مسجلاً بالفعل في هذا المقرر في الفصل الحالي
        // ستحتاج إلى طريقة لتحديد "الفصل الحالي" ديناميكياً في تطبيقك أو إرساله من التطبيق
        // كمثال، سنفترض أن الفصل الحالي هو "الخريف 2024"
        $currentSemester = 'الخريف 2024'; // <<< يجب استبدال هذا بمنطق ديناميكي أو قيمة مرسلة

        $existingEnrollment = StudentCourseEnrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('semester_enrolled', $currentSemester) // التحقق من الفصل
            ->first();

        if ($existingEnrollment) {
            // إذا كان مسجلاً بالفعل بنفس الحالة (مثل enrolled)
            if ($existingEnrollment->status == 'enrolled' || $existingEnrollment->status == 'completed') {
                 return response()->json(['message' => 'You are already enrolled in this course for the current semester.'], 409); // Conflict
            }
            // إذا كان لديه تسجيل سابق بحالة أخرى (مثل dropped أو failed) وتريد السماح بإعادة التسجيل
            // يمكنك تحديث السجل الحالي بدلاً من إنشاء سجل جديد
            // $existingEnrollment->status = 'enrolled';
            // $existingEnrollment->registration_datetime = now();
            // $existingEnrollment->save();
            // return response()->json(['message' => 'Your enrollment has been reactivated.', 'enrollment' => new StudentCourseEnrollmentResource($existingEnrollment)], 200);

             // إذا كنت لا تسمح بإعادة التسجيل بسهولة، فقط ارجع خطأ
             return response()->json(['message' => 'You have a previous record for this course in the current semester.'], 409);
        }

        // 4. إنشاء سجل تسجيل جديد بحالة 'enrolled' مباشرة
        try {
            $enrollment = StudentCourseEnrollment::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'registration_datetime' => now(), // <--- You are setting registration_datetime here
                'semester_enrolled' => $currentSemester, // استخدام قيمة الفصل الحالي
                'status' => 'enrolled', // الحالة هنا هي 'مسجل' مباشرة
                'attended' => false, // حالة الحضور افتراضياً false
                'notes' => null, // لا يوجد ملاحظات مبدئياً من الطالب
            ]);

            // يمكنك إرسال إشعار للطالب بتأكيد التسجيل هنا (اختياري)

            return response()->json([ // <<< هذه هي استجابة النجاح
            'message' => 'Successfully enrolled in the course.',
            'enrollment' => new StudentCourseEnrollmentResource($enrollment),
        ], 201);
        } catch (\Exception $e) {
            // معالجة أي أخطاء أخرى قد تحدث أثناء الحفظ
            return response()->json(['message' => 'Failed to enroll in the course.', 'error' => $e->getMessage()], 500); // Internal Server Error
        }
    }
}