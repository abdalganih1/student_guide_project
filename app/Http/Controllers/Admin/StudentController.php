<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Specialization;
use App\Http\Requests\Admin\StoreStudentRequest; // ستحتاج لإنشاء هذا
use App\Http\Requests\Admin\UpdateStudentRequest; // ستحتاج لإنشاء هذا
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    public function index(Request $request)
    {
        $query = Student::with('specialization')->latest();

        if ($request->filled('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }
        if ($request->filled('enrollment_year')) {
            $query->where('enrollment_year', $request->enrollment_year);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name_ar', 'like', "%{$search}%")
                  ->orWhere('full_name_en', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_university_id', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(15);
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        $enrollmentYears = Student::distinct()->orderBy('enrollment_year', 'desc')->pluck('enrollment_year');

        return view('admin.students.index', compact('students', 'specializations', 'enrollmentYears'));
    }

    public function create()
    {
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.students.create', compact('specializations'));
    }

    public function store(StoreStudentRequest $request)
    {
        $validatedData = $request->validated();

        if (!empty($validatedData['password'])) { // إذا تم إدخال كلمة مرور
            $validatedData['password_hash'] = Hash::make($validatedData['password']);
        }
        unset($validatedData['password']); // إزالة كلمة المرور من البيانات قبل الإنشاء إذا كانت فارغة

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('students_profiles', 'public');
            $validatedData['profile_picture_url'] = $path;
        }

        $validatedData['admin_action_by_id'] = Auth::guard('admin_web')->id();
        $validatedData['admin_action_at'] = now();

        Student::create($validatedData);

        return redirect()->route('admin.students.index')
                         ->with('success', 'تم إضافة الطالب بنجاح.');
    }

    public function show(Student $student)
    {
        $student->load(['specialization', 'adminActionBy', 'courseEnrollments.course', 'eventRegistrations.event']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.students.edit', compact('student', 'specializations'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validatedData = $request->validated();

        if (!empty($validatedData['password'])) {
            $student->password_hash = Hash::make($validatedData['password']);
        }
        // لا نزيل كلمة المرور هنا، النموذج سيتجاهل التحديث إذا كان الحقل فارغًا ولم يكن في fillable

        if ($request->hasFile('profile_picture')) {
            if ($student->profile_picture_url) {
                Storage::disk('public')->delete($student->profile_picture_url);
            }
            $path = $request->file('profile_picture')->store('students_profiles', 'public');
            $validatedData['profile_picture_url'] = $path;
        } elseif ($request->boolean('remove_profile_picture')) {
            if ($student->profile_picture_url) {
                Storage::disk('public')->delete($student->profile_picture_url);
            }
            $validatedData['profile_picture_url'] = null;
        }


        $validatedData['admin_action_by_id'] = Auth::guard('admin_web')->id();
        $validatedData['admin_action_at'] = now();
        // إزالة كلمة المرور من مصفوفة التحديث إذا كانت فارغة لتجنب مسح كلمة المرور الحالية
        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }


        $student->update($validatedData);

        return redirect()->route('admin.students.index')
                         ->with('success', 'تم تحديث بيانات الطالب بنجاح.');
    }

    public function destroy(Student $student)
    {
        // تحقق من عدم وجود تسجيلات في مقررات أو فعاليات
        if ($student->courseEnrollments()->exists() || $student->eventRegistrations()->exists()) {
            return redirect()->route('admin.students.index')
                             ->with('error', 'لا يمكن حذف الطالب لوجود تسجيلات مرتبطة به في مقررات أو فعاليات.');
        }
        try {
            if ($student->profile_picture_url) {
                Storage::disk('public')->delete($student->profile_picture_url);
            }
            $student->delete();
            return redirect()->route('admin.students.index')
                             ->with('success', 'تم حذف الطالب بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('admin.students.index')
                             ->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }
}