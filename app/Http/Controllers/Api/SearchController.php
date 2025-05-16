<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Models\Course;
use App\Models\Project;
use App\Models\Instructor;
// ... استيراد النماذج الأخرى التي تريد البحث فيها

class SearchController extends Controller
{
    /**
     * البحث الشامل عن معلومات (اختصاص، مقرر، مشروع، كادر تدريسي...).
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $query = $request->input('query');
        $results = [];

        // البحث في الاختصاصات
        $results['specializations'] = Specialization::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('name_ar', 'like', "%{$query}%")
                  ->orWhere('name_en', 'like', "%{$query}%")
                  ->orWhere('description_ar', 'like', "%{$query}%");
            })->take(5)->get(); // حدد عدد النتائج

        // البحث في المقررات
        $results['courses'] = Course::where(function ($q) use ($query) {
                $q->where('name_ar', 'like', "%{$query}%")
                  ->orWhere('name_en', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%")
                  ->orWhere('description_ar', 'like', "%{$query}%");
            })->take(5)->get();

        // البحث في المشاريع
        $results['projects'] = Project::where(function ($q) use ($query) {
                $q->where('title_ar', 'like', "%{$query}%")
                  ->orWhere('title_en', 'like', "%{$query}%")
                  ->orWhere('keywords', 'like', "%{$query}%");
            })->take(5)->get();

        // البحث في الكادر التدريسي
        $results['instructors'] = Instructor::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name_ar', 'like', "%{$query}%")
                  ->orWhere('name_en', 'like', "%{$query}%")
                  ->orWhere('title', 'like', "%{$query}%");
            })->take(5)->get();

        // يمكنك إضافة البحث في كيانات أخرى مثل الفعاليات، المرافق، إلخ.

        return response()->json($results);
    }
}