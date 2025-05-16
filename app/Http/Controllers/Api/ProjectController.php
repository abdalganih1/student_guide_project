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
}