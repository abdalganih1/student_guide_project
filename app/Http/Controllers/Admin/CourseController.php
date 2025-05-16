<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Specialization;
// use App\Models\Instructor; // لم نعد بحاجة إليه هنا بشكل مباشر لإدارة التعيينات
use App\Models\CourseResource;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB; // لم نعد بحاجة إليه لإدارة التعيينات هنا

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    public function index(Request $request)
    {
        $query = Course::with('specialization')->latest();
        if ($request->filled('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }
        $courses = $query->paginate(15);
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.courses.index', compact('courses', 'specializations'));
    }

    public function create()
    {
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.courses.create', compact('specializations'));
    }

    public function store(StoreCourseRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['created_by_admin_id'] = Auth::guard('admin_web')->id();
        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        Course::create($validatedData);
        return redirect()->route('admin.courses.index')
                         ->with('success', 'تم إنشاء المقرر بنجاح.');
    }

    public function show(Course $course)
    {
        // تم إزالة 'instructors' من هنا
        // وتم إزالة 'availableInstructors'
        $course->load([
            'specialization',
            'resources' => function ($query) {
                 $query->orderBy('created_at', 'desc');
            },
            'createdByAdmin',
            'lastUpdatedByAdmin'
        ]);

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.courses.edit', compact('course', 'specializations'));
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $validatedData = $request->validated();
        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        $course->update($validatedData);
        return redirect()->route('admin.courses.index')
                         ->with('success', 'تم تحديث المقرر بنجاح.');
    }

    public function destroy(Course $course)
    {
        // تم إزالة التحقق من 'instructors'
        if ($course->resources()->exists() || $course->enrolledStudents()->exists()) {
             return redirect()->route('admin.courses.index')
                              ->with('error', 'لا يمكن حذف المقرر لوجود موارد أو طلاب مسجلين مرتبطين به.');
        }
        try {
            $course->delete();
            return redirect()->route('admin.courses.index')
                             ->with('success', 'تم حذف المقرر بنجاح.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.courses.index')
                             ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }

    // --- Additional methods for managing resources ---

    public function addResource(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'url' => 'required|string|max:512',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'semester_relevance' => 'nullable|string|max:50',
        ]);

        $validated['uploaded_by_admin_id'] = Auth::guard('admin_web')->id();

        $course->resources()->create($validated);

        return redirect()->route('admin.courses.show', $course)
                         ->with('success', 'تمت إضافة المورد بنجاح.');
    }

    public function removeResource(Course $course, CourseResource $resource)
    {
        $resource->delete();
        return redirect()->route('admin.courses.show', $course)
                         ->with('success', 'تم حذف المورد بنجاح.');
    }

    // --- تم حذف دوال assignInstructor و removeAssignment ---
}