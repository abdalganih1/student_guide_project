@extends('admin.layouts.app')

@section('title', 'تفاصيل طلب تسجيل الفعالية')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-file-alt me-2"></i>تفاصيل طلب تسجيل</h1>
        <a href="{{ route('admin.event-registrations.index', ['event_id' => $registration->event_id, 'status' => $registration->status]) }}" class="btn btn-secondary">العودة إلى القائمة</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>طلب رقم: {{ $registration->id }}</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">الفعالية:</dt>
                <dd class="col-sm-9">
                    @if($registration->event)
                        <a href="{{ route('admin.events.show', $registration->event) }}">{{ $registration->event->title_ar }}</a>
                    @else
                        <span class="text-muted">فعالية محذوفة</span>
                    @endif
                </dd>

                <dt class="col-sm-3">الطالب:</dt>
                <dd class="col-sm-9">
                    @if($registration->student)
                        <a href="{{ route('admin.students.show', $registration->student) }}">{{ $registration->student->full_name_ar }}</a>
                        ({{ $registration->student->student_university_id }})
                    @else
                        <span class="text-muted">طالب محذوف</span>
                    @endif
                </dd>

                <dt class="col-sm-3">البريد الإلكتروني للطالب:</dt>
                <dd class="col-sm-9">{{ $registration->student->email ?? '-' }}</dd>

                <dt class="col-sm-3">تاريخ الطلب:</dt>
                <dd class="col-sm-9">{{ $registration->registration_datetime->translatedFormat('l, d F Y - H:i A') }}</dd>

                <dt class="col-sm-3">الحالة الحالية:</dt>
                <dd class="col-sm-9">
                    @if($registration->status == 'pending_approval')
                        <span class="badge bg-warning text-dark">قيد المراجعة</span>
                    @elseif($registration->status == 'registered' || $registration->status == 'approved')
                        <span class="badge bg-success">مسجل/مقبول</span>
                    @elseif($registration->status == 'rejected')
                        <span class="badge bg-danger">مرفوض</span>
                    @elseif($registration->status == 'waitlisted')
                         <span class="badge bg-info">قائمة انتظار</span>
                    @else
                        <span class="badge bg-secondary">{{ $registration->status }}</span>
                    @endif
                </dd>

                <dt class="col-sm-3">هل حضر الطالب؟</dt>
                <dd class="col-sm-9">
                    @if($registration->attended === true)
                        <span class="text-success">نعم</span>
                    @elseif($registration->attended === false && ($registration->status == 'registered' || $registration->status == 'approved'))
                        <span class="text-danger">لا</span>
                    @else
                        -
                    @endif
                </dd>

                @if($registration->notes)
                <dt class="col-sm-3">ملاحظات إضافية من الطالب (إن وجدت):</dt>
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $registration->notes }}</dd>
                @endif

                {{-- يمكنك إضافة سبب الرفض إذا قمت بتخزينه --}}
                {{-- @if($registration->status == 'rejected' && $registration->rejection_reason)
                <dt class="col-sm-3">سبب الرفض:</dt>
                <dd class="col-sm-9 text-danger">{{ $registration->rejection_reason }}</dd>
                @endif --}}

            </dl>

            @if($registration->status == 'pending_approval')
            <hr>
            <h5>الإجراءات:</h5>
            <div class="mt-3">
                <form action="{{ route('admin.event-registrations.approve', $registration) }}" method="POST" class="d-inline me-2">
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> موافقة على الطلب</button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $registration->id }}"><i class="fas fa-times me-1"></i> رفض الطلب</button>
            </div>

            <!-- Modal للرفض (نفس الـ modal من صفحة index) -->
            <div class="modal fade" id="rejectModal{{ $registration->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $registration->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.event-registrations.reject', $registration) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="rejectModalLabel{{ $registration->id }}">رفض طلب تسجيل الطالب: {{ $registration->student->full_name_ar ?? 'غير معروف' }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>هل أنت متأكد من رفض طلب التسجيل هذا؟</p>
                                {{-- <div class="mb-3">
                                    <label for="rejection_reason_show_{{ $registration->id }}" class="form-label">سبب الرفض (اختياري):</label>
                                    <textarea class="form-control" id="rejection_reason_show_{{ $registration->id }}" name="rejection_reason" rows="2"></textarea>
                                </div> --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-danger">تأكيد الرفض</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection