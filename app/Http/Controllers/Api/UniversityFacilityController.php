<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UniversityMedia;
use Illuminate\Http\Request;
use App\Http\Resources\UniversityMediaResource;
use App\Http\Resources\UniversityMediaCollection;

class UniversityFacilityController extends Controller
{
    /**
     * عرض صور وفيديوهات المرافق الجامعية مع إمكانية التصنيف.
     * "عرض صور المرافق الجامعية (الكليات وغيرها)"
     */
    public function index(Request $request)
    {
        $query = UniversityMedia::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('media_type')) {
            $query->where('media_type', $request->media_type); // 'image' or 'video'
        }
        // يمكنك إضافة فلتر حسب الكلية faculty_id إذا أردت

        $media = $query->orderBy('created_at', 'desc')->get();
        return new UniversityMediaCollection($media);
    }

    /**
     * عرض تفاصيل وسيط معين (صورة أو فيديو).
     */
    public function show(UniversityMedia $universityMedia)
    {
        return new UniversityMediaResource($universityMedia);
    }
}