<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project; // اسم النموذج لالمشاريع
use App\Models\Specialization;
use App\Models\Instructor;
use App\Http\Requests\Admin\StoreProjectRequest; // ستحتاج لإنشاء هذا
use App\Http\Requests\Admin\UpdateProjectRequest; // ستحتاج لإنشاء هذا
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    public function index(Request $request)
    {
        $query = Project::with(['specialization', 'supervisor'])->latest();

        if ($request->filled('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('student_names', 'like', "%{$search}%")
                  ->orWhere('keywords', 'like', "%{$search}%");
            });
        }

        $projects = $query->paginate(15);
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        $years = Project::distinct()->orderBy('year', 'desc')->pluck('year'); // لجلب السنوات المتاحة للفلترة
        $semesters = ['الخريف', 'الربيع']; // أو جلبها من قاعدة البيانات إذا كانت ديناميكية

        return view('admin.projects.index', compact('projects', 'specializations', 'years', 'semesters'));
    }

    public function create()
    {
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        $instructors = Instructor::where('is_active', true)->orderBy('name_ar')->get(['id', 'name_ar']);
        $semesters = ['الخريف', 'الربيع'];
        return view('admin.projects.create', compact('specializations', 'instructors', 'semesters'));
    }

    public function store(StoreProjectRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['created_by_admin_id'] = Auth::guard('admin_web')->id();
        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        Project::create($validatedData);

        return redirect()->route('admin.projects.index')
                         ->with('success', 'تم إضافة مشروع التخرج بنجاح.');
    }

    public function show(Project $project)
    {
        $project->load(['specialization', 'supervisor', 'createdByAdmin', 'lastUpdatedByAdmin']);
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        $instructors = Instructor::where('is_active', true)->orderBy('name_ar')->get(['id', 'name_ar']);
        $semesters = ['الخريف', 'الربيع'];
        return view('admin.projects.edit', compact('project', 'specializations', 'instructors', 'semesters'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validatedData = $request->validated();
        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        $project->update($validatedData);
        return redirect()->route('admin.projects.index')
                         ->with('success', 'تم تحديث مشروع التخرج بنجاح.');
    }

    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->route('admin.projects.index')
                             ->with('success', 'تم حذف مشروع التخرج بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('admin.projects.index')
                             ->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }
}