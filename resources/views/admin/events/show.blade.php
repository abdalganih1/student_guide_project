@extends('admin.layouts.app')

@section('title', 'تفاصيل الفعالية: ' . $event->title_ar)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-calendar-check me-2"></i>تفاصيل الفعالية: {{ $event->title_ar }}</h1>
        <div>
            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i> تعديل</a>
            <a href="{{ route('admin.event-registrations.index', ['event_id' => $event->id]) }}" class="btn btn-warning"><i class="fas fa-users me-1"></i> عرض طلبات التسجيل ({{ $event->registeredStudents->count() }})</a>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
        </div>
    </div>

    <div class="card">
        <div class="row g-0">
            @if($event->main_image_url)
            <div class="col-md-4 text-center p-3 border-end">
                <img src="{{ Storage::url($event->main_image_url) }}" alt="{{ $event->title_ar }}" class="img-fluid rounded" style="max-height: 300px; object-fit: contain;">
            </div>
            @endif
            <div class="{{ $event->main_image_url ? 'col-md-8' : 'col-md-12' }}">
                <div class="card-body">
                    <h3 class="card-title">{{ $event->title_ar }}</h3>
                    @if($event->title_en)
                        <h5 class="text-muted">{{ $event->title_en }}</h5>
                    @endif
                    <hr>
                    <dl class="row">
                        <dt class="col-sm-4">تاريخ ووقت البدء:</dt>
                        <dd class="col-sm-8">{{ $event->event_start_datetime->translatedFormat('l, d F Y - H:i A') }}</dd>

                        @if($event->event_end_datetime)
                        <dt class="col-sm-4">تاريخ ووقت الانتهاء:</dt>
                        <dd class="col-sm-8">{{ $event->event_end_datetime->translatedFormat('l, d F Y - H:i A') }}</dd>
                        @endif

                        <dt class="col-sm-4">الموقع:</dt>
                        <dd class="col-sm-8">{{ $event->location_text ?: '-' }}</dd>

                        <dt class="col-sm-4">التصنيف:</dt>
                        <dd class="col-sm-8">{{ $event->category ?: '-' }}</dd>

                        <dt class="col-sm-4">الحالة:</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $event->status == 'scheduled' ? 'info' : ($event->status == 'ongoing' ? 'primary' : ($event->status == 'completed' ? 'success' : ($event->status == 'cancelled' ? 'danger' : 'secondary'))) }}">
                                {{ $statuses[$event->status] ?? $event->status }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">يتطلب تسجيل:</dt>
                        <dd class="col-sm-8">{{ $event->requires_registration ? 'نعم' : 'لا' }}</dd>

                        @if($event->requires_registration)
                        <dt class="col-sm-4">الموعد النهائي للتسجيل:</dt>
                        <dd class="col-sm-8">{{ $event->registration_deadline ? $event->registration_deadline->translatedFormat('l, d F Y - H:i A') : '-' }}</dd>
                        @endif

                        <dt class="col-sm-4">الحد الأقصى للحضور:</dt>
                        <dd class="col-sm-8">{{ $event->max_attendees ?: 'غير محدد' }}</dd>

                        <dt class="col-sm-4">الجهة المنظمة:</dt>
                        <dd class="col-sm-8">{{ $event->organizer_info ?: ($event->organizingFaculty->name_ar ?? '-') }}</dd>

                        @if($event->organizingFaculty)
                        <dt class="col-sm-4">الكلية المنظمة:</dt>
                        <dd class="col-sm-8"><a href="{{ route('admin.faculties.show', $event->organizingFaculty) }}">{{ $event->organizingFaculty->name_ar }}</a></dd>
                        @endif
                    </dl>
                    <hr>
                    <h5>الوصف (عربي):</h5>
                    <div style="white-space: pre-wrap;">{{ $event->description_ar }}</div>

                    @if($event->description_en)
                    <hr>
                    <h5>الوصف (إنجليزي):</h5>
                    <div style="white-space: pre-wrap;">{{ $event->description_en }}</div>
                    @endif
                    <hr>
                     <small class="text-muted">
                        تم إنشاؤه بواسطة: {{ $event->createdByAdmin->name_ar ?? 'غير معروف' }} في {{ $event->created_at->translatedFormat('Y-m-d') }} <br>
                        آخر تحديث بواسطة: {{ $event->lastUpdatedByAdmin->name_ar ?? 'غير معروف' }} في {{ $event->updated_at->translatedFormat('Y-m-d') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- قسم الطلاب المسجلين --}}
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>الطلاب المسجلون ({{ $event->registeredStudents->count() }})</h5>
            <a href="{{ route('admin.event-registrations.index', ['event_id' => $event->id]) }}" class="btn btn-sm btn-outline-primary">إدارة التسجيلات</a>
        </div>
         <div class="card-body">
            @if($event->registeredStudents->isEmpty())
                <p class="text-muted">لا يوجد طلاب مسجلون في هذه الفعالية حالياً.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($event->registeredStudents as $registration)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                            @if ($registration->student)
                                <a href="{{ route('admin.students.show', $registration->student) }}">{{ $registration->student->full_name_ar }}</a>
                                <small class="text-muted">({{ $registration->student->student_university_id }})</small>
                            @else
                                <span class="text-danger">طالب محذوف (ID: {{ $registration->student_id }})</span>
                            @endif
                            </div>
                            <div>
                                <span class="badge bg-{{ $registration->status == 'registered' || $registration->status == 'approved' ? 'success' : ($registration->status == 'pending_approval' ? 'warning text-dark' : 'danger') }}">
                                    @if($registration->status == 'registered' || $registration->status == 'approved') تم التسجيل
                                    @elseif($registration->status == 'pending_approval') قيد المراجعة
                                    @elseif($registration->status == 'rejected') مرفوض
                                    @elseif($registration->status == 'waitlisted') قائمة انتظار
                                    @elseif($registration->status == 'cancelled_by_student') ملغى من الطالب
                                    @else {{ $registration->status }}
                                    @endif
                                </span>
                                @if($registration->attended)
                                    <span class="badge bg-info">حضر</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</div>
@endsection