@extends('admin.layouts.app')

@section('title', 'تفاصيل الطالب: ' . $student->full_name_ar)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-user-graduate me-2"></i>تفاصيل الطالب: {{ $student->full_name_ar }}</h1>
        <div>
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i> تعديل</a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
        </div>
    </div>

    <div class="card">
        <div class="row g-0">
            <div class="col-md-3 text-center p-3 border-end">
                @if($student->profile_picture_url)
                    <img src="{{ Storage::url($student->profile_picture_url) }}" alt="{{ $student->full_name_ar }}" class="img-fluid rounded-circle mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <i class="fas fa-user fa-5x text-secondary mb-2"></i>
                @endif
                <h5 class="card-title">{{ $student->full_name_ar }}</h5>
                <p class="card-text"><small class="text-muted">{{ $student->student_university_id }}</small></p>
                @if($student->is_active)
                    <span class="badge bg-success">نشط</span>
                @else
                    <span class="badge bg-danger">غير نشط</span>
                @endif
            </div>
            <div class="col-md-9">
                <div class="card-body">
                    <h5 class="card-title mb-3">معلومات الطالب</h5>
                    <dl class="row">
                        <dt class="col-sm-4">الاسم (عربي):</dt>
                        <dd class="col-sm-8">{{ $student->full_name_ar }}</dd>

                        <dt class="col-sm-4">الاسم (إنجليزي):</dt>
                        <dd class="col-sm-8">{{ $student->full_name_en ?: '-' }}</dd>

                        <dt class="col-sm-4">الرقم الجامعي:</dt>
                        <dd class="col-sm-8">{{ $student->student_university_id }}</dd>

                        <dt class="col-sm-4">البريد الإلكتروني:</dt>
                        <dd class="col-sm-8">{{ $student->email }}</dd>

                        <dt class="col-sm-4">الاختصاص:</dt>
                        <dd class="col-sm-8">{{ $student->specialization->name_ar ?? 'غير محدد' }}</dd>

                        <dt class="col-sm-4">سنة الالتحاق:</dt>
                        <dd class="col-sm-8">{{ $student->enrollment_year ?: '-' }}</dd>

                        <dt class="col-sm-4">آخر إجراء إداري بواسطة:</dt>
                        <dd class="col-sm-8">{{ $student->adminActionBy->name_ar ?? '-' }} @if($student->admin_action_at) (في: {{ $student->admin_action_at->translatedFormat('Y-m-d') }}) @endif</dd>

                        @if($student->admin_action_notes)
                        <dt class="col-sm-4">ملاحظات إدارية:</dt>
                        <dd class="col-sm-8">{{ $student->admin_action_notes }}</dd>
                        @endif

                        <dt class="col-sm-4">تاريخ الإضافة:</dt>
                        <dd class="col-sm-8">{{ $student->created_at->translatedFormat('Y-m-d H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
   <div class="card mt-4">
        <div class="card-header">
            <h4><i class="fas fa-book-open me-2"></i>المقررات المسجل بها الطالب ({{ $student->courseEnrollments->count() }})</h4>
        </div>
        <div class="card-body">
            @if($student->courseEnrollments->isEmpty())
                <p class="text-muted">لم يسجل الطالب في أي مقررات حالياً.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($student->courseEnrollments as $enrollment)
                        <li class="list-group-item">
                            @if ($enrollment->course) {{-- <<< التحقق هنا --}}
                                <a href="{{ route('admin.courses.show', $enrollment->course) }}">{{ $enrollment->course->name_ar ?? 'مقرر غير معروف' }}</a>
                                ({{ $enrollment->course->code ?? '-' }}) - الفصل: {{ $enrollment->semester_enrolled }}
                                <br>
                                <small>الحالة: {{ $enrollment->status }} @if($enrollment->grade) | الدرجة: {{ $enrollment->grade }} @endif</small>
                            @else
                                <span class="text-danger">مقرر محذوف (ID المقرر في التسجيل: {{ $enrollment->course_id }})</span>
                                - الفصل: {{ $enrollment->semester_enrolled }}
                                <br>
                                <small>الحالة: {{ $enrollment->status }} @if($enrollment->grade) | الدرجة: {{ $enrollment->grade }} @endif</small>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h4><i class="fas fa-calendar-check me-2"></i>الفعاليات المسجل بها الطالب ({{ $student->eventRegistrations->count() }})</h4>
        </div>
        <div class="card-body">
            @if($student->eventRegistrations->isEmpty())
                <p class="text-muted">لم يسجل الطالب في أي فعاليات حالياً.</p>
            @else
                 <ul class="list-group list-group-flush">
                    @foreach($student->eventRegistrations as $registration)
                        <li class="list-group-item">
                            @if ($registration->event) {{-- <<< التحقق هنا أيضاً للفعاليات --}}
                                <a href="{{ route('admin.events.show', $registration->event) }}">{{ $registration->event->title_ar ?? 'فعالية غير معروفة' }}</a>
                                 - <small>حالة التسجيل: {{ $registration->status }}</small>
                            @else
                                <span class="text-danger">فعالية محذوفة (ID الفعالية في التسجيل: {{ $registration->event_id }})</span>
                                - <small>حالة التسجيل: {{ $registration->status }}</small>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</div>
@endsection