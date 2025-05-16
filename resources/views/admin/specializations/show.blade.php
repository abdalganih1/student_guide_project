@extends('admin.layouts.app')

@section('title', 'تفاصيل الاختصاص: ' . $specialization->name_ar)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-eye me-2"></i>تفاصيل الاختصاص: {{ $specialization->name_ar }}</h1>
        <div>
            <a href="{{ route('admin.specializations.edit', $specialization) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i> تعديل</a>
            <a href="{{ route('admin.specializations.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>المعرف:</strong> {{ $specialization->id }}</p>
                    <p><strong>الاسم (عربي):</strong> {{ $specialization->name_ar }}</p>
                    <p><strong>الاسم (إنجليزي):</strong> {{ $specialization->name_en ?: '-' }}</p>
                    <p><strong>الكلية:</strong> {{ $specialization->faculty->name_ar ?? 'غير محدد' }}</p>
                    <p><strong>الحالة:</strong>
                        @if($specialization->status == 'published') <span class="badge bg-success">منشور</span>
                        @elseif($specialization->status == 'draft') <span class="badge bg-warning text-dark">مسودة</span>
                        @else <span class="badge bg-secondary">{{ $specialization->status }}</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>تم إنشاؤه بواسطة:</strong> {{ $specialization->createdByAdmin->name_ar ?? '-' }} ({{ $specialization->createdByAdmin->username ?? '' }})</p>
                    <p><strong>تاريخ الإنشاء:</strong> {{ $specialization->created_at->translatedFormat('l, d F Y H:i') }}</p>
                    <p><strong>آخر تحديث بواسطة:</strong> {{ $specialization->lastUpdatedByAdmin->name_ar ?? '-' }} ({{ $specialization->lastUpdatedByAdmin->username ?? '' }})</p>
                    <p><strong>تاريخ آخر تحديث:</strong> {{ $specialization->updated_at->translatedFormat('l, d F Y H:i') }}</p>
                </div>
            </div>
            <hr>
            <div>
                <h5>الوصف (عربي):</h5>
                <p>{{ $specialization->description_ar }}</p>
            </div>
            @if($specialization->description_en)
            <hr>
            <div>
                <h5>الوصف (إنجليزي):</h5>
                <p>{{ $specialization->description_en }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4><i class="fas fa-book me-2"></i>المقررات التابعة لهذا الاختصاص ({{ $specialization->courses->count() }})</h4>
        </div>
        <div class="card-body">
            @if($specialization->courses->isEmpty())
                <p class="text-muted">لا توجد مقررات مرتبطة بهذا الاختصاص حالياً.</p>
            @else
                <ul class="list-group">
                    @foreach($specialization->courses as $course)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.courses.show', $course) }}">{{ $course->name_ar }} ({{ $course->code }})</a>
                            <span class="badge bg-info rounded-pill">{{ $course->semester_display_info }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
             <div class="mt-3">
                <a href="{{ route('admin.courses.create', ['specialization_id' => $specialization->id]) }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-plus me-1"></i> إضافة مقرر جديد لهذا الاختصاص
                </a>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4><i class="fas fa-project-diagram me-2"></i>المشاريع التابعة لهذا الاختصاص ({{ $specialization->projects->count() }})</h4>
        </div>
        <div class="card-body">
            @if($specialization->projects->isEmpty())
                <p class="text-muted">لا توجد مشاريع تخرج مرتبطة بهذا الاختصاص حالياً.</p>
            @else
                <ul class="list-group">
                    @foreach($specialization->projects as $project)
                        <li class="list-group-item">
                            <a href="{{ route('admin.projects.show', $project) }}">{{ $project->title_ar }}</a> - {{ $project->year }}
                        </li>
                    @endforeach
                </ul>
            @endif
            <div class="mt-3">
                <a href="{{ route('admin.projects.create', ['specialization_id' => $specialization->id]) }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-plus me-1"></i> إضافة مشروع تخرج جديد لهذا الاختصاص
                </a>
            </div>
        </div>
    </div>

</div>
@endsection