@extends('admin.layouts.app')

@section('title', 'تفاصيل الوسيط: ' . ($universityFacility->title_ar ?: 'وسيط #' . $universityFacility->id) )

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-eye me-2"></i>تفاصيل وسيط جامعي</h1>
        <div>
            <a href="{{ route('admin.university-facilities.edit', $universityFacility) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i> تعديل</a>
            <a href="{{ route('admin.university-facilities.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($universityFacility->file_url)
            <div class="mb-3 text-center">
                @if($universityFacility->media_type == 'image')
                    <img src="{{ Storage::url($universityFacility->file_url) }}" alt="{{ $universityFacility->title_ar ?: 'صورة' }}" class="img-fluid" style="max-height: 400px; border: 1px solid #ddd; padding: 5px;">
                @elseif($universityFacility->media_type == 'video')
                    <video controls width="100%" style="max-width: 600px;">
                        <source src="{{ Storage::url($universityFacility->file_url) }}" type="{{ Storage::mimeType($universityFacility->file_url) }}">
                        متصفحك لا يدعم عرض الفيديو.
                    </video>
                @elseif($universityFacility->media_type == 'document')
                    <p><a href="{{ Storage::url($universityFacility->file_url) }}" target="_blank" class="btn btn-info"><i class="fas fa-download me-1"></i> تحميل المستند ({{ basename($universityFacility->file_url) }})</a></p>
                @endif
            </div>
            @endif

            <dl class="row">
                <dt class="col-sm-3">المعرف:</dt>
                <dd class="col-sm-9">{{ $universityFacility->id }}</dd>

                <dt class="col-sm-3">العنوان (عربي):</dt>
                <dd class="col-sm-9">{{ $universityFacility->title_ar ?: '-' }}</dd>

                <dt class="col-sm-3">العنوان (إنجليزي):</dt>
                <dd class="col-sm-9">{{ $universityFacility->title_en ?: '-' }}</dd>

                <dt class="col-sm-3">نوع الوسيط:</dt>
                <dd class="col-sm-9">{{ $mediaTypes[$universityFacility->media_type] ?? $universityFacility->media_type }}</dd>

                <dt class="col-sm-3">التصنيف:</dt>
                <dd class="col-sm-9">{{ $universityFacility->category ?: '-' }}</dd>

                <dt class="col-sm-3">الكلية المرتبطة:</dt>
                <dd class="col-sm-9">{{ $universityFacility->faculty->name_ar ?? '-' }}</dd>

                <dt class="col-sm-3">الوصف (عربي):</dt>
                <dd class="col-sm-9">{{ $universityFacility->description_ar ?: '-' }}</dd>

                <dt class="col-sm-3">الوصف (إنجليزي):</dt>
                <dd class="col-sm-9">{{ $universityFacility->description_en ?: '-' }}</dd>

                <dt class="col-sm-3">رُفع بواسطة:</dt>
                <dd class="col-sm-9">{{ $universityFacility->uploadedByAdmin->username ?? '-' }}</dd>

                <dt class="col-sm-3">تاريخ الرفع:</dt>
                <dd class="col-sm-9">{{ $universityFacility->created_at->translatedFormat('l, d F Y H:i') }}</dd>

                <dt class="col-sm-3">آخر تحديث:</dt>
                <dd class="col-sm-9">{{ $universityFacility->updated_at->translatedFormat('l, d F Y H:i') }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection