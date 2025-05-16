@extends('admin.layouts.app')

@section('title', 'تفاصيل مشروع التخرج: ' . Str::limit($project->title_ar, 30))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-eye me-2"></i>تفاصيل مشروع: {{ $project->title_ar }}</h1>
        <div>
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i> تعديل</a>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <h3>{{ $project->title_ar }}</h3>
                    @if($project->title_en)
                        <h5 class="text-muted">{{ $project->title_en }}</h5>
                    @endif
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="mb-0"><strong>السنة:</strong> {{ $project->year }}</p>
                    <p class="mb-0"><strong>الفصل:</strong> {{ $project->semester }}</p>
                </div>
            </div>
            <hr>
            <dl class="row">
                <dt class="col-sm-3">الاختصاص:</dt>
                <dd class="col-sm-9"><a href="{{ route('admin.specializations.show', $project->specialization) }}">{{ $project->specialization->name_ar ?? 'غير محدد' }}</a></dd>

                <dt class="col-sm-3">المشرف:</dt>
                <dd class="col-sm-9">{{ $project->supervisor ? $project->supervisor->name_ar : 'لا يوجد' }}</dd>

                <dt class="col-sm-3">أسماء الطلاب:</dt>
                <dd class="col-sm-9">{{ $project->student_names ?: '-' }}</dd>

                <dt class="col-sm-3">نوع المشروع:</dt>
                <dd class="col-sm-9">{{ $project->project_type ?: '-' }}</dd>

                <dt class="col-sm-3">الكلمات المفتاحية:</dt>
                <dd class="col-sm-9">{{ $project->keywords ?: '-' }}</dd>
            </dl>

            @if($project->abstract_ar)
            <hr>
            <h5>الملخص (عربي):</h5>
            <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $project->abstract_ar }}</div>
            @endif

            @if($project->abstract_en)
            <hr>
            <h5>الملخص (إنجليزي):</h5>
            <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $project->abstract_en }}</div>
            @endif
            <hr>
            <small class="text-muted">
                تم إنشاؤه بواسطة: {{ $project->createdByAdmin->name_ar ?? 'غير معروف' }} ({{ $project->createdByAdmin->username ?? '' }}) في {{ $project->created_at->translatedFormat('Y-m-d') }} <br>
                آخر تحديث بواسطة: {{ $project->lastUpdatedByAdmin->name_ar ?? 'غير معروف' }} ({{ $project->lastUpdatedByAdmin->username ?? '' }}) في {{ $project->updated_at->translatedFormat('Y-m-d') }}
            </small>
        </div>
    </div>
</div>
@endsection