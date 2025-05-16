<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Faculty;
use App\Http\Requests\Admin\StoreInstructorRequest;
use App\Http\Requests\Admin\UpdateInstructorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    public function index(Request $request)
    {
        $query = Instructor::with('faculty')->latest();
         if ($request->filled('faculty_id')) {
            $query->where('faculty_id', $request->faculty_id);
        }
        $instructors = $query->paginate(15);
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.instructors.index', compact('instructors', 'faculties'));
    }

    public function create()
    {
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.instructors.create', compact('faculties'));
    }

    public function store(StoreInstructorRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('instructors', 'public');
            $validatedData['profile_picture_url'] = $path;
        } else {
             $validatedData['profile_picture_url'] = null;
        }

        Instructor::create($validatedData);
        return redirect()->route('admin.instructors.index')
                         ->with('success', 'تم إضافة المدرس بنجاح.');
    }

    public function show(Instructor $instructor)
    {
        // تم إزالة 'courseAssignments.course' من هنا
        $instructor->load(['faculty', 'supervisedProjects']);
        return view('admin.instructors.show', compact('instructor'));
    }

    public function edit(Instructor $instructor)
    {
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.instructors.edit', compact('instructor', 'faculties'));
    }

    public function update(UpdateInstructorRequest $request, Instructor $instructor)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('profile_picture')) {
            if ($instructor->profile_picture_url) {
                Storage::disk('public')->delete($instructor->profile_picture_url);
            }
            $path = $request->file('profile_picture')->store('instructors', 'public');
            $validatedData['profile_picture_url'] = $path;
        } elseif ($request->boolean('remove_profile_picture') && $instructor->profile_picture_url) { // تعديل هنا لإزالة الصورة فقط إذا تم تحديد الخيار
            Storage::disk('public')->delete($instructor->profile_picture_url);
            $validatedData['profile_picture_url'] = null;
        }


        $instructor->update($validatedData);
        return redirect()->route('admin.instructors.index')
                         ->with('success', 'تم تحديث بيانات المدرس بنجاح.');
    }

    public function destroy(Instructor $instructor)
    {
        // تم إزالة التحقق من 'courseAssignments'
        if ($instructor->deanOfFaculty()->exists() || $instructor->supervisedProjects()->exists()) {
             return redirect()->route('admin.instructors.index')
                              ->with('error', 'لا يمكن حذف المدرس لوجود ارتباطات (عميد، مشرف مشاريع).');
        }

        try {
            if ($instructor->profile_picture_url) {
                Storage::disk('public')->delete($instructor->profile_picture_url);
            }
            $instructor->delete();
            return redirect()->route('admin.instructors.index')
                             ->with('success', 'تم حذف المدرس بنجاح.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.instructors.index')
                             ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage()); // إضافة رسالة الخطأ من الاستثناء
        }
    }
}