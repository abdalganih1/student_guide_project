<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project; // اسم النموذج هو Project
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;

class ProjectController extends Controller
{
    /**
     * عرض أرشيف المشاريع مع إمكانية الفلترة.
     * "عرض عنوان المشروع، الاختصاص، سنة التقديم، تحديد الفصل الدراسي (أول/ثاني) الذي قدم فيه المشروع."
     * "مع تحديث الفصل" -> يعني أن البيانات المعروضة يجب أن تكون محدثة.
     */
    public function index(Request $request)
    {
        $query = Project::query()->with(['specialization', 'supervisor']); // تحميل العلاقات

        if ($request->has('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }
        if ($request->has('year')) {
            $query->where('year', $request->year);
        }
        if ($request->has('semester')) {
            $query->where('semester', $request->semester); // "الخريف", "الربيع"
        }
        if ($request->has('keywords')) {
            $keywords = $request->keywords;
            $query->where(function ($q) use ($keywords) {
                $q->where('title_ar', 'like', "%{$keywords}%")
                  ->orWhere('title_en', 'like', "%{$keywords}%")
                  ->orWhere('keywords', 'like', "%{$keywords}%");
            });
        }

        $projects = $query->orderBy('year', 'desc')->orderBy('semester', 'asc')->get();
        return new ProjectCollection($projects);
    }

    /**
     * عرض تفاصيل مشروع تخرج معين (إذا لزم الأمر).
     */
    public function show(Project $project)
    {
        $project->load(['specialization', 'supervisor']);
        return new ProjectResource($project);
    }
    // في ApiProjectController.php
    public function filterProjects(Request $request) // أو عدّل دالة index لتقبل كل هذه البارامترات
    {
        $query = Project::query()->with(['specialization', 'supervisor']);

        if ($request->filled('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        if ($request->filled('keywords')) {
            $keywords = $request->keywords;
            $query->where(function ($q) use ($keywords) {
                $q->where('title_ar', 'like', "%{$keywords}%")
                ->orWhere('title_en', 'like', "%{$keywords}%")
                ->orWhere('student_names', 'like', "%{$keywords}%") // إذا كان student_names نصيًا
                ->orWhere('keywords', 'like', "%{$keywords}%");
            });
        }
        // أضف المزيد من الفلاتر حسب الحاجة

        $projects = $query->orderBy('year', 'desc')->orderBy('semester', 'asc')->paginate(15); // استخدام paginate
        return new ProjectCollection($projects);
    }
}