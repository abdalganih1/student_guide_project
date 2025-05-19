<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Specialization;
use App\Models\Instructor;
use App\Http\Requests\Admin\StoreProjectRequest; // ستحتاج لتعديله
use App\Http\Requests\Admin\UpdateProjectRequest; // ستحتاج لتعديله
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // إذا كنت ستستخدم DB لإدارة الربط يدوياً

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    public function index(Request $request)
    {
        // تحميل علاقة specializations (متعدد لمتعدد) بدلاً من specialization (واحد لواحد)
        $query = Project::with(['specializations', 'supervisor'])->latest();

        if ($request->filled('specialization_id')) {
            // للفلترة حسب اختصاص واحد في علاقة متعدد لمتعدد
            $query->whereHas('specializations', function ($q) use ($request) {
                $q->where('specializations.id', $request->specialization_id);
            });
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
        $years = Project::distinct()->orderBy('year', 'desc')->pluck('year');
        $semesters = ['الخريف', 'الربيع'];

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

        // فصل specialized_ids عن بقية البيانات
        $specializationIds = $validatedData['specialization_ids'];
        unset($validatedData['specialization_ids']);

        $validatedData['created_by_admin_id'] = Auth::guard('admin_web')->id();
        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        // إنشاء المشروع
        $project = Project::create($validatedData);

        // ربط المشروع بالتخصصات في جدول الربط
        $project->specializations()->attach($specializationIds);

        return redirect()->route('admin.projects.index')
                         ->with('success', 'تم إضافة مشروع التخرج بنجاح.');
    }

    public function show(Project $project)
    {
        // تحميل علاقة specializations (متعدد لمتعدد)
        $project->load(['specializations', 'supervisor', 'createdByAdmin', 'lastUpdatedByAdmin']);
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $specializations = Specialization::orderBy('name_ar')->get(['id', 'name_ar']);
        $instructors = Instructor::where('is_active', true)->orderBy('name_ar')->get(['id', 'name_ar']);
        $semesters = ['الخريف', 'الربيع'];

        // جلب IDs التخصصات المرتبطة حالياً بالمشروع
        $currentSpecializationIds = $project->specializations->pluck('id')->toArray();

        return view('admin.projects.edit', compact('project', 'specializations', 'instructors', 'semesters', 'currentSpecializationIds'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validatedData = $request->validated();

        // فصل specialized_ids عن بقية البيانات
        $specializationIds = $validatedData['specialization_ids'];
        unset($validatedData['specialization_ids']);

        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        // تحديث بيانات المشروع الرئيسية
        $project->update($validatedData);

        // تحديث علاقة المشاريع بالاختصاصات في جدول الربط
        $project->specializations()->sync($specializationIds); // sync تقوم بحذف وإضافة الروابط لتطابق المصفوفة الجديدة

        return redirect()->route('admin.projects.index')
                         ->with('success', 'تم تحديث مشروع التخرج بنجاح.');
    }

    public function destroy(Project $project)
    {
        // حذف الروابط في جدول الربط تلقائياً بفضل onDelete('cascade')
        // أو يمكنك حذفها يدوياً قبل حذف المشروع:
        // $project->specializations()->detach();

        try {
            $project->delete();
            return redirect()->route('admin.projects.index')
                             ->with('success', 'تم حذف مشروع التخرج بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('admin.projects.index')
                             ->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}