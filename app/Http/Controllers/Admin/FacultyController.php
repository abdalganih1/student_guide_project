<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Instructor;
use App\Http\Requests\Admin\StoreFacultyRequest; // Use Form Request
use App\Http\Requests\Admin\UpdateFacultyRequest; // Use Form Request
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
        // Add permission middleware here if needed (e.g., using Spatie Permissions)
        // $this->middleware('can:manage-faculties');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculties = Faculty::with('dean')->latest()->paginate(15);
        return view('admin.faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instructors = Instructor::where('is_active', true)->orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.faculties.create', compact('instructors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacultyRequest $request) // Use Form Request for validation
    {
        Faculty::create($request->validated());
        return redirect()->route('admin.faculties.index')
                         ->with('success', 'تم إنشاء الكلية بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faculty $faculty)
    {
        $faculty->load('dean', 'specializations', 'instructors'); // Eager load relations
        return view('admin.faculties.show', compact('faculty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faculty $faculty)
    {
        $instructors = Instructor::where('is_active', true)->orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.faculties.edit', compact('faculty', 'instructors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacultyRequest $request, Faculty $faculty) // Use Form Request
    {
        $faculty->update($request->validated());
        return redirect()->route('admin.faculties.index')
                         ->with('success', 'تم تحديث الكلية بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        // Basic check for related items before deleting
        if ($faculty->specializations()->exists() || $faculty->instructors()->exists()) {
             return redirect()->route('admin.faculties.index')
                              ->with('error', 'لا يمكن حذف الكلية لوجود اختصاصات أو مدرسين مرتبطين بها.');
        }

        try {
            $faculty->delete();
            return redirect()->route('admin.faculties.index')
                             ->with('success', 'تم حذف الكلية بنجاح.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch potential foreign key constraint errors if the check above fails
            return redirect()->route('admin.faculties.index')
                             ->with('error', 'حدث خطأ أثناء الحذف. قد تكون الكلية مرتبطة ببيانات أخرى.');
        }
    }
}