<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityMedia;
use App\Models\Faculty;
use App\Http\Requests\Admin\StoreUniversityFacilityRequest; // ستحتاج لإنشاء هذا
use App\Http\Requests\Admin\UpdateUniversityFacilityRequest; // ستحتاج لإنشاء هذا
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UniversityFacilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    public function index(Request $request)
    {
        $query = UniversityMedia::with(['faculty', 'uploadedByAdmin'])->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('media_type')) {
            $query->where('media_type', $request->media_type);
        }
        if ($request->filled('faculty_id')) {
            $query->where('faculty_id', $request->faculty_id);
        }

        $mediaItems = $query->paginate(15);
        $categories = UniversityMedia::distinct()->pluck('category')->filter()->sort();
        $mediaTypes = ['image' => 'صورة', 'video' => 'فيديو', 'document' => 'مستند'];
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);

        return view('admin.university_facilities.index', compact('mediaItems', 'categories', 'mediaTypes', 'faculties'));
    }

    public function create()
    {
        $categories = UniversityMedia::distinct()->pluck('category')->filter()->sort();
        $mediaTypes = ['image' => 'صورة', 'video' => 'فيديو', 'document' => 'مستند'];
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.university_facilities.create', compact('categories', 'mediaTypes', 'faculties'));
    }

    public function store(StoreUniversityFacilityRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('media_file')) {
            $path = $request->file('media_file')->store('university_media', 'public');
            $validatedData['file_url'] = $path;
        }
        unset($validatedData['media_file']); // إزالة مفتاح الملف بعد التخزين

        $validatedData['uploaded_by_admin_id'] = Auth::guard('admin_web')->id();

        UniversityMedia::create($validatedData);

        return redirect()->route('admin.university-facilities.index')
                         ->with('success', 'تم إضافة وسيط الجامعة بنجاح.');
    }

    /**
     * Display the specified resource.
     * عادة لا نحتاج لعرض منفصل هنا، يمكن أن يتم ضمن الـ index أو edit
     */
    public function show(UniversityMedia $universityFacility) // اسم المتغير يجب أن يطابق اسم المورد
    {
        $universityFacility->load(['faculty', 'uploadedByAdmin']);
        return view('admin.university_facilities.show', compact('universityFacility'));
    }

    public function edit(UniversityMedia $universityFacility)
    {
        $categories = UniversityMedia::distinct()->pluck('category')->filter()->sort();
        $mediaTypes = ['image' => 'صورة', 'video' => 'فيديو', 'document' => 'مستند'];
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.university_facilities.edit', compact('universityFacility', 'categories', 'mediaTypes', 'faculties'));
    }

    public function update(UpdateUniversityFacilityRequest $request, UniversityMedia $universityFacility)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('media_file')) {
            // حذف الملف القديم إذا كان موجودًا
            if ($universityFacility->file_url) {
                Storage::disk('public')->delete($universityFacility->file_url);
            }
            $path = $request->file('media_file')->store('university_media', 'public');
            $validatedData['file_url'] = $path;
        }
        unset($validatedData['media_file']);

        $universityFacility->update($validatedData);

        return redirect()->route('admin.university-facilities.index')
                         ->with('success', 'تم تحديث وسيط الجامعة بنجاح.');
    }

    public function destroy(UniversityMedia $universityFacility)
    {
        try {
            if ($universityFacility->file_url) {
                Storage::disk('public')->delete($universityFacility->file_url);
            }
            $universityFacility->delete();
            return redirect()->route('admin.university-facilities.index')
                             ->with('success', 'تم حذف وسيط الجامعة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('admin.university-facilities.index')
                             ->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }
}