<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Specialization;
use App\Models\Instructor;
use App\Models\CourseResource;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For course_instructor_assignments

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
        // Load relationships for display: specialization, instructors assigned, resources, admin creators
        $course->load([
            'specialization',
            'instructors' => function ($query) { // Get instructors assigned in any semester
                 $query->orderBy('name_ar');
            },
            'resources' => function ($query) {
                 $query->orderBy('created_at', 'desc');
            },
            'createdByAdmin',
            'lastUpdatedByAdmin'
        ]);
        // Also load instructors available for assignment form
        $availableInstructors = Instructor::where('is_active', true)->orderBy('name_ar')->get();

        return view('admin.courses.show', compact('course', 'availableInstructors'));
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
        if ($course->resources()->exists() || $course->enrolledStudents()->exists() || $course->instructors()->exists()) {
             return redirect()->route('admin.courses.index')
                              ->with('error', 'لا يمكن حذف المقرر لوجود موارد أو طلاب مسجلين أو مدرسين معينين مرتبطين به.');
        }
        try {
            // Delete related assignments manually if needed or rely on cascade (check migration)
            // DB::table('course_instructor_assignments')->where('course_id', $course->id)->delete();
            $course->delete();
            return redirect()->route('admin.courses.index')
                             ->with('success', 'تم حذف المقرر بنجاح.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.courses.index')
                             ->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }

    // --- Additional methods for managing resources and assignments ---

    /**
     * Add a resource to a course.
     */
    public function addResource(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'url' => 'required|string|max:512', // Consider 'file' type validation if uploading
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'semester_relevance' => 'nullable|string|max:50',
        ]);

        $validated['uploaded_by_admin_id'] = Auth::guard('admin_web')->id();

        $course->resources()->create($validated);

        return redirect()->route('admin.courses.show', $course)
                         ->with('success', 'تمت إضافة المورد بنجاح.');
    }

    /**
     * Remove a resource from a course.
     */
    public function removeResource(Course $course, CourseResource $resource)
    {
        // Optional: Add authorization check if needed
        $resource->delete(); // Assumes CourseResource model exists
        return redirect()->route('admin.courses.show', $course)
                         ->with('success', 'تم حذف المورد بنجاح.');
    }

    /**
     * Assign an instructor to a course for a specific semester.
     */
    public function assignInstructor(Request $request, Course $course)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'semester_of_assignment' => 'required|string|max:50',
            'role_in_course' => 'nullable|string|max:50',
        ]);

        // Check if assignment already exists
        $exists = DB::table('course_instructor_assignments')
                      ->where('course_id', $course->id)
                      ->where('instructor_id', $validated['instructor_id'])
                      ->where('semester_of_assignment', $validated['semester_of_assignment'])
                      ->exists();

        if ($exists) {
             return redirect()->route('admin.courses.show', $course)
                              ->with('error', 'المدرس معين بالفعل لهذا المقرر في هذا الفصل.');
        }

        DB::table('course_instructor_assignments')->insert([
            'course_id' => $course->id,
            'instructor_id' => $validated['instructor_id'],
            'semester_of_assignment' => $validated['semester_of_assignment'],
            'role_in_course' => $validated['role_in_course'] ?? 'Lecturer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.courses.show', $course)
                         ->with('success', 'تم تعيين المدرس للمقرر بنجاح.');
    }

     /**
     * Remove an instructor assignment from a course.
     */
    public function removeAssignment(Course $course, Request $request)
    {
         $validated = $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'semester_of_assignment' => 'required|string|max:50',
        ]);

        DB::table('course_instructor_assignments')
              ->where('course_id', $course->id)
              ->where('instructor_id', $validated['instructor_id'])
              ->where('semester_of_assignment', $validated['semester_of_assignment'])
              ->delete();

         return redirect()->route('admin.courses.show', $course)
                         ->with('success', 'تم حذف تعيين المدرس بنجاح.');
    }
}