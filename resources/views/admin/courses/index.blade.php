@extends('admin.layouts.app')

@section('title', 'إدارة المقررات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-book-open me-2"></i>إدارة المقررات</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> إضافة مقرر جديد
        </a>
    </div>

    {{-- قسم الفلترة --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.courses.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="specialization_id_filter" class="form-label">فلترة حسب الاختصاص</label>
                        <select class="form-select" id="specialization_id_filter" name="specialization_id">
                            <option value="">-- كل الاختصاصات --</option>
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization->id }}" {{ request('specialization_id') == $specialization->id ? 'selected' : '' }}>
                                    {{ $specialization->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- يمكنك إضافة فلاتر أخرى هنا مثل (السنة، الفصل، إمكانية التسجيل) --}}
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">فلترة</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary w-100">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($courses->isEmpty())
                <div class="alert alert-info text-center">لا توجد مقررات لعرضها حالياً.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الرمز</th>
                                <th>الاسم (عربي)</th>
                                <th>الاختصاص</th>
                                <th>الفصل/السنة</th>
                                <th>الساعات</th>
                                <th>تسجيل متاح</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                            <tr>
                                <td>{{ $course->id }}</td>
                                <td>{{ $course->code }}</td>
                                <td>{{ $course->name_ar }}</td>
                                <td>{{ $course->specialization->name_ar ?? 'غير محدد' }}</td>
                                <td>{{ $course->semester_display_info }} @if($course->year_level) (سنة {{ $course->year_level }}) @endif</td>
                                <td>{{ $course->credits ?: '-' }}</td>
                                <td>
                                    @if($course->is_enrollable)
                                        <span class="badge bg-success">نعم</span>
                                    @else
                                        <span class="badge bg-danger">لا</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-info" title="عرض وتفاصيل"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المقرر؟ سيؤدي هذا إلى حذف موارده وتعلقاته الأخرى.');">
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
                    {{ $courses->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection