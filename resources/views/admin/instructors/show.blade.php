@extends('admin.layouts.app')

@section('title', 'تفاصيل المدرس: ' . $instructor->name_ar)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-user-tie me-2"></i>تفاصيل المدرس: {{ $instructor->name_ar }}</h1>
        <div>
            <a href="{{ route('admin.instructors.edit', $instructor) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i> تعديل</a>
            <a href="{{ route('admin.instructors.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
        </div>
    </div>

    <div class="card">
        <div class="row g-0">
            <div class="col-md-3 text-center p-3 border-end">
                @if($instructor->profile_picture_url)
                    <img src="{{ Storage::url($instructor->profile_picture_url) }}" alt="{{ $instructor->name_ar }}" class="img-fluid rounded-circle mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <i class="fas fa-user-tie fa-5x text-secondary mb-2"></i>
                @endif
                <h5 class="card-title">{{ $instructor->name_ar }}</h5>
                <p class="card-text"><small class="text-muted">{{ $instructor->title ?: 'مدرس' }}</small></p>
                @if($instructor->is_active)
                    <span class="badge bg-success">نشط</span>
                @else
                    <span class="badge bg-danger">غير نشط</span>
                @endif
            </div>
            <div class="col-md-9">
                <div class="card-body">
                    <h5 class="card-title mb-3">معلومات المدرس</h5>
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>الاسم (عربي):</strong> {{ $instructor->name_ar }}</p>
                            <p><strong>البريد الإلكتروني:</strong> {{ $instructor->email ?: '-' }}</p>
                            <p><strong>الكلية:</strong> {{ $instructor->faculty->name_ar ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong>الاسم (إنجليزي):</strong> {{ $instructor->name_en ?: '-' }}</p>
                            <p><strong>موقع المكتب:</strong> {{ $instructor->office_location ?: '-' }}</p>
                            <p><strong>تاريخ الإضافة:</strong> {{ $instructor->created_at->translatedFormat('Y-m-d') }}</p>
                        </div>
                    </div>
                    @if($instructor->bio)
                    <hr>
                    <h6>نبذة تعريفية:</h6>
                    <p>{{ $instructor->bio }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- تم إزالة أو تعديل هذا القسم --}}
    {{-- إذا كنت لا تريد عرض المقررات نهائياً، احذف هذا القسم بالكامل --}}
    {{-- إذا كنت تريد الإشارة إلى عدم وجود ربط مباشر، يمكنك تعديل النص --}}
    <div class="card mt-4">
        <div class="card-header">
            <h4><i class="fas fa-book me-2"></i>المقررات</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">لا يتم ربط المدرسين بالمقررات بشكل مباشر في هذا النظام حالياً من خلال ملف المدرس الشخصي.</p>
            {{-- أو يمكنك عرض قائمة بجميع المقررات التي قد يكون لها صلة بالكلية التي ينتمي إليها المدرس، إذا كان ذلك منطقيًا --}}
            {{-- <p class="text-muted">لعرض المقررات، يرجى الذهاب إلى قسم إدارة المقررات.</p> --}}
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4><i class="fas fa-graduation-cap me-2"></i>المشاريع التي يشرف عليها ({{ $instructor->supervisedProjects->count() }})</h4>
        </div>
        <div class="card-body">
             @if($instructor->supervisedProjects->isEmpty())
                <p class="text-muted">لا يوجد مشاريع يشرف عليها هذا المدرس حالياً.</p>
            @else
                <ul class="list-group">
                    @foreach($instructor->supervisedProjects as $project)
                        <li class="list-group-item">
                            <a href="{{ route('admin.projects.show', $project) }}">{{ $project->title_ar }}</a> ({{ $project->year }})
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection