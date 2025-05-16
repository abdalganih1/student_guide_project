@extends('admin.layouts.app')

@section('title', 'تفاصيل التنبيه: ' . Str::limit($notification->title_ar, 30))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-info-circle me-2"></i>تفاصيل التنبيه</h1>
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>{{ $notification->title_ar }}</h5>
            @if($notification->title_en) <p class="text-muted mb-0">{{ $notification->title_en }}</p> @endif
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">نص التنبيه (عربي):</dt>
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $notification->body_ar }}</dd>

                @if($notification->body_en)
                <dt class="col-sm-3">نص التنبيه (إنجليزي):</dt>
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $notification->body_en }}</dd>
                @endif

                <dt class="col-sm-3">النوع:</dt>
                <dd class="col-sm-9">{{ $notification->type }}</dd>

                <dt class="col-sm-3">المرسل:</dt>
                <dd class="col-sm-9">{{ $notification->sentByAdmin->username ?? 'غير معروف' }}</dd>

                <dt class="col-sm-3">تاريخ النشر:</dt>
                <dd class="col-sm-9">{{ $notification->publish_datetime->translatedFormat('l, d F Y - H:i A') }}</dd>

                @if($notification->expiry_datetime)
                <dt class="col-sm-3">تاريخ انتهاء الصلاحية:</dt>
                <dd class="col-sm-9">{{ $notification->expiry_datetime->translatedFormat('l, d F Y - H:i A') }}</dd>
                @endif

                <dt class="col-sm-3">الجمهور المستهدف:</dt>
                <dd class="col-sm-9">
                    @if($notification->target_audience_type == 'all')
                        جميع الطلاب
                    @elseif($notification->target_audience_type == 'course_specific' && $notification->relatedCourse)
                        طلاب مقرر: <a href="{{ route('admin.courses.show', $notification->relatedCourse) }}">{{ $notification->relatedCourse->name_ar }}</a>
                    @elseif($notification->target_audience_type == 'custom_group' || $notification->target_audience_type == 'individual')
                        مجموعة مخصصة / أفراد ({{ $notification->recipients->count() }} طالب)
                    @else
                        {{ $notification->target_audience_type }}
                    @endif
                </dd>

                @if($notification->relatedCourse)
                <dt class="col-sm-3">المقرر المرتبط:</dt>
                <dd class="col-sm-9"><a href="{{ route('admin.courses.show', $notification->relatedCourse) }}">{{ $notification->relatedCourse->name_ar }}</a></dd>
                @endif

                @if($notification->relatedEvent)
                <dt class="col-sm-3">الفعالية المرتبطة:</dt>
                <dd class="col-sm-9"><a href="{{ route('admin.events.show', $notification->relatedEvent) }}">{{ $notification->relatedEvent->title_ar }}</a></dd>
                @endif
            </dl>

            @if($notification->target_audience_type == 'custom_group' || $notification->target_audience_type == 'individual')
                <hr>
                <h5>الطلاب المستلمون:</h5>
                @if($notification->recipients->isEmpty())
                    <p class="text-muted">لم يتم تحديد طلاب مستلمين بشكل فردي لهذا التنبيه.</p>
                @else
                    <ul>
                        @foreach($notification->recipients as $student)
                            <li><a href="{{ route('admin.students.show', $student) }}">{{ $student->full_name_ar }}</a> ({{ $student->student_university_id }})</li>
                        @endforeach
                    </ul>
                @endif
            @endif
        </div>
        <div class="card-footer text-muted">
            تم الإنشاء في: {{ $notification->created_at->translatedFormat('Y-m-d H:i') }}
        </div>
    </div>
</div>
@endsection