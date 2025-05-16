@extends('admin.layouts.app')

@section('title', 'إدارة طلبات تسجيل الفعاليات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-clipboard-check me-2"></i>إدارة طلبات تسجيل الفعاليات</h1>
        {{-- لا يوجد زر "إضافة" هنا عادةً لأن التسجيلات تأتي من الطلاب --}}
    </div>

    {{-- قسم الفلترة --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.event-registrations.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="event_id_filter" class="form-label">فلترة حسب الفعالية</label>
                        <select class="form-select form-select-sm" id="event_id_filter" name="event_id">
                            <option value="">-- كل الفعاليات --</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status_filter" class="form-label">فلترة حسب الحالة</label>
                        <select class="form-select form-select-sm" id="status_filter" name="status">
                            <option value="">-- كل الحالات --</option>
                            <option value="pending_approval" {{ request('status', 'pending_approval') == 'pending_approval' ? 'selected' : '' }}>قيد المراجعة</option>
                            <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>مسجل/مقبول</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                            <option value="waitlisted" {{ request('status') == 'waitlisted' ? 'selected' : '' }}>قائمة انتظار</option>
                            <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>حضر</option>
                            <option value="cancelled_by_student" {{ request('status') == 'cancelled_by_student' ? 'selected' : '' }}>ملغى من الطالب</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">فلترة</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.event-registrations.index') }}" class="btn btn-secondary btn-sm w-100">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($registrations->isEmpty())
                <div class="alert alert-info text-center">
                    @if(request()->filled('event_id') || request()->filled('status'))
                        لا توجد طلبات تسجيل تطابق معايير الفلترة الحالية.
                    @else
                        لا توجد طلبات تسجيل لعرضها حالياً (الطلبات قيد المراجعة تظهر بشكل افتراضي).
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الفعالية</th>
                                <th>الطالب</th>
                                <th>الرقم الجامعي</th>
                                <th>تاريخ الطلب</th>
                                <th>الحالة</th>
                                <th>حضر؟</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                            <tr>
                                <td>{{ $registration->id }}</td>
                                <td>
                                    @if($registration->event)
                                        <a href="{{ route('admin.events.show', $registration->event) }}">{{ Str::limit($registration->event->title_ar, 30) }}</a>
                                    @else
                                        <span class="text-muted">فعالية محذوفة</span>
                                    @endif
                                </td>
                                <td>
                                    @if($registration->student)
                                        <a href="{{ route('admin.students.show', $registration->student) }}">{{ $registration->student->full_name_ar }}</a>
                                    @else
                                        <span class="text-muted">طالب محذوف</span>
                                    @endif
                                </td>
                                <td>{{ $registration->student->student_university_id ?? '-' }}</td>
                                <td>{{ $registration->registration_datetime->translatedFormat('Y-m-d H:i') }}</td>
                                <td>
                                    @if($registration->status == 'pending_approval')
                                        <span class="badge bg-warning text-dark">قيد المراجعة</span>
                                    @elseif($registration->status == 'registered' || $registration->status == 'approved')
                                        <span class="badge bg-success">مسجل/مقبول</span>
                                    @elseif($registration->status == 'rejected')
                                        <span class="badge bg-danger">مرفوض</span>
                                    @elseif($registration->status == 'waitlisted')
                                        <span class="badge bg-info">قائمة انتظار</span>
                                    @elseif($registration->status == 'cancelled_by_student')
                                        <span class="badge bg-secondary">ملغى من الطالب</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $registration->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($registration->attended === true)
                                        <span class="badge bg-primary">نعم</span>
                                    @elseif($registration->attended === false && ($registration->status == 'registered' || $registration->status == 'approved'))
                                        <span class="badge bg-secondary">لم يحضر</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.event-registrations.show', $registration) }}" class="btn btn-sm btn-info" title="عرض التفاصيل"><i class="fas fa-eye"></i></a>
                                    @if($registration->status == 'pending_approval')
                                        <form action="{{ route('admin.event-registrations.approve', $registration) }}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="موافقة"><i class="fas fa-check"></i></button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" title="رفض" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $registration->id }}"><i class="fas fa-times"></i></button>
                                        <!-- Modal للرفض -->
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
                                                            {{-- يمكنك إضافة حقل لسبب الرفض هنا إذا أردت --}}
                                                            {{-- <div class="mb-3">
                                                                <label for="rejection_reason_{{ $registration->id }}" class="form-label">سبب الرفض (اختياري):</label>
                                                                <textarea class="form-control" id="rejection_reason_{{ $registration->id }}" name="rejection_reason" rows="2"></textarea>
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
                                    {{-- يمكنك إضافة إجراءات أخرى مثل وضع علامة "حضر" --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $registrations->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection