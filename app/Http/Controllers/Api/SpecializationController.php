<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;
use App\Http\Resources\SpecializationResource; // ستحتاج لإنشاء هذا الـ Resource
use App\Http\Resources\SpecializationCollection;

class SpecializationController extends Controller
{
    /**
     * عرض قائمة بجميع الاختصاصات المنشورة.
     */
    public function index()
    {
        $specializations = Specialization::where('status', 'published')->get();
        return new SpecializationCollection($specializations);
    }

    /**
     * عرض تفاصيل اختصاص معين مع خطته الدراسية (قائمة المقررات).
     */
    public function show(Specialization $specialization)
    {
        // تأكد أن الاختصاص منشور
        if ($specialization->status !== 'published') {
            return response()->json(['message' => 'Specialization not found or not published.'], 404);
        }
        // تحميل المقررات المرتبطة بهذا الاختصاص
        $specialization->load('courses'); // افترض وجود علاقة 'courses' في نموذج Specialization
        return new SpecializationResource($specialization);
    }
}