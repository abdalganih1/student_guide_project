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
            // Store the image in 'public/instructors' directory
            $path = $request->file('profile_picture')->store('instructors', 'public');
            $validatedData['profile_picture_url'] = $path;
        } else {
             $validatedData['profile_picture_url'] = null; // Ensure it's null if no file
        }


        Instructor::create($validatedData);
        return redirect()->route('admin.instructors.index')
                         ->with('success', 'تم إضافة المدرس بنجاح.');
    }

    public function show(Instructor $instructor)
    {
        $instructor->load(['faculty', 'supervisedProjects', 'courseAssignments.course']);
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
            // Delete old image if it exists
            if ($instructor->profile_picture_url) {
                Storage::disk('public')->delete($instructor->profile_picture_url);
            }
            // Store the new image
            $path = $request->file('profile_picture')->store('instructors', 'public');
            $validatedData['profile_picture_url'] = $path;
        }
        // If no new image, keep the old one (don't set to null unless intended)
        // To remove image without uploading new one, you'd need separate logic/checkbox

        $instructor->update($validatedData);
        return redirect()->route('admin.instructors.index')
                         ->with('success', 'تم تحديث بيانات المدرس بنجاح.');
    }

    public function destroy(Instructor $instructor)
    {
        // Check for relationships (Dean, Supervisor, Course Assignments)
        if ($instructor->deanOfFaculty()->exists() || $instructor->supervisedProjects()->exists() || $instructor->courseAssignments()->exists()) {
             return redirect()->route('admin.instructors.index')
                              ->with('error', 'لا يمكن حذف المدرس لوجود ارتباطات (عميد، مشرف، مقررات).');
        }

        try {
             // Delete profile picture from storage if it exists
            if ($instructor->profile_picture_url) {
                Storage::disk('public')->delete($instructor->profile_picture_url);
            }
            $instructor->delete();
            return redirect()->route('admin.instructors.index')
                             ->with('success', 'تم حذف المدرس بنجاح.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.instructors.index')
                             ->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }
}