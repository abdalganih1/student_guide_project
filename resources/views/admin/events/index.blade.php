@extends('admin.layouts.app')

@section('title', 'إدارة الفعاليات والمسابقات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-calendar-alt me-2"></i>إدارة الفعاليات والمسابقات</h1>
        <a href="{{ route('admin.events.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> إضافة فعالية جديدة
        </a>
    </div>

    {{-- قسم الفلترة --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.events.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="search_filter" class="form-label">بحث (عنوان، وصف)</label>
                        <input type="text" class="form-control form-control-sm" id="search_filter" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="status_filter" class="form-label">الحالة</label>
                        <select class="form-select form-select-sm" id="status_filter" name="status">
                            <option value="">-- كل الحالات --</option>
                            @foreach($statuses as $statusKey => $statusName)
                                <option value="{{ $statusKey }}" {{ request('status') == $statusKey ? 'selected' : '' }}>
                                    {{ $statusName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="faculty_id_filter" class="form-label">الكلية المنظمة</label>
                        <select class="form-select form-select-sm" id="faculty_id_filter" name="faculty_id">
                            <option value="">-- كل الكليات --</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">فلترة</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-sm w-100">إلغاء</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($events->isEmpty())
                <div class="alert alert-info text-center">لا توجد فعاليات لعرضها حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>العنوان (عربي)</th>
                                <th>تاريخ البدء</th>
                                <th>الحالة</th>
                                <th>الكلية المنظمة</th>
                                <th>التسجيل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td>{{ $event->id }}</td>
                                <td>
                                    @if($event->main_image_url)
                                        <img src="{{ Storage::url($event->main_image_url) }}" alt="{{ $event->title_ar }}" class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                                    @else
                                        <i class="fas fa-image fa-2x text-secondary"></i>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.events.show', $event) }}">{{ Str::limit($event->title_ar, 40) }}</a>
                                    @if($event->title_en)<small class="d-block text-muted">{{ Str::limit($event->title_en, 40) }}</small>@endif
                                </td>
                                <td>{{ $event->event_start_datetime->translatedFormat('Y-m-d H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $event->status == 'scheduled' ? 'info' : ($event->status == 'ongoing' ? 'primary' : ($event->status == 'completed' ? 'success' : ($event->status == 'cancelled' ? 'danger' : 'secondary'))) }}">
                                        {{ $statuses[$event->status] ?? $event->status }}
                                    </span>
                                </td>
                                <td>{{ $event->organizingFaculty->name_ar ?? '-' }}</td>
                                <td>{{ $event->requires_registration ? 'مطلوب' : 'غير مطلوب' }}</td>
                                <td>
                                    <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.event-registrations.index', ['event_id' => $event->id]) }}" class="btn btn-sm btn-warning" title="طلبات التسجيل"><i class="fas fa-users"></i></a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذه الفعالية؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $events->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection